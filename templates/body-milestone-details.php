<?php include_once('templates/edit-milestone.php'); ?>

<div class="row content-container">
	<h2 class="col-xs-12 milestone-title"><!-- title --></h2>
	<div class="col-xs-12 summary">
		<!-- summary -->
	</div>
</div>
<div class="row content-container">
	<div>
		<span>BUDGET</span>
		<div class="progress">
			<div class="progress-bar progress-budget">
				<!-- budget info -->
  			</div>
		</div>
	</div>
	<div>
		<span>TIME</span>
		<div class="progress">
			<div class="progress-bar progress-time">
				<!-- time info -->
  			</div>		
		</div>
	</div>
</div>
<div class="row content-container">
	<h3 class="col-xs-12">Description</h3>
	<p class="col-xs-12 description">
		<!-- description -->
	</p>
</div>
<div class="row content-container">
	<h3 class="col-xs-12">tasks attached to milestone</h3>
	<div class="panel-content">
		<table class="col-xs-12">
			<thead>
				<tr>
					<th>ID</th>
					<th>Priority</th>
					<th>Title</th>
					<th>Project</th>
					<th>Status</th>
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
	<h3 class="col-xs-12">users working on this milestone</h3>
	<div class="panel-content">
		<table class="col-xs-12">
			<thead>
				<tr>
					<th>ID (username)</th>
					<th>Display Name</th>
					<th>Manhours put in</th>
				</tr>
			</thead>
			<tbody class="users">
				<!-- users -->
			</tbody>
		</table>
	</div>
	<button type="button" class="col-xs-12 button-expand"><span class="glyphicon glyphicon-chevron-down"></span></button>
</div>