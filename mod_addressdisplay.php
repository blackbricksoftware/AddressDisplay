<?php defined( '_JEXEC' ) or die( 'Restricted access' );

require_once __DIR__ . '/helper.php';

$helper = new ModAddressDisplayHelper($params);
$helper->display();


