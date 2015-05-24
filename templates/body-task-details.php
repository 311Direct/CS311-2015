<?php include_once('templates/edit-task.php'); ?>

<div class="row content-container">
	<h2 class="col-xs-10 task-title"><!-- title --></h2>
	<button type="button" class="col-xs-2 btn-watch"><span class="glyphicon glyphicon-eye-open"></span></button>
	<div class="col-xs-12 summary">
		<!-- summary -->
	</div>
</div>
<div class="row content-container">
	<div class="col-xs-10 log-work">
		<h2 class="col-xs-12">Log work</h2>
		<input type="number" class="col-xs-3 hours" value="1" /><span class="col-xs-9 log-hours-label">hours</span>
	</div>
	<button type="button" class="col-xs-2 btn-log-work"><span class="glyphicon glyphicon-list-alt"></span></button>
	<div class="col-xs-12 panel-content">
		<table class="col-xs-12">
			<thead>
				<tr>
					<th class="col-xs-9">When</th>
					<th class="col-xs-3">Hours</th>
				</tr>
			</thead>
			<tbody class="work-log-history">
			</tbody>
		</table>
	</div>
	<button type="button" class="col-xs-12 button-expand"><span class="glyphicon glyphicon-chevron-down"></span></button>	
</div>
<div class="row content-container">
	<div>
		<span>BUDGET</span>
		<div class="progress">
			<div class="progress-bar progress-budget">
				<!-- progress budget -->
  			</div>
		</div>
	</div>
	<div>
		<span>TIME</span>
		<div class="progress">
			<div class="progress-bar progress-time">
				<!-- progress time -->
  			</div>		
		</div>
	</div>
	<div>START DATE: <span class="start-date"><!-- start date --></span></div>
	<div>DUE DATE: <span class="due-date"><!-- due date --></span></div>
	<div>FINISH DATE: <span class="end-date"><!-- finish date --></span></div>
	<div class="flags">FLAGS: <!-- flags, e.g.: <span class="flag">Release 1.6.0</span> -->
	</div>
</div>
<div class="row content-container">
	<h3 class="col-xs-12">description</h3>
	<p class="col-xs-12 description">
		<!-- description -->
	</p>
</div>
<div class="row content-container">
	<h3 class="col-xs-12">attachments</h3>
	<?php require_once('templates/fileUpload.php'); ?>
	<div class="panel-content attachments">
		<!-- attachments, e.g.: <div class="col-xs-12 attachment">req-spec-functional.pdf</div> -->
	</div>
	<button type="button" class="col-xs-12 button-expand"><span class="glyphicon glyphicon-chevron-down"></span></button>
</div>
<div class="row content-container">
	<h3 class="col-xs-10">comments</h3>
	<button type="button" class="col-xs-2 btn-add-comment"><span class="glyphicon glyphicon-plus"></span></button>	
	<div class="panel-content comments">
		<!-- comments, e.g.:
		<article>
			<div class="comment-header"><a href="">Michael Nguyen</a> @ 18th April, 2015 1:04AM</div>
			<p>
				I feel as if this project is not moving fast enough. Please reply, thanks.
			</p>
		</article>
		-->
	</div>
	<button type="button" class="col-xs-12 button-expand"><span class="glyphicon glyphicon-chevron-down"></span></button>
</div>
<div class="row content-container">
	<h3 class="col-xs-12">sub tasks</h3>
	<div class="panel-content">
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Priority</th>
					<th>Task title</th>
					<th>Assignees</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody class="sub-tasks">
				<!-- sub tasks -->				
			</tbody>
		</table>
	</div>
	<button type="button" class="col-xs-12 button-expand"><span class="glyphicon glyphicon-chevron-down"></span></button>
</div>
<div class="row content-container">
	<h3 class="col-xs-12">dependency chain</h3>
	<canvas id="dependency-chain"></canvas>
</div>

<?php include_once('upload-mask.php'); ?>

<!-- dependencies -->
<script src="js/log-work.js"></script>