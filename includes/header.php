<!DOCTYPE html>
<html>
	
	<head>
		
		<title>Course Calc</title>
		
		<meta name="keywords" content="university college degree course tracker classification predictor grade result calculator mark estimator"/> 
		<meta name="description" content="Calculate your predicted course result based on your current module marks at CourseCalc.com!"/>
		
		<link rel="stylesheet" type="text/css" href="/assets/css/reset.css" />
		<link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
		<link rel="stylesheet" type="text/css" href="/assets/css/fonts.css" />
		
		<!-- jQuery from Google CDN, with local fallback -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
		<script type="text/javascript">
		if (typeof jQuery == 'undefined')
		{
			document.write(unescape("%3Cscript src='/assets/js/jquery-1.8.0.min.js' type='text/javascript'%3E%3C/script%3E"));
		}
		</script>
		
<?php if (isset($modules)) : ?>
		<script>
			var data_yrweights = []; // preallocate year weights array
<?php foreach ($yrweights as $yr => $weight) : ?>
			data_yrweights[<?php echo $yr; ?>] = <?php echo $weight;?>;
<?php endforeach; ?>

			var data_modules = []; // preallocate module data array
<?php foreach ($modules as $yr => $module) : ?>
			data_modules[<?php echo $yr; ?>] = []; // preallocate array for year
<?php foreach ($module as $modnum => $modinfo) : ?>
			data_modules[<?php echo $yr; ?>][<?php echo $modnum; ?>] = []; // preallocate array for module
			data_modules[<?php echo $yr; ?>][<?php echo $modnum; ?>][0] = "<?php echo $modinfo['code']; ?>";
			data_modules[<?php echo $yr; ?>][<?php echo $modnum; ?>][1] = "<?php echo $modinfo['name']; ?>";
			data_modules[<?php echo $yr; ?>][<?php echo $modnum; ?>][2] = "<?php echo $modinfo['crds']; ?>";
			data_modules[<?php echo $yr; ?>][<?php echo $modnum; ?>][3] = "<?php echo $modinfo['mark']; ?>";
<?php endforeach; ?>
<?php endforeach; ?>
		</script>
<?php endif; ?>
		
		<script src="/assets/js/js.min.js"></script>
		
	</head>
	
	<body>
		
		<div id="header">
			<h1><a href="http://coursecalc.com/">Course Calc</a></h1>
			<hr />
			<p>How's <em>your</em> degree going? Course Calc shows your current degree grade, predicts your final classification and determines the marks needed to achieve a 1<sup>st</sup>, 2-1, 2-2 or 3<sup>rd</sup>.</p>
		</div><!-- #header -->		
		
		<div id="content">
		