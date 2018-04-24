<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use MatthiasMullie\Minify;

/**
 * @author	: uncaose@gmail.com
 * @url 	: https://github.com/uncaose/straight-ci-layout
 */
class Asset extends CI_Controller
{
	public $cofnig = [];
	public $isCache = FALSE;

	public function __construct() {
		parent::__construct();
		$this->config->load('straight', TRUE, FALSE);
		$this->load->driver('straight');
		$this->config = $this->config->item('straight');
		$this->load->driver('cache', $this->config['adapter'] );
		
		$this->isCache = $this->cache->{$this->config['adapter']['adapter']}->is_supported()
				|| $this->cache->{$this->config['adapter']['backup']}->is_supported();
	}

	public function js()
	{
		$file = VIEWPATH.join('/', func_get_args());

		$this->load->driver('straight');
		$this->straight->layout->header( $file );

		$this->straight->layout->header( $file );
		echo $this->_js( $file );
	}
	
	public function css()
	{
		$file = VIEWPATH.join('/', func_get_args());

		$this->load->driver('straight');
		$this->straight->layout->header( $file );

		$this->straight->layout->header( $file );
		echo $this->_css( $file );
	}

	public function combine( $file = '' )
	{
        $l = $this->input->get('l');    // query list
        $key = substr($file, 0, strrpos($file, '.'));
        $ext = pathinfo( $file, PATHINFO_EXTENSION );

		if( empty($key) )
		{
			show_404();
        }

		$this->straight->layout->header( $file );

		if( $this->isCache && $content = $this->cache->get( $file ) ) // 캐시
		{
			if( isset($content['minify']) && $content['minify'] == $this->config['asset_minify_'.$ext] )
			{
				echo $content['body'];
				exit;
            }

        }
        else if( $this->isCache && $cache = $this->cache->get($key) )
        {
            if( is_array($cache) ) $cache = $cache[0];
            $cache = @json_decode($cache, TRUE);
        }
        else if( strlen($l) ) 
        {
            $cache = explode(',', $l );
            if( ! sizeof($cache) )
            {
                show_404();
            }
        }

		$content = '';
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

        if( $this->isCache )
        {
            $this->cache->save($file, ['minify'=>$this->config['asset_minify_'.$ext], 'body'=>$content], $this->config['ttl'] );
        }
        echo $content;
        exit;
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
 * End of File application/controllers/asset.php
 */
