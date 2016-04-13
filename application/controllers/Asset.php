<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Asset extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if( ! defined('VIEWPATH')  ) defined('VIEWPATH', $this->load->_ci_view_path); // ci3 style
	}

	public function _remap($method, $params = array())
	{
		$file = VIEWPATH.$method.(sizeof($params)?'/'.join('/', $params):'');
		
		if( ! file_exists($file) )    // existst file
		{
			show_404();
		}
		
		switch( pathinfo($file, PATHINFO_EXTENSION) ){
			case( 'js' ):
				$this->output->set_header('Content-Type: application/javascript');
				break;
			case( 'css' ):
				$this->output->set_header('Content-type: text/css; charset: UTF-8');
				break;
		}
		
		readfile( $file );
	}
}

/**
 * End of File application/controllers/Asset.php
 */