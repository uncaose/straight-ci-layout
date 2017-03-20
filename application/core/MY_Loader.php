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
			$this->_ci_view_path = array_keys($this->_ci_view_paths)[0];
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
