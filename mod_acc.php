<?php
/**
 * Mod acc! Module Entry Point
 */

// No direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$helper = new ModACCHelper;
$servers = $helper->init();

require JModuleHelper::getLayoutPath('mod_acc');