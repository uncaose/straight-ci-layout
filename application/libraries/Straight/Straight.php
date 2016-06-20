<?php

/**
 * Created by PhpStorm.
 * User: uncaose
 * Date: 2016. 6. 15.
 * Time: 23:13
 */
class Straight extends CI_Driver_Library
{
	public $valid_drivers;
	public $CI;
	public $view_skin = '_skin';
	
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->config->load('straight', TRUE);
		$this->valid_drivers = $this->CI->config->item('modules', 'straight');

		$this->_init();
	}

	public function _init()
	{
		if( ! defined('EXT') ) define('EXT', '.php');	// ci2 EXT redefine
		if( ! defined('VIEWPATH')  ) define('VIEWPATH', $this->CI->load->_ci_view_path); // ci3 style
		$this->CI->load->setSkin('_skin')->setLayout('_layout');	// default skin, layout
	}
}