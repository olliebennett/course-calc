<h2>Course Details</h2>

	<p>Fill in the deets below to calculate your result. For any incompleted modules, simply leave the "mark" field blank.</p>
	<form id="course" name="details" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	
	<table id="course_info">
		<tbody>
			<tr>
				<th>Course Title</th>
				<td><input type="text" id="coursetitle" name="coursetitle" value="<?= ((isset($course_title)) ? stripslashes($course['title']) : '') ?>" ></td>
			</tr>
			<tr>
				<th>Course Length</th>
				<td><a href="javascript:decrementYear()">[-]</a> <span id="num_years">0</span> <a href="javascript:incrementYear()">[+]</a> years</td>
			</tr>
		</tbody>
	</table><!-- #weightings -->
	
	<div class="clearfix;"></div>
	
	<table id="weightings">
		<thead>
			<tr>
				<th>Year</th>
				<th>Weighting</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Total:</th>
				<th><span id="year_weightings_total">xxx</span> %</th>
			</tr>
		</tfoot>
	</table><!-- #weightings -->
	
	<table id="modules">
		<thead>
			<tr>
				<th>Module Code</th>
				<th>Module Name</th>
				<th>Credits</th>
				<th>Mark</th>
			</tr>
		</thead>
	</table><!-- #modules -->
	
	<div class="clearfix"></div>
				
	<input type="submit" name="save" value="Calculate and Save"  /> or <a href="http://coursecalc.com/" onclick="return confirm('This will wipe any data entered above. Continue?');" class="reset">RESET</a>
					
	</form>
			
<div id="results">
	&nbsp;
</div>