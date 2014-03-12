<?php
    /*
     *      Copyright (C) 2014 Janne Juhala 
     *      http://jjj.fi/
     *      janne.juhala@outlook.com
     *      https://github.com/jjuhala/HomeHub
     *
     *  This project is licensed under the terms of the MIT license.
     *
     */
    
    
// UI-class handles reading and printing the templates
class UI {
	protected $template_dir = 'templates/';
    protected $header_template = '_head.phtml';
    protected $footer_template = '_foot.phtml';
	protected $error_template = 'error.phtml';
	protected $debug_template = 'debug.phtml';
	protected $vars = array();
    protected $conf = array();


    // By default don't show any errors or notices
    protected $showError = false;
    protected $errorMsg = [];
    protected $showNotice = false;
    protected $noticeMsg = [];


    // Check that head & footer are available at class construct
	public function __construct() {
        if (file_exists($this->template_dir . $this->header_template) === false) {
            kill("Header template missing ($this->template_dir$this->header_template)",false);
        }
        if (file_exists($this->template_dir . $this->footer_template) === false) {
            kill("Footer template missing ($this->template_dir$this->footer_template)",false);
        }


    }

    // Validate input, require additional php dependencies and render the page
    public function validate_and_render($page) {
        if (isset($page['q'])) {
            $page = $page['q'];
        } else {
            $page = 'home';
        }
        if (in_array($page, $this->vars['conf']['pages_whitelist'])) {
            // Valid page
            // Check if there's additonal file to require for this page
            if (file_exists("inc/inc.$page.php")) {
                include("inc/inc.$page.php");
            }
            $this->render("tpl.$page.phtml");
        } else {
            $this->render("404");
        }
    }


    // Get wanted template file and include it
    // Show error if template is missing
    public function render($template_file) {

        // if 404 template is wanted, set 404 headers and adjust the template name
        if ($template_file == "404") {
            header('HTTP/1.0 404 Not Found');
            $template_file = "tpl.404.phtml";
        }

        if (file_exists($this->template_dir . $template_file)) {
            include($this->template_dir . $this->header_template);
            include($this->template_dir . $template_file);
            include($this->template_dir . $this->footer_template);
        } else {
            $this->kill("Template file not found ($this->template_dir$template_file)");
        }
    }

    public function addError($errorTitle,$errMsg)
    {
        $this->showError = true;
        $this->errorMsg[] = array($errorTitle,$errMsg);
    }

    public function addNotice($noticeTitle,$noticeMsg)
    {
        $this->showNotice = true;
        $this->noticeMsg[] = array($noticeTitle,$noticeMsg);
    }



    // Basic MySQL query
    // Returns array of fetched items
    public function query($query) {
        try {
            $result = $this->pdo->query($query);
        } catch(PDOException $pdoer) {
            $this->kill("MySQL Query failed.<br>PDO Exception: " . $pdoer->getMessage());
        }

        return $result;
    }


    // Get error-template, show it and stop execution
    // If template is missing, show less informative error
    public function kill($error,$include_adtns = true) {
    	if (file_exists($this->template_dir . $this->error_template)) {
            if ($include_adtns) include($this->template_dir . $this->header_template);
            include($this->template_dir . $this->error_template);
            if ($include_adtns) include($this->template_dir . $this->footer_template);
            exit();
        } else {
            throw new Exception('Fatal error happened. Error template, which would give more informative error,'.
            	'seems to be missing from path: ' . $this->template_dir . $this->error_template);
        }
    }


    public function debug($info) {
        $this->debug_info = $info;
    	$this->render($this->debug_template);
    }

    public function __set($key, $val) {
        $this->vars[$key] = $val;
    }
    public function __get($key) {
        return $this->vars[$key];
    }


} // UI-class



?>