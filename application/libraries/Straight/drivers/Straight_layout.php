<?php

/**
 * Created by PhpStorm.
 * User: uncaose
 * Date: 2016. 6. 15.
 * Time: 23:13
 */
class Straight_layout Extends CI_Driver
{
	public function asset( $file='' )
	{
		if( empty($file) || ! file_exists($file) )    // existst file
		{
			header('HTTP/1.0 404 Not Found');
			exit;
		}

		$ext = pathinfo( $file, PATHINFO_EXTENSION );
		switch( TRUE )
		{
			case( $ext === 'js' ):
				header('Content-Type: text/javascript');
			case( $ext === 'css' ):
				header('Content-Type: text/css');
				$this->webCache( $file );
				break;
			default:
				header('HTTP/1.0 404 Not Found');
				exit;
				break;
		}

		$this->CI->load->helper('file');
		$this->CI->output->set_content_type( get_mime_by_extension($file) );
		readfile( $file );
	}

	// 30758400 : 1 year
	public function webCache( $file='', $time=30758400 )
	{
		if( empty($file) || ! file_exists($file) )    // existst file
		{
			header('HTTP/1.0 404 Not Found');
			exit;
		}

		$lastModifTime = filemtime($file);
		$Etag = hash_file('sha256', $file);

		// last modify
		if( ! empty($lastModifTime) )
		{
			header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModifTime)." GMT");
		}

		// Etag
		if( ! empty($Etag) )
		{
			header("Etag: {$Etag}");
		}

		// checkt last time & Etag
		if ( ( isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModifTime  )
			|| ( ! empty($Etag) && isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == $Etag) )
		{
			// Not Modify
			header("HTTP/1.1 304 Not Modified");
			exit;
		}

		// set Cache Header
		header('Vary: Accept-Encoding');
		header("Expires: ".gmdate("D, d M Y H:i:s", time()+$time)." GMT");
		header('Pragma: cache');
		header('Cache-Control: public');
		header("Cache-Control: max-age=".$time);
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