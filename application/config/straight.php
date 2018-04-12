<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['modules'] = CI_VERSION>="3.0.0"?array('layout'):array('straight_layout');

$config['asset_controller'] = 'asset';
$config['asset_hashkey'] = 'md5';
$config['asset_nocache_uri'] = TRUE;   // TRUE : /asset/css/style.css?_=abc...1234, FALSE : /asset/cas/style.css
$config['asset_combine'] = [
	'combine' => TRUE,
	'adapter' => ['adapter' => 'apc', 'backup' => 'file'],
	'ttl' => 2592000	// 30 day
];

$config['view_skin'] = '_skin';
$config['view_layout'] = '_layout';

/**
 * End of File config/straight.php
 */