<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['modules'] = ['layout'];
$config['asset_controller'] = 'asset';
$config['asset_hashkey'] = 'md5';
$config['asset_nocache_uri'] = TRUE;   // TRUE : /asset/css/style.css?_=abc...1234, FALSE : /asset/cas/style.css
$config['asset_combine'] = TRUE;    // TRUE|FALSE css, js combine, TRUE: use cache if possible or query
$config['asset_minify_js'] = TRUE;  // require composer minify lib
$config['asset_minify_css'] = TRUE; // require composer minify lib

$config['adapter'] =  ['adapter' => 'dummy', 'backup' => 'file'];  // CI cache lib, default dummy
$config['ttl'] = 2592000;	// 30 day

$config['view_skin'] = '_skin';
$config['view_layout'] = '_layout';
$config['view_minify'] = TRUE;

if( CI_VERSION < "3" )
{
    $config['modules'] = ['straight_layout'];
    $config['asset_controller'] = 'asset'; // window... required rename
}

/**
 * End of File config/straight.php
 */