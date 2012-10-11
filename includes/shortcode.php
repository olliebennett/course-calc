<?php if (isset($shortcode)) { ?>

<?php 
$shareurl = 'http://coursecalc.com/'.$shortcode;
$shareurl_enc = urlencode($shareurl);

$sharestring = 'I just checked my degree grade at CourseCalc.com!';
$sharestring_enc = urlencode($sharestring);

// if (isset($savefailure)) {
	// echo '<p class="error">Save failed. See error below.</p>';
// } else {
	// //facebook sharer does not work with text, but says it does here: https://developers.facebook.com/docs/share/
?>
	
<form>

<p class="success">Bookmark or share this course data: <input class="copyurl" readonly="readonly" size="40" value="<?php echo $shareurl; ?>">
or via
<a href="http://twitter.com/share?text=<?php echo $sharestring_enc; ?>&amp;url=<?php echo $shareurl_enc; ?>" title="Share my results on Twitter" ><img src="assets/img/twitter.png" height="30" class="social" alt="Twitter" /></a>
<a href="https://www.facebook.com/sharer.php?u=<?php echo $shareurl_enc; ?>" title="Share my results on Facebook" ><img src="assets/img/facebook.png" height="30" class="social" alt="Facebook" /></a>
<a href="https://plus.google.com/share?url=<?php echo $shareurl_enc; ?>" title="Share my results on Google+" ><img src="assets/img/googleplus.png" height="30" class="social" alt="Google+" /></a> 
</p>

</form>

<p>Want a custom shortcode, like <strong>CourseCalc.com/YourName</strong>? Ask me!</p>

<?php } ?>