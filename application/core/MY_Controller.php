<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author	: uncaose@gmail.com
 * @url 	: https://github.com/uncaose/straight-ci-layout
 */
class MY_Controller extends CI_Controller
{
	public function __construct() {
		parent::__construct();
		$this->load->driver('straight');
	}

	/**
	 * CI _output
	 */
	public function _output( $output='' ) {
		echo $this->straight->layout->output( $output );
	}
}


/**
 * End of File application/library/MY_Controller.php
 */
