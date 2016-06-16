<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author	: uncaose@gmail.com
 * @url 	: https://github.com/uncaose/straight-ci-layout
 */
//require_once()
class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->driver('straight');
	}

	/**
	 * CI _output
	 */
	public function _output( $output='' )
	{
		$output = $this->straight->layout->skin( $output );
		$output = $this->straight->layout->layout( $output );
		$output = $this->straight->layout->view2asset( $output );

		echo $output;
	}
}


/**
 * End of File application/library/MY_Controller.php
 */
