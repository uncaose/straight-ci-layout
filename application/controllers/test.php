<?php

use MatthiasMullie\Minify;

Class Test Extends CI_Controller
{
    public $cofnig = [];
	public $isCache = FALSE;

    public function __construct() 
    {
		parent::__construct();
		$this->config->load('straight', TRUE, FALSE);
		$this->config = $this->config->config['straight'];
        // $this->load->driver('cache', $this->config['adapter'] );
		
		// $this->isCache = $this->cache->{$this->config['adapter']['adapter']}->is_supported()
		// 		|| $this->cache->{$this->config['adapter']['backup']}->is_supported();
    }
    
	public function js()
	{
		$file = VIEWPATH.join('/', func_get_args());

		// $this->load->driver('straight');
		// $this->header( $file );
        echo $this->_js( $file );
    }
    
	private function _js( $file = '' )
	{
        $rs = '';
		// if( $this->config['asset_minify_js'] === TRUE && class_exists('MatthiasMullie\\Minify\\JS') )
		// {
		// 	$minifier = new Minify\JS( $file );
		// 	$rs = $minifier->minify();
		// }else{
			$rs = $this->asset( $file );
        // }
        return (ENVIRONMENT!=='production'?'/*'.str_replace(VIEWPATH,'', $file).'*/':'').$rs;
    }
    
    private function asset( $file='' )
	{
		// if( empty($file) || ! file_exists($file) )    // existst file
		// {
		// 	header('HTTP/1.0 404 Not Found');
		// 	exit;
		// }

		// $this->load->helper('file');
		// $this->output->set_content_type( get_mime_by_extension($file) );
		ob_start();
		readfile( $file );
		$content = ob_get_clean();

		return $content;
	}
}