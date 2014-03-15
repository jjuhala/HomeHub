/*  
 *  Arduino Server for HomeHub home automation system
 *  This will handle all communication between HomeHub 
 *  www-server and arduinos around the house (433MHZ RF)
 *
 *  Early dev version, now supports receiving data from 433MHz RF
 *  and sending it to server via http get request
 *  
 *  
 *      Copyright (C) 2014 Janne Juhala 
 *      http://jjj.fi/
 *      janne.juhala@outlook.com
 *      https://github.com/jjuhala/HomeHub
 *
 *  This project is licensed under the terms of the MIT license.
 *  
 */
 
#include <SPI.h>
#include <Ethernet.h>
#include <VirtualWire.h>

/* ---------------------------- Settings ---------------------------- */
/* Set ip for this arduino */          IPAddress ip(192, 168, 1, ???);
/* Set port for server. Usually 80 */  int port = 80;
/* 433MHz Transmit Speed */            int transmitSpd = 1000;
/* 433MHz receiver pin */              int rxPin = 7;
/* 433MHz transmitter pin */           int txPin = 6;
/* URL to HomeHub api, no http:// */   const char* host="url_to_homehub_www";
/* URL to HomeHub api, no http:// */   String hostStr="url_to_homehub_www";
/* HomeHub api port, usually 80 */     int hostport = 80;
/* API secret set up for HomeHub */    String api_secret = "your_secret";
/* ---------------------------- -------- ---------------------------- */
byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };



#undef int
#undef abs
#undef double
#undef float
#undef round
#define BUFSIZ 100


EthernetServer server(port); 
void setup()
{
    Ethernet.begin(mac,ip);//, ip, gateway, subnet);
    delay(500);
    server.begin();
    Serial.begin(9600);
    vw_set_ptt_inverted(true);
    vw_set_tx_pin(txPin);
    vw_set_rx_pin(rxPin);
    vw_setup(transmitSpd);
    vw_rx_start();
}

void loop()
{
    char clientline[BUFSIZ];
    int index = 0;
    EthernetClient client = server.available(); 
    if (client) {
        boolean currentLineIsBlank = true;
        while (client.connected()) {
            if (client.available()) {
                char c = client.read();
                
                
                // Add the character to the buffer if it's not new line
                if (c != '\n' && c != '\r') {
                  clientline[index] = c;
                  index++;
                  
                  // ignore data if it's over buffer size
                  if (index >= BUFSIZ) {
                    index = BUFSIZ -1;
                  }
                  
                  // read more
                  continue;
                }
                
                // got new line -> string is done
                clientline[index] = 0;
         
                
                if (strstr(clientline, "GET / ") != 0) {
                  client.println("HTTP/1.1 200 OK");
                  client.println("Content-Type: text/plain");
                  client.println();
                  client.println("ERROR|EmptyRequest");
                } else if (strstr(clientline, "GET /") != 0) {
                  char *gmsg;
                  gmsg = clientline + 5;
                  (strstr(clientline, " HTTP"))[0] = 0;
                  processReq(gmsg,false);
                  client.println("HTTP/1.1 200 OK");
                  client.println("Content-Type: text/plain");
                  client.println();
                  client.println("OK|ProcessedCmd");
                } else {
                  // 404, should never happen
                  client.println("HTTP/1.1 404 Not Found");
                  client.println("Content-Type: text/plain");
                  client.println();
                  client.println("404");
                }
                break;
                
            } // if (client.available())
        } // while (client.connected())
        delay(1);
        client.stop();
    } // if (client)
    
    
    uint8_t buf[VW_MAX_MESSAGE_LEN];
    uint8_t buflen = VW_MAX_MESSAGE_LEN;
    
    // Check if there's message to receive
    if (vw_get_message(buf, &buflen)) {
      String rMsg = "";
      int i;
      for (i = 0; i < buflen; i++) {
        char ad = buf[i];
        rMsg += ad; 
      }
      Serial.println(rMsg);
      sendMsgToServer(rMsg, client);
    } // vw_get_msg
  
  
  
  
}
void sendMsgToServer (String msg,EthernetClient client) {
  if (client.connect(host,hostport)) {
    Serial.println("HomeHub api connected");
    client.println("GET /api/sensor_set?f=a&s="+api_secret+"&msg="+msg+" HTTP/1.1");
    Serial.println("GET /api/sensor_set?f=a&s="+api_secret+"&msg="+msg+" HTTP/1.1");
    client.println("Host: "+hostStr);
    //client.println("Content-Type: application/x-www-form-urlencoded");
    //client.println("Connection: close");
    client.println();
  } else {
     Serial.println("Can't connect to HomeHub api");
  } 
  int readNow = 0;
  int lastWasS = 0;
  int lastWas1e = 0;
  int doneReading = 0;
  String receivedMsg = ""; 
  while(client.connected() && !client.available()) delay(1); //waits for data
  while (client.connected() && client.available() && doneReading == 0) { //connected or data available
    char c = client.read(); //gets byte from ethernet buffer
    
    
    // Read and parse reply
    if (readNow == 0) {
      if (lastWasS == 1 && c == '>') {
        readNow = 1;
        lastWasS = 0;
      }
      if (c == 'S') {
        lastWasS = 1;
      } else {
        lastWasS = 0; 
      }
    } else {
      if (lastWas1e == 1 && c == 'E') {
        readNow = 0;
        doneReading = 1; 
        lastWas1e = 0;
      }
      if (c == '<' && readNow == 1) {
        lastWas1e = 1;
      } else {
        lastWas1e = 0;
        if (readNow == 1) {
          Serial.print(c); 
          receivedMsg += c;
        }
        
      }
    } // reading
    
    // Received msg and done reading, now send the command(s)
    
    if (receivedMsg != "" && doneReading == 1) {
      processReq(receivedMsg,true);
    }
    
     
  } // client connected && client available && doneReading = 0
} // SendMsgToServer

void processReq(String req,boolean wait) {
  Serial.println(req);
  char convBuf[100];
  req.toCharArray(convBuf,100);
  // Wait for radio silence
  if (wait) { delay(500); }
  
  // Repeat all messages 3 times
  for (int a=0;a<4;a++) {
    char *str;
    char *p = convBuf;
    while ((str = strtok_r(p, ";", &p)) != NULL) {
  
      Serial.println(str);
      const char*semesg = str;
      vw_send((uint8_t *)str, strlen(str));
      vw_wait_tx();
    }
  }
}
