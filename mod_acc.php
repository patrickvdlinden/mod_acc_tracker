<?php
/**
 * Mod acc! Module Entry Point
 */

// No direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$document = JFactory::getDocument();
$document->addStyleSheet('modules/mod_acc/css/mod_acc.css');

$helper = new ModACCHelper;
$servers = $helper->init();

require JModuleHelper::getLayoutPath('mod_acc');