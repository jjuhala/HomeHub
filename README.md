# HomeHub home automation system
  
HomeHub is a open source home automation project built with C++ (Arduino), PHP+MySQL (Server), HTML5 etc (Frontend).
  
  
#### Please note that HomeHub is a work-in-process and is far from being ready.
#### At the moment it's in a very early stage and this read me is more of a description of what it will be. Because of such an early development stage I wont list which features work and which dont.
  
  
### Features
* Wireless
* Ready support for
  * controlling lights (any voltage)  
   You can implement this on lights with regular wall on/off switches without losing their functionality (just a tiny bit of modding needed)
  * controlling sockets (any voltage)
  * temperature sensors
  * humidity sensons
  * brightness sensons
  * magnetic switches to detect when door/window etc. is opened or closed
  * motion sensors
  * "Turn everything off"-buttons
  * beeper
  * physical buttons for any actions (ie. button to shut TV off, turn music on and put lights on)
  * XBMC control
  * infrared (controlling TV or anything that has IR remote)
* Very easy to implement any additional sensors
* Email notifications
* Manage rules & view status via web-based application
* Automated actions when sensor values meet the rules
* Scheduled actions
* HTTP API to control everything from any device connected to internet
* Keyboard shortcuts for Windows machines (ie. CTRL+NUM1 to switch lights on/off)
* Infrared control (ie. control lights with your TV remote)  
  
### Hardware & software requirements
* Webserver with PHP support (preferably apache)
* MySQL server
* Arduinos
  * Ethernet shield (for server-arduino)
  * 433MHz transmitters and receivers
  * Relays, transistors etc. for controlling lights and sockets
  * Any sensors you want (temperature, humidity, brightness, magnetic switches for doors/windows etc.)
  * IR receiver if you want to use remotes
  * IR transmitter if you want to control TV or any other devices with IR remotes
  * Magnetic switches if you want to detect door/window etc. positions  
  
  
HomeHub is my personal project and it comes without any kind of warranty.   
If you wake up 5 AM to your ceiling lights blinking or refrigerator temperature alarm, don't blame me :)  
  
  
If you found this useful, feel free to donate:  
BTC: 1Mz9JDmed8ZWMnMCyMK4fpiboD2eggNGVF  
Doge: DJ2z5FfP5EAHmAw3f2V9tyG4UwkEM1aiTJ  
Paypal: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=FHCUMTL6FPU6L&lc=US&item_name=JJJ&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted  
  
If you've build a home automation system using HomeHub, I'm open to hearing what kind of system you've put together.  
  
  
Cheers, Janne Juhala  