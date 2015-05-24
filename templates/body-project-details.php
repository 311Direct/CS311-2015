<?php include_once('templates/edit-project.php'); ?>

<div class="row content-container">
	<h2 class="project-title"><!-- project title --></h2>
	<div class="summary">
		<!-- summary -->
	</div>
</div>
<div class="row content-container">
	<div>
		<span>BUDGET</span>
		<div class="progress">
			<div class="progress-bar progress-budget">
				<!-- used/allocated budget -->
  			</div>
		</div>
	</div>
	<div>
		<span>TIME</span>
		<div class="progress">
			<div class="progress-bar progress-time">
  				<!-- used/allocated time -->
  			</div>
		</div>
	</div>
	<a href="effort-estimation.php" class="anchor-effort-est anchor-no-underline"><div class="col-xs-12 btn btn-effort-estimation">VIEW EFFORT ESTIMATION</div></a>
	<a href="project-visualization.php" class="anchor-vis anchor-no-underline"><div class="col-xs-12 btn btn-visualization">VIEW VISUALIZATION</div></a>
</div>
<div class="row content-container">
	<h3 class="col-xs-12">description</h3>
	<div class="col-xs-12 panel-content description">
		<!-- description -->
	</div>
	<button type="button" class="col-xs-12 button-expand"><span class="glyphicon glyphicon-chevron-down"></span></button>
</div>
<div class="row content-container">
	<h3 class="col-xs-12" id="deliverables">deliverables</h3>
	<?php require_once('fileUpload.php'); ?>
	<div class="col-xs-12 panel-content attachments">
		<!-- deliverables -->
		<!-- e.g.: <div class="col-xs-12 attachment">req-spec-functional.pdf (Requirements specification)</div> -->
	</div>
	<button type="button" class="col-xs-12 button-expand"><span class="glyphicon glyphicon-chevron-down"></span></button>
</div>
<div class="row content-container">
	<div>
		<h3 class="col-xs-12">milestones</h3>
	</div>
	<div class="col-xs-12 panel-content">
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Title</th>
					<th>Progress</th>
				</tr>
			</thead>
			<tbody class="milestones">
				<!-- milestones -->						
			</tbody>
		</table>
	</div>
	<button type="button" class="col-xs-12 button-expand"><span class="glyphicon glyphicon-chevron-down"></span></button>
</div>
<div class="row content-container">
	<div>
		<h3 class="col-xs-12 btn-assign-task">tasks</h3>
	</div>
	<div class="col-xs-12 panel-content">
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Priority</th>
					<th>Title</th>
					<th>Status</th>
					<th>Assignee</th>
				</tr>
			</thead>
			<tbody class="tasks">
				<!-- tasks -->						
			</tbody>
		</table>
	</div>
	<button type="button" class="col-xs-12 button-expand"><span class="glyphicon glyphicon-chevron-down"></span></button>
</div>
<div class="row content-container">
	<h3 class="col-xs-12">users working on project</h3>
	<div class="panel-content">
		<table>
			<thead>
				<tr>
					<th>ID (username)</th>
					<th>Display Name</th>
					<th>Manhours put in</th>
				</tr>
			</thead>
			<tbody class="users">
				<!-- users working on project -->		
			</tbody>
		</table>
	</div>
	<button type="button" class="col-xs-12 button-expand"><span class="glyphicon glyphicon-chevron-down"></span></button>
</div>
<div class="row content-container">
	<h3 class="col-xs-10">comments</h3>
	<button type="button" class="col-xs-2 btn-add-comment"><span class="glyphicon glyphicon-plus"></span></button>	
	<div class="col-xs-12 panel-content comments">
		<!-- comments -->
		<!-- e.g.: <article>
			<div class="comment-header"><a href="">Michael Nguyen</a> @ 18th April, 2015 1:04AM</div>
			<p>
				I feel as if this project is not moving fast enough. Please reply, thanks.
			</p>
		</article>
		-->
	</div>
	<button type="button" class="col-xs-12 button-expand"><span class="glyphicon glyphicon-chevron-down"></span></button>
</div>

<?php include_once('upload-mask.php'); ?>