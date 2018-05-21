<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author	: uncaose@gmail.com
 * @url 	: https://github.com/uncaose/straight-ci-layout
 */
class MY_Output extends CI_Output
{
	public function __construct() {
        if( CI_VERSION >= "3"  )
        {
            parent::__construct();
        }
        else
        {
            $this->___construct();
        }
    }
    
    // CI2 $mimes error solution
    private function ___construct()
    {
        $this->_zlib_oc = @ini_get('zlib.output_compression');

        // Get mime types for later
        if (defined('ENVIRONMENT') AND file_exists(APPPATH.'config/'.ENVIRONMENT.'/mimes.php'))
        {
            $mimes = include APPPATH.'config/'.ENVIRONMENT.'/mimes.php';
        }
        else
        {
            $mimes = include APPPATH.'config/mimes.php';
        }


        $this->mime_types = $mimes;

        log_message('debug', "Output Class Initialized");
    }
}


/**
 * End of File application/library/MY_Output.php
 */
