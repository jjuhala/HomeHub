/*  
 *  Button for Home Hub home automation system
 *  
 *  This is a button which you can execute some action with
 *  When the button is pressed, given message is transmitted over 433MHz
 *  Set up a sensor matching given command and then set the new sensor 
 *  as trigger for wanted action.
 *  You can set up two different commands: Quick press and long press
 *
 *      Copyright (C) 2014 Janne Juhala 
 *      http://jjj.fi/
 *      janne.juhala@outlook.com
 *      https://github.com/jjuhala/HomeHub
 *
 *  This project is licensed under the terms of the MIT license.
 *  
 *  TODO: Long press not working yet
 */

/* -------------------- Settings -------------------- */
/* Quick press cmd */          String qCmd = "B1";
/* Enable long press */        boolean enableLong = false;
/* Long press cmd */           String lCmd ="B1L";
/* Long press time (ms) */     int lpTime = 1000;
/* Button pin */               int btnPin = 3;
/* 433MHz Transmit Speed */    int transmitSpd = 1000;
/* 433MHz transmitter pin */   int transmitterPin = 2;
/* -------------------- -------- -------------------- */

#include <VirtualWire.h>
#undef int
#undef abs
#undef double
#undef float
#undef round

void setup()
{
  Serial.begin(9600);
  vw_set_ptt_inverted(true);
  vw_set_tx_pin(transmitterPin);
  vw_setup(1000);
  vw_rx_start();
  Serial.println("Ready for some action");
  pinMode(btnPin, INPUT_PULLUP);
}

boolean shortPress = false;
boolean longPress = false;

void loop()
{
  int btnVal = digitalRead(btnPin);
  if (btnVal == 1) {
    // If button was jsut released, sleep a bit
    if (shortPress) {
      delay(100); 
    }
    
    // Button not pressed
    shortPress = false;
    longPress = false;
  } else if (btnVal == 0) {
    // Button pressed
    if (enableLong==false && shortPress ==false) {
      // Set shortPress to true so we dont send it again
      // until button is released and pressed again
      shortPress = true;
      
      // Long press not enabled and this is first time
      // this button press was detected -> send msg
      sendMsg(qCmd);
      

      // Sleep for a while to avoid button tremble
      // causing msg to be sent multiple times
      delay(50);
      
      Serial.println("yup");
    }
    // If long presss not active, send cmd 
  }

}


// Send message and wait for it to be sent
void sendMsg(String message) {
    char convBuf[15];
    message.toCharArray(convBuf,15);
    const char *msge = convBuf;
    vw_send((uint8_t *)msge, strlen(msge));
    vw_wait_tx();
}
