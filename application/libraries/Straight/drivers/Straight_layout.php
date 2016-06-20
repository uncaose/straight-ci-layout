<?php

/**
 * Created by PhpStorm.
 * User: uncaose
 * Date: 2016. 6. 15.
 * Time: 23:13
 */
class Straight_layout Extends CI_Driver
{
	public function asset($method, $params = array())
	{
		$file = VIEWPATH.(sizeof($params)?'/'.join('/', $params):'');

		if( ! file_exists($file) )    // existst file
		{
			show_404();
		}

		if( ! in_array(pathinfo($file, PATHINFO_EXTENSION), Array('js', 'css')) )
		{
			show_404();
		}

		$this->CI->load->helper('file');
		$this->CI->output->set_content_type( get_mime_by_extension($file) );
		readfile( $file );
	}

	private function skin( $output='' )
	{
		if( ! empty($this->CI->load->_skin) && file_exists(VIEWPATH.$this->CI->load->_skin.EXT) )
		{
			$output = $this->CI->load->view($this->CI->load->_skin, Array('skin'=>$output), TRUE);
		}
		return $output;
	}

	private function layout( $output='' )
	{
		if( ! empty($this->CI->load->_layout) && file_exists(VIEWPATH.$this->CI->load->_layout.EXT) )
		{
			$output = $this->CI->load->view($this->CI->load->_layout, Array('layout'=>$output), TRUE);
		}
		else
		{
			$output = '<!DOCTYPE html><html lang="en"><head></head><body>'.$output.'</body></html>';
		}
		return $output;
	}
	
	
	private function view2asset( $output='' )
	{
		if( isset($this->CI->load->_views) && sizeof($this->CI->load->getView(TRUE)) )
		{
			foreach( $this->CI->load->getView(TRUE) AS $_view)
			{
				$js = VIEWPATH.$_view.'.js';
				$css = VIEWPATH.$_view.'.css';
				
				// view js
				if( file_exists( $js ) )
				{
					$js = str_replace(VIEWPATH, $this->CI->config->item('asset_controller', 'straight').'/js/', $js).'?_='.hash('md5', $js );
					$output = str_replace('</body>', "<script type='text/javascript' src='/{$js}'></script>\n</body>", $output );
				}
				
				// view css
				if( file_exists( $css ) )
				{
					$css = str_replace(VIEWPATH, $this->CI->config->item('asset_controller', 'straight').'/css/', $css).'?_='.hash('md5', $css );
					$output = str_replace('</head>', "<link rel='stylesheet' type='text/css' href='/{$css}' />\n</head>", $output );
				}
			}
		}
		return $output;
	}

	public function output( $output='' )
	{
		$output = $this->skin( $output );
		$output = $this->layout( $output );
		$output = $this->view2asset( $output );

		return $output;
	}
}


/**
 * End of File drivers/Straight_layout.php
 */