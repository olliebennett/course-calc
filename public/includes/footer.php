		</div><!-- #content -->
		
		<hr />
		
		<div id="footer">
			<p>Got feedback? Want a new feature? Get in touch <a href="http://olliebennett.co.uk/">here</a>.</p>
			<p>&copy; <?= date('Y') ?> | An <a href="http://olliebennett.co.uk/">Ollie Bennett</a> weekend project, with input from <a href="http://lukewoollett.com/">Luke Woollett</a>.</p>
			<p class="small">CourseCalc is provided "as-is", without warranty of any kind and is used at your own risk. Read full <a href="http://ourdisclaimer.com/?i=CourseCalc.com">disclaimer</a>.</p>
		</div><!-- #footer -->
		
<?php
// If data arrays have not been loaded or just saved, we want to generate the first one for the user.
//$yrweights = array();
//$modules = array();
if (empty($modules) || empty($yrweights)) : ?>
		<script type="text/javascript">
			incrementYear();
			incrementYear();
			incrementYear(); // Start with three years.
		</script>
<?php else: ?>
		<script type="text/javascript">
<?php for ($i=0; $i<count($modules); $i++) : ?>
			incrementYear();
<?php endfor; ?>
			updateYearWeightingsSum(); // Manually enable auto-summing of year weightings
		</script>
<?php endif; ?>

<?php
// If there was an error with one or more of the inputs (i.e. not numeric, etc) show this error
if (isset($savefailure)) : ?>
		<script type="text/javascript">
			showError('<?php echo $savefailure; ?>');
		</script>
<?php endif; ?>
	
	</body>
</html>