<div class="row col-md-12 popup popup-new-task content-container" data-create-item-type="task">
	<h3 class="col-xs-10">new task</h3>
	<button type="button" class="col-xs-2 cancel"><span class="glyphicon glyphicon-remove"></span></button>
	<h4 class="col-xs-12">Title</h4>	
	<input type="text" class="col-xs-12 title" placeholder="Title" />
	<h4 class="col-xs-12">Project ID</h4>
	<input type="text" class="col-xs-12 project-id" placeholder="example: 23" />
	
	<!-- assignees -->
	<h4 class="col-xs-12">Assignees</h4>
	<select class="col-xs-12 create-system-item-list list-users" data-items-field="assignees">
		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
	</select>
	<div class="col-xs-12 assignees"></div>

	<h4 class="col-xs-12">Priority</h4>
	<select class="col-xs-12 priority">
		<option value="critical">Critical</option>
		<option value="high">High</option>
		<option value="low">Low</option>
		<option value="trivial">Trivial</option>
	</select>
	<h4 class="col-xs-12">Budget</h4>
	<input type="number" class="col-xs-12 allocated-budget" min="0" value="10000" />
	<h4 class="col-xs-12">Manhours</h4>
	<input type="number" class="col-xs-12 allocated-time" min="0" value="1000" />
	<h4 class="col-xs-12">Start date</h4>
	<input type="date" class="col-xs-12 date-start" />
	<h4 class="col-xs-12">Deadline</h4>
	<input type="date" class="col-xs-12 date-expected-finish" />
	<h4 class="col-xs-12">Finish date</h4>
	<input type="date" class="col-xs-12 date-end" />
	<h4 class="col-xs-12">Flags</h4>
	<input type="text" class="col-xs-12 flags" placeholder="1.6.0,1.6.0 A1" />
	<h4 class="col-xs-12">Description</h4>
	<textarea class="col-xs-12 description"></textarea>
	
	<!-- subtask IDs -->
	<h4 class="col-xs-12">Subtask IDs</h4>
	<select class="col-xs-12 create-system-item-list list-tasks" data-items-field="subtask-ids">
		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
	</select>
	<div class="col-xs-12 subtask-ids"></div>
	
	<!-- dependee IDs -->
	<h4 class="col-xs-12">Dependee IDs</h4>
	<select class="col-xs-12 create-system-item-list list-tasks" data-items-field="dependee-ids">
		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
	</select>
	<div class="col-xs-12 dependee-ids"></div>

	<!-- dependant IDs -->
	<h4 class="col-xs-12">Dependant IDs</h4>
	<select class="col-xs-12 create-system-item-list list-tasks" data-items-field="dependant-ids">
		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
	</select>
	<div class="col-xs-12 dependant-ids"></div>
	
	<h4 class="col-xs-12">Status</h4>
	<select class="col-xs-12 status">
		<option value="open">Open</option>
		<option value="in progress">In progress</option>
		<option value="suspended">Suspended</option>
		<option value="closed">Closed</option>
	</select>
	
	<!-- milestones that this task belongs to -->
	<h4 class="col-xs-12">Milestones</h4>
	<select class="col-xs-12 create-system-item-list list-milestones" data-items-field="task-milestones">
		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
	</select>
	<div class="col-xs-12 task-milestones"></div>	
	
	<button type="button" class="col-xs-4 col-xs-offset-2 create"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 cancel"><span class="glyphicon glyphicon-remove"></span></button>
</div>