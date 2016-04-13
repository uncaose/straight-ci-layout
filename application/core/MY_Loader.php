<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * @author	: uncaose@gmail.com
 * @url 	: https://github.com/uncaose/straight-ci-layout
 */
class MY_Loader extends CI_Loader
{
	public $_ci_view_path = '';
	public $_views = Array();		// view lists
	public $_skin = '_skin';		// skin
	public $_layout = '_layout';	// layout
	public function __construct()
	{
		parent::__construct();
	}
	// Extend
	public function view( $view, $vars = array(), $return = FALSE )
	{
		$this->_views[] = substr($view, strpos($view, '/')===0?1:0); // 뷰 로드 저장
		return parent::view($view, $vars, $return);
	}
	
	public function getView( $unique=FALSE )
	{
		if( $unique === TRUE )
		{
			return array_unique( $this->_views );
		}
		return $this->_views;
	}
	public function skin( $name='' )
	{
		$this->_skin = $name;
		return $this;
	}
	
	public function getSkin()
	{
		return $this->_skin;
	}
	public function layout( $name='' )
	{
		$this->_layout = $name;
		return $this;
	}
	
	public function getLayout()
	{
		return $this->_layout;
	}
}

/**
 * End Of File application/core/MY_Loader.php
 */