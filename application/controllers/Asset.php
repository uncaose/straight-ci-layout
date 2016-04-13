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
		
		$this->load->helper('file');
		$this->output->set_content_type( get_mime_by_extension($file) );
		readfile( $file );
	}
}

/**
 * End of File application/controllers/Asset.php
 */