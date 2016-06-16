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

/**
 * End of File config/Straight.php
 */