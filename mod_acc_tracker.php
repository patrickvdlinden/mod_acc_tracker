<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

/**
 * Mod acc tracker! Module Entry Point
 */

// No direct access
defined('_JEXEC') or die;

$document = JFactory::getDocument();
$document->addStyleSheet('modules/mod_acc_tracker/css/mod_acc_tracker.css');
$document->addScriptDeclaration('const ACC_TRACKER_REQUEST_PATH = "' . $params->get('acc_tracker_request_path') . '";');
$document->addScript('modules/mod_acc_tracker/js/mod_acc_tracker.js');

require JModuleHelper::getLayoutPath('mod_acc_tracker');