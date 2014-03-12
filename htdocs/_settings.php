<?php
// HomeHub configuration file
//                              ↓  Edit these  ↓
return array(
    'notification_email' =>     'your@email.com',   /* Email where you want notifications */

    //Connection information
    'arduino_ip' =>             '0.0.0.0',          /* Server arduino's IP */
    'arduino_port' =>           80,                 /* Server arduino's port, default: 80 */
    'arduino_secret' =>         'secret',           /* Secret you set to arduino server */
    'mysql_ip' =>               '127.0.0.1',        /* IP of MySQL Server, usually 127.0.0.1 (localhost) */
    'mysql_port' =>             3306,               /* MySQL server port, almost always 3306 */
    'mysql_database' =>         'database',         /* Database name you created to MySQL server */
    'mysql_user' =>             'your_mysql_user',  /* Database user you created to MySQL server */
    'mysql_password' =>         'your_mysql_pw',    /* Database user's password */

    // Api
    'api_secret' => 'any_secret_for_api',           /* Select any secret for api, special chars are bad, mmkay? */


    // 
    // Do not edit after this line unless you know what you're doing
    // 



    // Available pages
    'pages_whitelist' => array
    (
        'home',
        'manage-rules',
        'manage-actions',
        'manage-commands',
        'manage-sensors'
    ),


);
?>