<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit title</h4>
	<textarea class="col-xs-12">example: Develop integrated Windows 7 image</textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="project" data-for-column="title"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit start date</h4>
	<textarea class="col-xs-12">example: 2015-05-22</textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="project" data-for-column="dateStart"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit expected finish date</h4>
	<textarea class="col-xs-12">example: 2015-05-22</textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="project" data-for-column="dateExpectedFinish"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit finish date</h4>
	<textarea class="col-xs-12">example: 2015-05-22</textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="project" data-for-column="dateFinished"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<!-- project managers -->
	<h4 class="col-xs-12">Edit project managers</h4>
	<select class="col-xs-12 create-system-item-list list-project-managers" data-items-field="project-managers">
		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
	</select>
	<div class="col-xs-12 project-managers"></div>
	
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="project" data-for-column="TAILORED_projectManagers"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit status</h4>
	<select class="col-xs-12">
		<option value="open">Open</option>
		<option value="in progress">In progress</option>
		<option value="finished">Finished</option>
		<option value="failed to finish">Failed to finish</option>
	</select>
	
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="project" data-for-column="status"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit allocated budget (in AUD)</h4>
	<textarea class="col-xs-12">example: 40400</textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="project" data-for-column="allocatedBudget"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit allocated time (hours)</h4>
	<textarea class="col-xs-12">example: 2400</textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="project" data-for-column="allocatedTime"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<h4 class="col-xs-12">Edit description</h4>
	<textarea class="col-xs-12">Example: A client has asked for our product to have a fingerprint scanner</textarea>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="project" data-for-column="description"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">
	<!-- project milestones -->
	<h4 class="col-xs-12">Edit milestones</h4>
	<select class="col-xs-12 create-system-item-list list-milestones" data-items-field="project-milestones">
		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
	</select>
	<div class="col-xs-12 project-milestones"></div>
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="project" data-for-column="TAILORED_milestones"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="row popup-edit content-container">

	<!-- tasks in the project -->
	<h4 class="col-xs-12">Edit tasks</h4>
	<select class="col-xs-12 create-system-item-list list-tasks" data-items-field="project-tasks">
		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
	</select>
	<div class="col-xs-12 project-tasks"></div>

	<!--
	<h4 class="col-xs-12">Edit tasks</h4>
	<textarea class="col-xs-12">example: 456,789</textarea>
	-->
	
	<button type="button" class="col-xs-4 col-xs-offset-2 btn-edit-publish" data-for-type="project" data-for-column="TAILORED_tasks"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 btn-edit-close"><span class="glyphicon glyphicon-remove"></span></button>
</div>