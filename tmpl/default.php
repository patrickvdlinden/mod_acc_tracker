<?php
// No direct access
defined('_JEXEC') or die; ?>
<div id="mod_acc">
	<?php if ($servers) :
		foreach ($servers as $server) : ?>
        <h2><?php echo $server['serverName'] ?></h2>
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Driver</th>
                <th>Best lap</th>
                <th>Gap to leader</th>
                <th>Gap interval</th>
                <th></th>
                <th>Best S1</th>
                <th>Best S2</th>
                <th>Best S3</th>
                <th>Optimal</th>
                <th>Opt. diff with Best</th>
                <th>Total laps</th>
            </tr>
            </thead>
            <tbody>
						<?php foreach ($server['results'] as $key => $bestResult) : ?>
                <tr>
                    <td><?php echo str_pad($key + 1, '2', '0', STR_PAD_LEFT) ?></td>

                    <td>
											<?php echo implode(' ', [
												$bestResult->currentDriver->firstName ?? '',
												$bestResult->currentDriver->lastName ?? '',
											]); ?>
                    </td>
                    <td>
											<?php echo $helper->milisecondsToTimeStap($bestResult->timing->bestLap); ?>
                    </td>
                    <td>
											<?php if ($key > 0)
											{
												echo $helper->milisecondsToTimeStap($server['results'][$key]->timing->bestLap -
													$server['results'][0]->timing->bestLap, FALSE);
											} ?>
                    </td>
                    <td>
											<?php
											if ($key > 0)
											{
												echo $helper->milisecondsToTimeStap($server['results'][$key]->timing->bestLap - $server['results'][$key -
													1]->timing->bestLap, FALSE);
											}
											?>
                    </td>
                    <td></td>
                    <td>
											<?php echo $helper->milisecondsToTimeStap($bestResult->timing->bestSplits[0]); ?>
                    </td>
                    <td>
											<?php echo $helper->milisecondsToTimeStap($bestResult->timing->bestSplits[1]); ?>
                    </td>
                    <td>
											<?php echo $helper->milisecondsToTimeStap($bestResult->timing->bestSplits[2]); ?>
                    </td>
                    <td>
											<?php
											$optimal = $bestResult->timing->bestSplits[0] + $bestResult->timing->bestSplits[1] +
												$bestResult->timing->bestSplits[2];
											echo $helper->milisecondsToTimeStap($optimal);
											?>
                    </td>
                    <td>
											<?php echo $helper->milisecondsToTimeStap($bestResult->timing->bestLap - $optimal, FALSE); ?>
                    </td>
                    <td>
											<?php echo $server['lapCounts'][$bestResult->currentDriver->playerId] ?>
                    </td>
                </tr>
						<?php endforeach; ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <th>Avg Gap:</th>
                <td><?php echo $helper->milisecondsToTimeStap($server['avgGap'] ?? 0, FALSE) ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <th colspan="2">Total laps completed:</th>
                <td><?php echo $server['totalLapCount']; ?></td>
            </tr>
            </tbody>
            <caption>Data of completed sessions, will be updated in <?php echo $server['next_update'] ?></caption>
        </table>
		<?php endforeach; else : ?>
      <p>Something went wrong, please contact an administrator.</p>
	<?php endif; ?>
</div>