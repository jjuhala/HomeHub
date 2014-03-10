<?php

// UI-class handles reading and printing the templates
class UI {
	protected $template_dir = 'templates/';
    protected $header_template = '_head.phtml';
    protected $footer_template = '_foot.phtml';
	protected $error_template = 'error.phtml';
	protected $debug_template = 'debug.phtml';
	protected $vars = array();
    protected $conf = array();

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
                require("inc/inc.$page.php");
            }
            $this->render("tpl.$page.phtml");
        } else {
            $this->render("404");
        }
    }


    // Get wanted template file and include it
    // Show error if template is missing
    public function render($template_file) {
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



    // Get error-template, show it and stop execution
    // If template is missing, show less informative error
    public function kill($error,$include_adtns = true) {
    	if (file_exists($this->template_dir . $this->error_template)) {
            if ($include_adtns) include($this->template_dir . $this->header_template);
            include($this->template_dir . $this->error_template);
            if ($include_adtns) include($this->template_dir . $this->footer_template);
            exit();
        } else {
            throw new Exception('Fatal error happened. Error template, which would give more further information,'.
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