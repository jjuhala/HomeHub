<?php
    /*
     *      Copyright (C) 2014 Janne Juhala 
     *      http://jjj.fi/
     *      janne.juhala@outlook.com
     *  	https://github.com/jjuhala/HomeHub
     *
     *  This project is licensed under the terms of the MIT license.
     *
     */
     
    $this->inc_cont_in_head = false;

    $this->actions_list = $this->query("SELECT * FROM hh_actions WHERE showOnUI = 1");
    $this->sensors_list = $this->query("SELECT * FROM hh_sensors WHERE showOnUI = 1");

?>