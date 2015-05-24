<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit title</h4>
	<textarea class="col-xs-12">example: Develop integrated Windows 7 image</textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="task" data-for-column="title"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">

	<!-- assignees -->
	<h4 class="col-xs-12">Edit assignees</h4>
	<select class="col-xs-12 create-system-item-list list-users" data-items-field="assignees">
		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
	</select>
	<div class="col-xs-12 assignees"></div>

	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="task" data-for-column="TAILORED_assignees"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit priority</h4>
	<select class="col-xs-12">
		<option value="critical">Critical</option>
		<option value="high">High</option>
		<option value="low">Low</option>
		<option value="trivial">Trivial</option>
	</select>
	
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="task" data-for-column="priority"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit status</h4>
	<select class="col-xs-12">
		<option value="open">Open</option>
		<option value="in progress">In progress</option>
		<option value="suspended">Suspended</option>
		<option value="closed">Closed</option>
	</select>
	
	<!--
	<textarea class="col-xs-12"></textarea>
	-->
	
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="task" data-for-column="status"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit allocated budget (in AUD)</h4>
	<textarea class="col-xs-12">example: 40400</textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="task" data-for-column="allocatedBudget"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit allocated time (hours)</h4>
	<textarea class="col-xs-12">example: 2400</textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="task" data-for-column="allocatedTime"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit start date</h4>
	<textarea class="col-xs-12">example: 2015-05-22</textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="task" data-for-column="startDate"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit due date</h4>
	<textarea class="col-xs-12">example: 2015-05-22</textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="task" data-for-column="dueDate"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit finish date</h4>
	<textarea class="col-xs-12">example: 2015-05-22</textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="task" data-for-column="endDate"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit flags</h4>
	<textarea class="col-xs-12">example: 1.6.0,1.6.0 A1</textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="task" data-for-column="TAILORED_flags"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit description</h4>
	<textarea class="col-xs-12">Example: A client has asked for our product to have a fingerprint scanner</textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="task" data-for-column="description"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">

	<!-- subtasks -->
	<h4 class="col-xs-12">Edit sub tasks</h4>
	<select class="col-xs-12 create-system-item-list list-tasks" data-items-field="project-subtasks">
		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
	</select>
	<div class="col-xs-12 project-subtasks"></div>

	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="task" data-for-column="TAILORED_subtasks"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">

	<!-- milestones that this task belongs to -->
	<h4 class="col-xs-12">Edit milestones this task belongs to</h4>
	<select class="col-xs-12 create-system-item-list list-milestones" data-items-field="task-milestones">
		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
	</select>
	<div class="col-xs-12 task-milestones"></div>

	<!--
	<h4 class="col-xs-12">Edit milestones this task belongs to</h4>
	<textarea class="col-xs-12">example: 23,41</textarea>
	-->
	
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="task" data-for-column="TAILORED_milestonesThatTaskBelongsTo"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>