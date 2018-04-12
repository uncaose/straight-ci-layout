<?php

/**
 * Created by PhpStorm.
 * User: uncaose
 * Date: 2016. 6. 15.
 * Time: 23:13
 */
class Straight extends CI_Driver_Library
{
	public $config = [];

	public $valid_drivers;
	public $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->config->load('straight', TRUE);
		$this->valid_drivers = $this->CI->config->item('modules', 'straight');

		$this->_init();
	}

	public function _init()
	{
		// set config
		$this->CI->config->load( 'straight', TRUE, TRUE ); // get Straight Config
		$this->config =  $this->CI->config->config['straight'];

		if( ! defined('EXT') ) define('EXT', '.php');	// ci2 EXT redefine
		if( ! defined('VIEWPATH')  ) define('VIEWPATH', $this->CI->load->_ci_view_path); // ci3 style

		$this->CI->load->_skin = $this->config['view_skin'];         // default skin
		$this->CI->load->_layout = $this->config['view_layout'];    // default layout
	}
}