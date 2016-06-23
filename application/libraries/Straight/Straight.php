<?php

/**
 * Created by PhpStorm.
 * User: uncaose
 * Date: 2016. 6. 15.
 * Time: 23:13
 */
class Straight extends CI_Driver_Library
{
	public $config = Array(
		'asset_controller' => 'asset',
		'asset_hashkey' => 'md5',
		'asset_nocache_uri' => FALSE,
		'view_skin' => '_skin',
		'view_layout' => '_layout',
	);

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
		$this->CI->config->load( 'straight', FALSE, TRUE ); // get Straight Config
		$this->config = array_merge( $this->config, $this->CI->config->config['straight'] );

		if( ! defined('EXT') ) define('EXT', '.php');	// ci2 EXT redefine
		if( ! defined('VIEWPATH')  ) define('VIEWPATH', $this->CI->load->_ci_view_path); // ci3 style

		$this->CI->load
			->setSkin( $this->config['view_skin'] )         // default skin
			->setLayout( $this->config['view_layout'] );    // default layout
	}
}