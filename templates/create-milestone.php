<div class="row col-md-12 popup popup-new-milestone content-container" data-create-item-type="milestone">
	<h3 class="col-xs-10">new milestone</h3>
	<button type="button" class="col-xs-2 cancel"><span class="glyphicon glyphicon-remove"></span></button>
	<h4 class="col-xs-12">Title</h4>	
	<input type="text" class="col-xs-12 title" placeholder="Title" />
	<h4 class="col-xs-12">Project ID</h4>
	<input type="text" class="col-xs-12 project-id" placeholder="example: 32" />
	<h4 class="col-xs-12">Budget</h4>
	<input type="number" class="col-xs-12 allocated-budget" min="0" value="10000" />
	<h4 class="col-xs-12">Manhours</h4>
	<input type="number" class="col-xs-12 allocated-time" min="0" value="1000" />
	<h4 class="col-xs-12">Description</h4>
	<textarea class="col-xs-12 description"></textarea>
	<h4 class="col-xs-12">Start date</h4>
	<input type="date" class="col-xs-12 start-date" />
	<h4 class="col-xs-12">Due date</h4>
	<input type="date" class="col-xs-12 due-date" />
	<h4 class="col-xs-12">Finished date</h4>
	<input type="date" class="col-xs-12 end-date" />
	<h4 class="col-xs-12">Status</h4>
	<select class="col-xs-12 status">
		<option value="open">Open</option>
		<option value="in progress">In progress</option>
		<option value="finished">Finished</option>
		<option value="failed to finish">Failed to finish</option>
	</select>
	
	<!-- tasks in the milestone -->
	<h4 class="col-xs-12">Tasks</h4>
	<select class="col-xs-12 create-system-item-list list-tasks" data-items-field="milestone-tasks">
		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
	</select>
	<div class="col-xs-12 milestone-tasks"></div>	
		
	<button type="button" class="col-xs-4 col-xs-offset-2 create"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 cancel"><span class="glyphicon glyphicon-remove"></span></button>
</div>