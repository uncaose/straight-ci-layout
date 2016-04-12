<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Asset extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function _remap()
	{
		// path
		$file = '.'.str_replace( segment(1, 'both'), APPPATH.'views', uri_string() );
		$ext = pathinfo($file, PATHINFO_EXTENSION);

		if( $ext == 'js' && file_exists($file) )    // CSS, 존재유무
		{
			$this->output->set_header('Content-Type: application/javascript');
			readfile( $file );
		}
		else if( $ext == 'css' && file_exists($file) )   // CSS, 존재유무
		{
			$this->output->set_header('Content-type: text/css; charset: UTF-8');
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