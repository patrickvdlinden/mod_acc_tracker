<?php
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Http\HttpFactory;

class ModACCHelper {

  protected Joomla\CMS\Cache\CacheController $cache;

  protected string $cacheId;

  private Joomla\Registry\Registry $params;

  public function __construct($params) {
    $this->cache = JFactory::getCache('mod_acc_tracker', '');
    $this->params = $params;
    $this->cacheId = 'mod_acc_tracker_' . md5($this->params->get('server_path', 'randomstring'));
  }

  public function getData(): string {
    if ($this->cache->contains($this->cacheId)) {
      return $this->cache->get($this->cacheId);
    }
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
      $this->cache->store($resultsTable, $this->cacheId);
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