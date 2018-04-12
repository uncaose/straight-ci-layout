<?php

/**
 * Created by PhpStorm.
 * User: uncaose
 * Date: 2016. 6. 15.
 * Time: 23:13
 */
class Straight_layout Extends CI_Driver
{
	public function header( $file = '' )
	{
		$ext = pathinfo( $file, PATHINFO_EXTENSION );
		switch( TRUE )
		{
			case( $ext === 'js' ):
				header('Content-Type: text/javascript');
				break;
			case( $ext === 'css' ):
				header('Content-Type: text/css');
				break;
			default:
				header('HTTP/1.0 404 Not Found');
				exit;
			break;
		}
	}

	public function asset( $file='' )
	{
		if( empty($file) || ! file_exists($file) )    // existst file
		{
			header('HTTP/1.0 404 Not Found');
			exit;
		}

		$this->CI->load->helper('file');
		$this->CI->output->set_content_type( get_mime_by_extension($file) );
		ob_start();
		readfile( $file );
		$content = ob_get_clean();

		return $content;
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
		$Etag = hash_file($this->config['asset_hashkey'], $file);

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
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModifTime)." GMT");
		header("Etag: {$Etag}");
		header('Pragma: cache');
		header('Cache-Control: public');
		header("Cache-Control: max-age=".$time);
	}

	private function skin( $output='' )
	{
		if( $this->CI->load->_skin != $this->CI->config->item('view_skin') )
		{
			$path = substr($this->CI->load->_views[0], 0, strrpos($this->CI->load->_views[0], '/'));
			$skin = $path.'/'.str_replace(EXT, '', $this->CI->load->_skin);

			if( file_exists(VIEWPATH.$skin.EXT) )
			{
				$this->CI->load->_skin = $skin;
			}
		}

		if( file_exists(VIEWPATH.$this->CI->load->_skin.EXT) )
		{
			$output = $this->CI->load->view( $this->CI->load->_skin, Array('skin'=>$output), TRUE, TRUE );
		}
		return $output;
	}

	private function layout( $output='' )
	{
		if( $this->CI->load->_layout != $this->CI->config->item('view_layout') )
		{
			$path = substr($this->CI->load->_views[0], 0, strrpos($this->CI->load->_views[0], '/'));
			$layout = $path.'/'.str_replace(EXT, '', $this->CI->load->_layout);
	
			if( file_exists(VIEWPATH.$layout.EXT) )
			{
				$this->CI->load->_layout = $layout;
			}
		}

		if( file_exists(VIEWPATH.$this->CI->load->_layout.EXT) )
		{
			$output = $this->CI->load->view( $this->CI->load->_layout, Array('layout'=>$output), TRUE, TRUE );
		}
		else
		{
			$output = '<!DOCTYPE html><html lang="en"><head></head><body>'.$output.'</body></html>';
		}
		return $output;
	}
	
	
	private function view2asset( $output='' )
	{
		$asset_path = $this->config['asset_controller'];
		$nocache_uri = $this->config['asset_nocache_uri'];
		$views = $this->CI->load->getView(TRUE);

		foreach( $views AS $v )
		{
			$js = VIEWPATH.$v.'.js';
			$css = VIEWPATH.$v.'.css';

			// view js
			if( file_exists( $js ) )
			{
				$js = str_replace(VIEWPATH, $asset_path.'/js/', $js).($nocache_uri?'?_='.hash($this->config['asset_hashkey'], $js ):'');
				$output = str_replace('</body>', "\n\t<script type='text/javascript' src='/{$js}'></script>\n</body>", $output );
			}

			// view css
			if( file_exists( $css ) )
			{
				$css = str_replace(VIEWPATH, $asset_path.'/css/', $css).($nocache_uri?'?_='.hash($this->config['asset_hashkey'], $css ):'');
				$output = str_replace('</head>', "\t<link rel='stylesheet' type='text/css' href='/{$css}' />\n</head>", $output );
			}
		}
		return $output;
	}

	private function view2combineAsset( $output )
	{
		$asset_path = $this->config['asset_controller'];
		$nocache_uri = $this->config['asset_nocache_uri'];
		$views = $this->CI->load->getView(TRUE);

		$_js = [];
		$_css = [];
		foreach( $views AS $v )
		{
			$js = $v.'.js';
			$css = $v.'.css';

			// view js
			if( file_exists( VIEWPATH.$js ) )
			{
				$hash = hash_file($this->config['asset_hashkey'], VIEWPATH.$js);
				$_js[$hash] = $js;
			}

			// view css
			if( file_exists( VIEWPATH.$css ) )
			{
				$hash = hash_file($this->config['asset_hashkey'], VIEWPATH.$css);
				$_css[$hash] = $css;
			}
		}

		if( sizeof($_js) )
		{
			$content = json_encode($_js);
			$hash = hash($this->config['asset_hashkey'], $content);
			if( ! $cache = get_instance()->cache->get($hash) )
			{
				if( ! get_instance()->cache->save($hash, $content, $this->config['asset_combine']['ttl']) )
				{
					return FALSE;
				}
			}

			$output = str_replace('</body>', "\n\t<script type='text/javascript' src='/{$asset_path}/combine/{$hash}.js'></script>\n</body>", $output );
		}

		if( sizeof($_css) )
		{
			$content = json_encode($_css);
			$hash = hash($this->config['asset_hashkey'], $content);
			if( ! $cache = get_instance()->cache->get($hash) )
			{
				if( get_instance()->cache->save($hash, $content, $this->config['asset_combine']['ttl']) )
				{
					return FALSE;
				}
			}

			$output = str_replace('</head>', "\t<link rel='stylesheet' type='text/css' href='/{$asset_path}/combine/{$hash}.css' />\n</head>", $output );
		}

		return $output;
	}

	public function output( $output='' )
	{
		$output = $this->skin( $output );
		$output = $this->layout( $output );
		
		if( $this->config['asset_combine']['combine'] 
			&& get_instance()->load->driver('cache', $this->config['asset_combine']['adapter'] ) )
		{
			$output = $this->view2combineAsset( $output );
		} else {
			$output = $this->view2asset( $output );
		}

		return $output;
	}
}


/**
 * End of File drivers/Straight_layout.php
 */