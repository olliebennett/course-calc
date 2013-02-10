<?php

// Calculate 

// d($modules,'$modules');
// d($yrweights,'$yrweights');

$calc['total_modules_complete'] = 0;
$calc['total_modules_incomplete'] = 0;

foreach ($modules as $year => $module) {
	//echo "<h3>Year $year</h3>";
	$calc['year'][$year]['modules_complete'] = 0;
	$calc['year'][$year]['modules_incomplete'] = 0;
	$calc['year'][$year]['credits_complete'] = 0;	
	$calc['year'][$year]['credits_incomplete'] = 0;
	
	// Year Weight
	$calc['year'][$year]['weight'] = $yrweights[$year]/100;
	
	foreach ($module as $module_id => $module_details) {
		//echo "<p>Module $module_id</p>";
		// Store module details
		$calc['year'][$year]['module'][$module_id]['name'] = $module_details['name'];
		$calc['year'][$year]['module'][$module_id]['code'] = $module_details['code'];
		$calc['year'][$year]['module'][$module_id]['crds'] = $module_details['crds'];
		// hack to fix errors when (int)0 is treated the same as an empty string.
		if ($module_details['mark'] === '') {
			$calc['year'][$year]['module'][$module_id]['mark'] = '';
		} elseif ($module_details['mark'] === '0') {
			$calc['year'][$year]['module'][$module_id]['mark'] = 0.00001;
		} else {
			$calc['year'][$year]['module'][$module_id]['mark'] = $module_details['mark']/100;
		}
		
		
		// Find the number of complete/incomplete modules/credits for each year
		if ($module_details['mark'] != '') {
			$calc['year'][$year]['modules_complete']++;
			$calc['year'][$year]['credits_complete'] += $module_details['crds'];
		} else {
			$calc['year'][$year]['modules_incomplete']++;
			$calc['year'][$year]['credits_incomplete'] += $module_details['crds'];
		}
	}
}

// Calculate overall weighted credits
$calc['completed_credit_weighting'] = 0;
$calc['incompleted_credit_weighting'] = 0;
$calc['completed_mark_weighting'] = 0;
foreach ($calc['year'] as $year => $module) {
	//echo "<h3>Year $year</h3>";
	// Calculate yearly weighted credits
	$calc['year'][$year]['year_completed_credit_weighting'] = 0;
	$calc['year'][$year]['year_incompleted_credit_weighting'] = 0;
	$calc['year'][$year]['year_completed_mark_weighting'] = 0;
	foreach ($module['module'] as $module_id => $module_details) {
		//echo "<p>Module $module_id</p>";
		$calc['year'][$year]['module'][$module_id]['weighted_credits']
			= $calc['year'][$year]['module'][$module_id]['crds']
			/ ($calc['year'][$year]['credits_complete'] + $calc['year'][$year]['credits_incomplete'])
			* $calc['year'][$year]['weight']; // year weighting as a fraction
		//var_dump($calc['year'][$year]['module'][$module_id]['mark']);
		if ($calc['year'][$year]['module'][$module_id]['mark'] == '') {
			// incomplete weighting?
			$calc['incompleted_credit_weighting']
				+= $calc['year'][$year]['module'][$module_id]['weighted_credits'];
			
			$calc['year'][$year]['year_incompleted_credit_weighting']
				+= $calc['year'][$year]['module'][$module_id]['weighted_credits'];
		
		} else {
			$calc['year'][$year]['year_completed_credit_weighting']
				+= $calc['year'][$year]['module'][$module_id]['weighted_credits'];
			$calc['completed_credit_weighting']
				+= $calc['year'][$year]['module'][$module_id]['weighted_credits'];	
			
			$calc['year'][$year]['year_completed_mark_weighting']
				+= ($calc['year'][$year]['module'][$module_id]['weighted_credits']
				* $calc['year'][$year]['module'][$module_id]['mark']);
			$calc['completed_mark_weighting']
				+= ($calc['year'][$year]['module'][$module_id]['weighted_credits']
				* $calc['year'][$year]['module'][$module_id]['mark']);
		}

	}
}

// d($calc,'$calc');
// d($calc['completed_mark_weighting'],'completed_mark_weighting');
// d($calc['completed_credit_weighting'],'completed_credit_weighting');
// d($calc['incompleted_credit_weighting'],'incompleted_credit_weighting');
// d($modules,'$modules');


?>

<div style="clear:both;" ></div>

<h2>Degree Results</h2>

<?php // degree IS complete
if ($calc['incompleted_credit_weighting'] <= 0) : ?>

<h3>Final Grade</h3>

<p>Your degree is complete! Your final grade is: <strong><?= number_format(100*($calc['completed_mark_weighting'] / $calc['completed_credit_weighting']),2); ?>%</strong>.</p>

<p>How about adding this page as a link on your LinkedIn Profile? <a href="http://www.linkedin.com/profile/edit-additional-info" target="_blank">Go <em>here</em> to add a link</a>.</p>

<?php // no marks provided
elseif ($calc['completed_credit_weighting'] == 0) : ?>

<h3>More Details Required</h3>
<p>You must enter at least one module mark before any calculations can be performed. For an example, see <a href="/example">CourseCalc.com/example</a>.</p>

<?php // degree is NOT complete
else : ?>

<h3>Current Grade: <?= number_format(100*($calc['completed_mark_weighting'] / $calc['completed_credit_weighting']),2); ?>%</h3>

<h3>Required Marks</h3>
<p>This table shows the marks you'd need to average from now on, in order to achieve each degree classification.</p>
<table id="required_grades">
	<thead>
		<tr>
			<th>Grade</th>
			<th>Mark</th>
			<th>Required</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach (array('1<sup>st</sup>'=>0.7,'2-1'=>0.6,'2-2'=>0.5,'3<sup>rd</sup>'=>0.4) as $grade_name => $grade) : ?>
		
		<tr>
			<td><?php echo $grade_name; ?></td>
			<td><?php echo number_format(100*$grade, 2); ?>%</td>
			<td><?php
		$required_average_mark = ($grade - $calc['completed_mark_weighting']) / $calc['incompleted_credit_weighting'];
		if ($required_average_mark > 1) {
			echo 'Impossible';
		} elseif ($required_average_mark < 0) {
			echo 'Guaranteed!';
		} else {
			echo '<strong>' . number_format(100*$required_average_mark, 2) . '%</strong>';
		}
			?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<p>Your maximum possible grade (assuming 100% from now on) is: <strong><?= number_format(100*($calc['completed_mark_weighting'] + $calc['incompleted_credit_weighting']),2)?>%</strong></p>

<p>Your minimum possible grade (assuming 0% from now on) is: <strong><?= number_format(100*($calc['completed_mark_weighting']),2); ?>%</strong></p>

<?php endif; ?>

<h2>Information Overload!</h2>

<h3>Year Breakdown</h3>

<table id="year_breakdowns">
	<thead>
		<tr>
			<th>Year</th>
			<th>Credits</th>
			<th>Overall</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($calc['year'] as $year => $module) : ?>
		<tr>
			<td><?php echo $year; ?></td>
			<td><?php echo $calc['year'][$year]['credits_complete']; ?>/<?= $calc['year'][$year]['credits_complete']+$calc['year'][$year]['credits_incomplete']; ?></td>
			<td><?php echo (($calc['year'][$year]['year_completed_credit_weighting'] == 0) ? 'N/A' : number_format(100*($calc['year'][$year]['year_completed_mark_weighting'] / $calc['year'][$year]['year_completed_credit_weighting']),2).'%'); ?></td>		
		</tr>
<?php endforeach; ?>
	</tbody>
</table>

<h3>Best Modules</h3>
<?php
$best_module[1]['mark'] = 0; // Best
$best_module[2]['mark'] = 0; // Second best
$best_module[3]['mark'] = 0; // Third best
foreach ($calc['year'] as $year => $module) {
	foreach ($module['module'] as $module_id => $module_details) {
		if ($calc['year'][$year]['module'][$module_id]['mark'] == '') continue;
		if ($calc['year'][$year]['module'][$module_id]['mark'] > $best_module[1]['mark']) {
			$best_module[3] = $best_module[2];
			$best_module[2] = $best_module[1];
			$best_module[1] = $calc['year'][$year]['module'][$module_id];
		} else if ($calc['year'][$year]['module'][$module_id]['mark'] > $best_module[2]['mark']) {
			$best_module[3] = $best_module[2];
			$best_module[2] = $calc['year'][$year]['module'][$module_id];
		} else if ($calc['year'][$year]['module'][$module_id]['mark'] > $best_module[3]['mark']) {
			$best_module[3] = $calc['year'][$year]['module'][$module_id];
		}
	}
}
?>
<table id="best_modules">
	<thead>
		<tr>
			<th>Code</th>
			<th>Name</th>
			<th>Credits</th>
			<th>Mark</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($best_module as $module) : 
			if (!isset($module['crds'])) break;
		?>

		<tr>
			<td><?= ($module['code']=='') ? 'NoCode' : $module['code']; ?></td>
			<td><?= ($module['name']=='') ? 'NoName' : $module['name']; ?></td>
			<td><?= $module['crds']; ?></td>
			<td><?= number_format(100*$module['mark'],2); ?>%</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<h3>Worst Modules</h3>
<?php
$worst_module[1]['mark'] = 1; // Worst
$worst_module[2]['mark'] = 1; // Second worst
$worst_module[3]['mark'] = 1; // Third worst
foreach ($calc['year'] as $year => $module) {
	foreach ($module['module'] as $module_id => $module_details) {
		if ($calc['year'][$year]['module'][$module_id]['mark'] == '') continue;
		if ($calc['year'][$year]['module'][$module_id]['mark'] < $worst_module[1]['mark']) {
			$worst_module[3] = $worst_module[2];
			$worst_module[2] = $worst_module[1];
			$worst_module[1] = $calc['year'][$year]['module'][$module_id];
		} else if ($calc['year'][$year]['module'][$module_id]['mark'] < $worst_module[2]['mark']) {
			$worst_module[3] = $worst_module[2];
			$worst_module[2] = $calc['year'][$year]['module'][$module_id];
		} else if ($calc['year'][$year]['module'][$module_id]['mark'] < $worst_module[3]['mark']) {
			$worst_module[3] = $calc['year'][$year]['module'][$module_id];
		}
	}
}
?>
<table id="worst_modules">
	<thead>
		<tr>
			<th>Code</th>
			<th>Name</th>
			<th>Credits</th>
			<th>Mark</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($worst_module as $module) : 
			if (!isset($module['crds'])) break;
		?>
		<tr>
			<td><?= ($module['code']=='') ? 'NoCode' : $module['code']; ?></td>
			<td><?= ($module['name']=='') ? 'NoName' : $module['name']; ?></td>
			<td><?= $module['crds']; ?></td>
			<td><?= number_format(100*$module['mark'],2); ?>%</td>
		<tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php /* Other Ideas

<h3>Escape Velocity</h3>
<p>It's interesting to consider whether you're showing a trend for improvement over time.</p>





*/
