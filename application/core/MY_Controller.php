<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author	: uncaose@gmail.com
 * @url 	: https://github.com/uncaose/straight-ci-layout
 */
class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if( ! defined('EXT') ) define('EXT', '.php');	// ci2 EXT redefine
		if( ! defined('VIEWPATH')  ) define('VIEWPATH', $this->load->_ci_view_path); // ci3 style
		$this->load->skin('_skin')->layout('_layout');	// default skin, layout
	}

	/**
	 * CI _output
	 */
	public function _output( $output )
	{
		$output = $this->_straight_skin( $output );
		$output = $this->_straight_layout( $output );
		$output = $this->_straight_view( $output );
		
		echo $output;
	}
	
	// skin
	private function _straight_skin( $output='' )
	{
		if( ! empty($this->load->_skin) && file_exists(VIEWPATH.$this->load->_skin.EXT) )
		{
			$output = $this->load->view($this->load->_skin, Array('skin'=>$output), TRUE);
		}
		return $output;
	}
	
	// layout
	private function _straight_layout( $output='' )
	{
		if( ! empty($this->load->_layout) && file_exists(VIEWPATH.$this->load->_layout.EXT) )
		{
			$output = $this->load->view($this->load->_layout, Array('layout'=>$output), TRUE);
		}
		else
		{
			$output = '<!DOCTYPE html><html lang="en"><head></head><body>'.$output.'</body></html>';
		}
		return $output;
	}
	
	// view
	private function _straight_view( $output='' )
	{
		if( isset($this->load->_views) && sizeof($this->load->getView(TRUE)) )
		{
			foreach( $this->load->getView(TRUE) AS $_view)
			{
				$js = VIEWPATH.$_view.'.js';
				$css = VIEWPATH.$_view.'.css';

				// view js
				if( file_exists( $js ) )
				{
					$js = str_replace(VIEWPATH, 'asset/', $js).'?_='.hash('md5', $js );
					$output = str_replace('</body>', "<script type='text/javascript' src='/{$js}'></script>\n</body>", $output );
				}

				// view css
				if( file_exists( $css ) )
				{
					$css = str_replace(VIEWPATH, 'asset/', $css).'?_='.hash('md5', $css );
					$output = str_replace('</head>', "<link rel='stylesheet' type='text/css' href='/{$css}' />\n</head>", $output );
				}
			}
		}
		return $output;
	}
}


/**
 * End of File application/library/MY_Controller.php
 */
