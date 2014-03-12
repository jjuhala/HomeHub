/*  
 *  Arduino Server for HomeHub home automation system
 *  Early dev version
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
#undef int
#undef abs
#undef double
#undef float
#undef round
#define BUFSIZ 100

byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
IPAddress ip(192, 168, 1, 200);
EthernetServer server(9999); 
byte gateway[] = { 192, 168, 1, 1 };
byte subnet[] = { 255, 255, 255, 0 };
void setup()
{
    Ethernet.begin(mac, ip, gateway, subnet);
    server.begin();
    Serial.begin(9600);
    vw_set_ptt_inverted(true);
    vw_set_tx_pin(6);
    vw_set_rx_pin(7);
    vw_setup(1000);
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
                  client.println("Content-Type: text/html");
                  client.println();
                  client.println("ERROR|EmptyRequest");
                } else if (strstr(clientline, "GET /") != 0) {
                  char *filename;
                  filename = clientline + 5;
                  (strstr(clientline, " HTTP"))[0] = 0;
                  processReq(filename);
                  client.println("HTTP/1.1 200 OK");
                  client.println("Content-Type: text/plain");
                  client.println();
                  client.println("OK|ProcessedCmd");
                  //client.println(filename);
                } else {
                  // everything else is a 404
                  client.println("HTTP/1.1 404 Not Found");
                  client.println("Content-Type: text/html");
                  client.println();
                  client.println("<h2>404</h2>");
                }
                break;
                
            } // if (client.available())
        } // while (client.connected())
        delay(1);
        client.stop();
    } // if (client)
}


void processReq(String req) {
  Serial.println(req);
  char convBuf[100];
  req.toCharArray(convBuf,100);
  char *str;
  char *p = convBuf;
  while ((str = strtok_r(p, ";", &p)) != NULL) {
    Serial.println(str);
    const char*semesg = str;
    vw_send((uint8_t *)str, strlen(str));
    vw_wait_tx();
  }
}
