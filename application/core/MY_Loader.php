<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * @author	: uncaose@gmail.com
 * @url 	: https://github.com/uncaose/straight-ci-layout
 */
class MY_Loader extends CI_Loader
{
	public $_ci_view_path = '';     // ci3
	public $_views = Array();	// view lists
	public $_skin = '_skin';	// skin
	public $_layout = '_layout';	// layout
	
	public function __construct() {
		parent::__construct();
		if( empty($this->_ci_view_path) && ! empty($this->_ci_view_paths) ) { // ci2 path setting
			list($this->_ci_view_path) = array_keys($this->_ci_view_paths);
		}

		if( CI_VERSION < "3.0.0"  )	// ci2 에서 composer_autoload apply
		{
			$this->_composer_autoload();	
		}
	}

	private function _composer_autoload()
	{
		if ($composer_autoload = config_item('composer_autoload'))
		{
			if ($composer_autoload === TRUE)
			{
				file_exists(APPPATH.'vendor/autoload.php')
					? require_once(APPPATH.'vendor/autoload.php')
					: log_message('error', '$config[\'composer_autoload\'] is set to TRUE but '.APPPATH.'vendor/autoload.php was not found.');
			}
			elseif (file_exists($composer_autoload))
			{
				require_once($composer_autoload);
			}
			else
			{
				log_message('error', 'Could not find the specified $config[\'composer_autoload\'] path: '.$composer_autoload);
			}
		}
	}
	
	// Extend
	public function view( $view, $vars = array(), $return = FALSE, $unshift = FALSE ) {
		if( $unshift ) {
			array_unshift( $this->_views, substr($view, strpos($view, '/')===0?1:0) ); // store viewname
		} else {
			array_push( $this->_views, substr($view, strpos($view, '/')===0?1:0) ); // store viewname
		}
		return parent::view($view, $vars, $return);
	}
	
	public function getView( $unique=FALSE ) {
		if( $unique === TRUE ) {
			return array_unique( $this->_views );
		}
		return $this->_views;
	}
	
	public function setSkin( $name='' ) {
		$this->_skin = $name;
		return $this;
	}
	
	public function getSkin() {
		return $this->_skin;
	}

	public function setLayout( $name='' ) {
		$this->_layout = $name;
		return $this;
	}
	
	public function getLayout() {
		return $this->_layout;
	}
}

/**
 * End Of File application/core/MY_Loader.php
 */
