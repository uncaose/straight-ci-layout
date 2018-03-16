<?php


if( CI_VERSION >= "3.0.0" )
{
	$config['modules'] = array('layout');
}
else
{
	$config['modules'] = array('straight_layout');
}

$config['asset_controller'] = 'asset';
$config['asset_hashkey'] = 'md5';
$config['asset_nocache_uri'] = FALSE;   // TRUE : /asset/css/style.css?_=abc...1234, FALSE : /asset/cas/style.css

$config['view_skin'] = '_skin';
$config['view_layout'] = '_layout';

/**
 * End of File config/straight.php
 */