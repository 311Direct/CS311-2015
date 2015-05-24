<div class="row popup-edit content-container">
  <div class="field-edit">
  	<h4 class="col-xs-12">Edit title</h4>
  	<input type="text" class="col-xs-12 col-lg-9" />
  	<button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish" data-for-type="project" data-for-column="title"><span class="glyphicon glyphicon-ok"></span></button>
  </div>
  <div class="field-edit">
  	<h4 class="col-xs-12">Edit start date</h4>
  	<input type="text" class="col-xs-12 col-lg-9" />
  	<button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish" data-for-type="project" data-for-column="dateStart"><span class="glyphicon glyphicon-ok"></span></button>
  </div>
  <div class="field-edit">
  	<h4 class="col-xs-12">Edit expected finish date</h4>
  	<input type="text" class="col-xs-12 col-lg-9" />
  	<button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish" data-for-type="project" data-for-column="dateExpectedFinish"><span class="glyphicon glyphicon-ok"></span></button>
  </div>
  <div class="field-edit">
  	<h4 class="col-xs-12">Edit finish date</h4>
  	<input type="text" class="col-xs-12 col-lg-9" />
  	<button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish" data-for-type="project" data-for-column="dateFinished"><span class="glyphicon glyphicon-ok"></span></button>
  </div>
  <div class="field-edit">
  	<!-- project managers -->
  	<h4 class="col-xs-12">Edit project managers</h4>
  	<select class="col-xs-12 col-lg-9 create-system-item-list list-project-managers" data-items-field="project-managers">
  		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
  	</select>  	
  	<button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish" data-for-type="project" data-for-column="TAILORED_projectManagers"><span class="glyphicon glyphicon-ok"></span></button>
  	<div class="col-xs-12 project-managers"></div>
  </div>
  <div class="field-edit">
  	<h4 class="col-xs-12 ">Edit status</h4>
  	<select class="col-xs-12 col-lg-9">
  		<option value="open">Open</option>
  		<option value="in progress">In progress</option>
  		<option value="finished">Finished</option>
  		<option value="failed to finish">Failed to finish</option>
  	</select>
  	
  	<button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish" data-for-type="project" data-for-column="status"><span class="glyphicon glyphicon-ok"></span></button>
  </div>
  <div class="field-edit">
  	<h4 class="col-xs-12">Edit allocated budget (in AUD)</h4>
  	<input type="text" class="col-xs-12 col-lg-9" />
  	<button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish" data-for-type="project" data-for-column="allocatedBudget"><span class="glyphicon glyphicon-ok"></span></button>
  </div>
  <div class="field-edit">
  	<h4 class="col-xs-12">Edit allocated time (hours)</h4>
  	<input type="text" class="col-xs-12 col-lg-9" />
  	<button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish" data-for-type="project" data-for-column="allocatedTime"><span class="glyphicon glyphicon-ok"></span></button>
  </div>
  <div class="field-edit">
  	<h4 class="col-xs-12">Edit description</h4>
  	<input type="text" class="col-xs-12 col-lg-9" />
  	<button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish" data-for-type="project" data-for-column="description"><span class="glyphicon glyphicon-ok"></span></button>
  </div>
  <div class="field-edit">
  	<!-- project milestones -->
  	<h4 class="col-xs-12">Edit milestones</h4>
  	<select class="col-xs-12 col-lg-9 create-system-item-list list-milestones" data-items-field="project-milestones">
  		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
  	</select>
  	<button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish" data-for-type="project" data-for-column="TAILORED_milestones"><span class="glyphicon glyphicon-ok"></span></button>
  	<div class="col-xs-12 project-milestones"></div>
  </div>
  <div class="field-edit">
  
  	<!-- tasks in the project -->
  	<h4 class="col-xs-12">Edit tasks</h4>
  	<select class="col-xs-12 col-lg-9 create-system-item-list list-tasks" data-items-field="project-tasks">
  		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
  	</select>
  
  	<!--
  	<h4 class="col-xs-12">Edit tasks</h4>
  	<input type="text" class="col-xs-12 col-lg-9" />

example: 456,789</textarea>
  	-->
  	
  	<button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish" data-for-type="project" data-for-column="TAILORED_tasks"><span class="glyphicon glyphicon-ok"></span></button>
  	<div class="col-xs-12 project-tasks"></div>
  </div>
</div>