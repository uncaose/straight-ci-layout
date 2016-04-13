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
		// path
		//$file = str_replace( $this->uri->segment(1, 'before'), VIEWPATH, $this->uri->uri_string() );
		$file = VIEWPATH.$method.(sizeof($params)?'/'.join('/', $params):'');
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		
		if( file_exists($file) )    // CSS, 존재유무
		{
			switch( $ext ){
				case( 'js' ):
					$this->output->set_header('Content-Type: application/javascript');
					break;
				case( 'css' ):
					$this->output->set_header('Content-type: text/css; charset: UTF-8');
					break;
			}
			readfile( $file );
		}
		else
		{
			show_404();
		}
	}
}

/**
 * End of File application/controllers/Asset.php
 */