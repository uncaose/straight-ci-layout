<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use MatthiasMullie\Minify;

/**
 * @author	: uncaose@gmail.com
 * @url 	: https://github.com/uncaose/straight-ci-layout
 */
class Asset extends CI_Controller
{
	public $config = [];
	public $isCache = FALSE;

	public function __construct() {
		parent::__construct();
		$this->config->load('straight', TRUE, FALSE);
		$this->load->driver('straight');
		$this->config = $this->config->item('straight');
		$this->isCache = $this->load->driver('cache', $this->config['adapter'] );
	}

	public function js()
	{
		$file = VIEWPATH.join('/', func_get_args());

		$this->straight->layout->header( $file );
		echo $this->_js( $file );
	}
	
	public function css()
	{
		$file = VIEWPATH.join('/', func_get_args());

		$this->straight->layout->header( $file );
		echo $this->_css( $file );
	}

	public function combine( $file = '' )
	{
		$key = substr($file, 0, strrpos($file, '.'));
		if( empty($key) 
		|| ! $this->config['asset_combine']
		|| ! $this->isCache
		|| ! $cache = $this->cache->get($key) )
		{
			show_404();
		}

		$this->straight->layout->header( $file );

		if( is_array($cache) ) $cache = $cache[0];

		$ext = pathinfo( $file, PATHINFO_EXTENSION );
		if( $content = $this->cache->get( $file ) )
		{
			if( isset($content['minify']) && $content['minify'] == $this->config['asset_minify_'.$ext] )
			{
				echo $content['body'];
				exit;
			}
		}

		$content = '';
		$cache = @json_decode($cache, TRUE);
		foreach( $cache AS $h => $f )
		{
			$f = VIEWPATH.$f;
			switch( $ext ){
				case( 'js' ):
					$content .= $this->_js( $f );
				break;
				case( 'css' ):
					$content .= $this->_css( $f );
				break;
				default:
					show_404();
				break;
			}
		}

		$this->cache->save($file, ['minify'=>$this->config['asset_minify_'.$ext], 'body'=>$content], $this->config['ttl'] );
		echo $content;
	}

	private function _js( $file = '' )
	{
		if( $this->config['asset_minify_js'] === TRUE && class_exists('MatthiasMullie\\Minify\\JS') )
		{
			$minifier = new Minify\JS( $file );
			return $minifier->minify();
		}else{
			return $this->straight->layout->asset( $file );
		}
	}

	private function _css( $file = '' )
	{
		if( $this->config['asset_minify_css'] === TRUE && class_exists('MatthiasMullie\\Minify\\CSS') )
		{
			$minifier = new Minify\CSS( $file );
			return $minifier->minify();
		}else{
			return $this->straight->layout->asset( $file );
		}
	}
}

/**
 * End of File application/controllers/Asset.php
 */
