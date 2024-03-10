<?php
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Http\HttpFactory;

class ModACCTrackerHelper {

  private Joomla\Registry\Registry $params;

  public function __construct($params) {
    $this->params = $params;
  }

  public function getData(): string {
    $http = HttpFactory::getHttp();
    if ($this->params->get('acc_tracker_request_path')) {
      $response = $http->get($this->params->get('acc_tracker_request_path'));
    }
    else {
      return '<p>Incorrerct server path configuration.</p>';
    }
    if ($response->code !== 200) {
      return '<p style="color: red;">Failed to retrieve the results.</p>';
    }
    else {
      $resultsTable = stripResultsTable($response->body);
      return $resultsTable;
    }
  }

  public function stripResultsTable($responseText) {
    // We only need the actual <table> and not the HTML around it.
    $startIndexOfResults = stripos($responseText, '<table');
    $endIndexOfResults = stripos($responseText, '</table>');

    if ($startIndexOfResults === false || $endIndexOfResults === false) {
      return '<p>No results available.</p>';
    }
    
    $endIndexOfResults += strlen('</table>');

    $resultsTable = substr($responseText, $startIndexOfResults, $endIndexOfResults - $startIndexOfResults);

    // Find the table class and remove it as we don't want some of the styles.
    $classStartIndex = stripos($resultsTable, "class=\"");
    $classSubstrLength = stripos(substr($resultsTable, $classStartIndex + strlen("class=\"")), "\"");

    $resultsTable = substr($resultsTable, 0, $classStartIndex) . substr($resultsTable, $classStartIndex + $classSubstrLength + 1);

    return $resultsTable;
  }
}