<?php
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Http\HttpFactory;

class ModACCHelper {

	protected Joomla\CMS\Cache\CacheController $cache;

	protected $cacheId = 'mod_acc';

	public function __construct() {
		$this->cache = JFactory::getCache('mod_acc', '');
	}

	public function getData() {
		if ($this->cache->contains($this->cacheId))
		{
			return $this->cache->get($this->cacheId);
		}
		$http     = HttpFactory::getHttp();
		$response = $http->post('http://20.23.242.54/', ['AUTH_KEY' => 'gasdg51sdr6g51ser61g6sdr1g']);
		if ($response->code !== 200)
		{
			return [];
		}
		else
		{
			$data['response']  = json_decode($response->body);
			$data['timestamp'] = date('d-m-y H:i:s');
			$this->cache->store($data, $this->cacheId);

			return $data;
		}
	}

	protected static function sortByTime($a, $b): int {
		return strcmp($a->timing->bestLap, $b->timing->bestLap);
	}

	public function milisecondsToTimeStap($time, $minutes = TRUE) {
		$string = '';
		if ($minutes)
		{
			$string .= floor($time / 60000) . ':';
		}
		$string .= str_pad(floor(($time % 60000) / 1000), 2, '0', STR_PAD_LEFT) . ':' . str_pad(floor($time % 1000), 3, '0', STR_PAD_LEFT);;

		return $string;
	}

	public function init() {
		$data    = $this->getData();
		$results = [];

		if (isset($data['response']))
		{
			foreach ($data['response'] as $key => $server)
			{
				$bestResults = [];
				$lapCounts   = [];
				foreach ($server as $result)
				{
					if (isset($result->sessionResult) && $lines = $result->sessionResult->leaderBoardLines)
					{
						foreach ($lines as $line)
						{
							if (isset($bestResults[$line->currentDriver->playerId]))
							{

								if ($line->timing->bestLap < $bestResults[$line->currentDriver->playerId]->timing->bestLap)
								{
									$bestResults[$line->currentDriver->playerId] = $line;
								}

							}
							else
							{
								$bestResults[$line->currentDriver->playerId] = $line;
							}

							if (isset($lapCounts[$line->currentDriver->playerId]))
							{
								$lapCounts[$line->currentDriver->playerId] += $line->timing->lapCount;
							}
							else
							{
								$lapCounts[$line->currentDriver->playerId] = $line->timing->lapCount;
							}
						}
					}
				}

				usort($bestResults, 'ModACCHelper::sortByTime');

				$gap = [];
				foreach ($bestResults as $currentKey => $bestResult)
				{
					if ($currentKey > 0)
					{
						$gap[] = $bestResults[$currentKey]->timing->bestLap - $bestResults[$currentKey - 1]->timing->bestLap;
					}
				}
				$results[$key]['avgGap']      = array_sum($gap) / count($gap);
				$results[$key]['serverName']  = $server[0]->serverName;
				$results[$key]['next_update'] = date("i:s", strtotime($data['timestamp']) - strtotime('+1 hours 1 second'));
				$results[$key]['results']     = $bestResults;
				$results[$key]['lapCounts']   = $lapCounts;
			}

			return $results;
		}
	}

}