/*  
 *  Basic Light Switch client for HomeHub homeautomation system
 *  
 *  This is a basic light switch client. 
 *  The state can be switched by cutting the power and restoring
 *  it or with message (on/off/switch) sent over 433MHz.
 *  You have to add spring (or foam or other bouncy material)
 *  to normal light switch so it never gets witched to "off" 
 *  beacuse then you couldn't turn the lamp on over 433MHz anymore.
 *  Circuit & hardware: http://xl.jjj.fi/bWIqYU.png
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

/* -------------------- Settings -------------------- */
/* 433MHz ON Cmd */            String onCmd = "2n";
/* 433MHz OFF Cmd */           String offCmd ="2f";
/* 433MHz SWITCH Cmd */        String swCmd = "2s";
/* 433MHz Transmit Speed */    int transmitSpd = 1000;
/* Light switch relay pin */   int relayPin = 4;
/* 433MHz receiver pin */      int receiverPin = 7;
/* Where to write the state */ int EEPROM_address = 0;
/* -------------------- -------- -------------------- */


#include <EEPROM.h>
#include <VirtualWire.h>
#undef int
#undef abs
#undef double
#undef float
#undef round

// Store relay state in var (less eeprom ios)
int relayOn = 0;

// millis() of last executed message.
// Server can send same message multiple times to make sure
// it goes through, but we want to execute it only once.
unsigned long lastMsg = 0;

void setup() {
  // Boot up VirtualWire for listening 433MHz
  vw_set_ptt_inverted(true);
  vw_set_rx_pin(receiverPin);
  vw_setup(transmitSpd);
  vw_rx_start();
  pinMode(relayPin,OUTPUT);
  
  // Read relay state before power out and set it to opposite
  relayOn = EEPROM.read(EEPROM_address);
  if (relayOn == 1) {
    relayOn = 0;
    digitalWrite(relayPin,LOW);
  } else {
    relayOn = 1;
    digitalWrite(relayPin,HIGH);
  }
  EEPROM.write(EEPROM_address, relayOn);
}

void loop(){
  // Set up receive buffer
  uint8_t buffer[VW_MAX_MESSAGE_LEN];
  uint8_t bufferlen = VW_MAX_MESSAGE_LEN;
  
  if (vw_get_message(buffer, &bufferlen)) {
    // Got message! Let's see if it's for this client
    
    // Do nothing if last cmd has been executed less than 800 ms ago
    // abs() because millis() resets every ~50 days
    if (abs(millis() - lastMsg) > 800) {
      lastMsg = millis();
      
      // Build received message String
      String rMsg = "";
      int i;
      for (i = 0; i < bufferlen; i++) {
        char add = buffer[i];
        rMsg += add; 
      }
      
      // Compare received String to this client's cmds (on/off/switch)
      if (rMsg == onCmd) {
        
        // ON cmd received!
        if (relayOn = 0) {
          relayOn = 1;
          EEPROM.write(EEPROM_address, relayOn);
          digitalWrite(relayPin,HIGH);
        }
      } else if(rMsg == offCmd) {
        
        // OFF cmd received!
        if (relayOn = 1) {
          relayOn = 0;
          EEPROM.write(EEPROM_address, relayOn);
          digitalWrite(relayPin,LOW);
        }
      } else if (rMsg == swCmd) {
        
        // SWITCH cmd received!
        if (relayOn == 0) {
          relayOn = 1;
          EEPROM.write(EEPROM_address, relayOn);
          digitalWrite(relayPin,HIGH);
        } else {
          relayOn = 0;
          EEPROM.write(EEPROM_address, relayOn);
          digitalWrite(relayPin,LOW);
        }
      } // rMsg == cmds
    } // millis-last > 800
  } // msg received
} // loop
