<div class="row col-md-12 popup popup-new-project content-container" data-create-item-type="project">
	<h3 class="col-xs-10">new project</h3>
	<button type="button" class="col-xs-2 cancel"><span class="glyphicon glyphicon-remove"></span></button>
	<h4 class="col-xs-12">Title</h4>
	<input type="text" class="col-xs-12 title" placeholder="Title" />
	<h4 class="col-xs-12">Start date</h4>
	<input type="date" class="col-xs-12 date-start" />
	<h4 class="col-xs-12">Deadline</h4>
	<input type="date" class="col-xs-12 date-expected-finish" />
	<h4 class="col-xs-12">End date</h4>
	<input type="date" class="col-xs-12 date-finished" />
	
	<!-- project managers -->
	<h4 class="col-xs-12">Project managers</h4>
	<select class="col-xs-12 create-system-item-list list-project-managers" data-items-field="project-managers">
		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
	</select>
	<div class="col-xs-12 project-managers"></div>
	
	<h4 class="col-xs-12">Budget</h4>
	<input type="number" class="col-xs-12 allocated-budget" min="0" value="10000" />
	<h4 class="col-xs-12">Manhours</h4>
	<input type="number" class="col-xs-12 allocated-time" min="0" value="1000" />	
	<h4 class="col-xs-12">Status</h4>
	<select class="col-xs-12 status">
		<option value="open">Open</option>
		<option value="in progress">In progress</option>
		<option value="finished">Finished</option>
		<option value="failed to finish">Failed to finish</option>
	</select>
	<h4 class="col-xs-12">Milestones</h4>
	
	<!-- project milestones -->
	<select class="col-xs-12 create-system-item-list list-milestones" data-items-field="project-milestones">
		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
	</select>
	<div class="col-xs-12 project-milestones"></div>
	
	<h4 class="col-xs-12">Description</h4>
	<textarea class="col-xs-12 description"></textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 create"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 cancel"><span class="glyphicon glyphicon-remove"></span></button>
</div>