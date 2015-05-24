<div class="row popup-edit content-container">
  <h3>Task Attributes</h3>
  
  <h4 class="col-xs-12">Title</h4>
  <div class="field-edit">
  <input type="text" class="col-xs-12 col-lg-9" placeholder="Title" />
  <button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish" data-for-type="task" data-for-column="title">
  <span class="glyphicon glyphicon-ok-sign"></span>
  </button>
  </div>
  <!--	<button type="button" class="col-xs-1 col-lgaaa-1 btn-edit-close"><span class="glyphicon glyphicon-resize-small"></span></button></div> -->
  
  <h4 class="col-xs-12">Priority</h4>
  <div class="field-edit">
  <select class="col-xs-12 col-lg-9">
  	<option value="critical">Critical</option>
  	<option value="high">High</option>
  	<option value="low">Low</option>
  	<option value="trivial">Trivial</option>
  </select>	
  <button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish"  data-for-type="task" data-for-column="priority">
  <span class="glyphicon glyphicon-ok-sign"></span>
  </button>
  </div>
  
  <h4 class="col-xs-12">Status</h4>
  <div class="field-edit">
  <select class="col-xs-12 col-lg-9">
  	<option value="open">Open</option>
  	<option value="in progress">In Progress</option>
  	<option value="suspended">Suspended</option>
  	<option value="closed">Closed</option>
  </select>
  <button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish"  data-for-type="task" data-for-column="status">
  <span class="glyphicon glyphicon-ok-sign"></span>
  </button>
  </div>
  
  <h4 class="col-xs-12">Edit description</h4>
  <div class="field-edit">
  <input type="text" class="col-xs-12 col-lg-9" />
  <button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish"  data-for-type="task" data-for-column="description">
  <span class="glyphicon glyphicon-ok-sign"></span>
  </button>
  </div>
  
  <h4 class="col-xs-12">Edit allocated budget (in AUD)</h4>
  <div class="field-edit">
  <input type="number" class="col-xs-12 col-lg-9" />
  <button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish"  data-for-type="task" data-for-column="allocatedBudget">
  <span class="glyphicon glyphicon-ok-sign"></span>
  </button>
  </div>
  
  <h4 class="col-xs-12">Edit allocated time (hours)</h4>
  <div class="field-edit">
  <input type="text" class="col-xs-12 col-lg-9" />
  <button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish"  data-for-type="task" data-for-column="allocatedTime">
  <span class="glyphicon glyphicon-ok-sign"></span>
  </button>
  </div>
  
  <h4 class="col-xs-12">Edit flags</h4>
  <div class="field-edit">
  <input type="text" class="col-xs-12 col-lg-9" />
  <button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish"  data-for-type="task" data-for-column="TAILORED_flags">
  <span class="glyphicon glyphicon-ok-sign"></span>
  </button>
  </div>
  
  <h4 class="col-xs-12">Edit start date</h4>
  <div class="field-edit">
  <input type="date" class="col-xs-12 col-lg-9" />
  <button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish"  data-for-type="task" data-for-column="startDate">
  <span class="glyphicon glyphicon-ok-sign"></span>
  </button>
  </div>
  
  <h4 class="col-xs-12">Edit due date</h4>
  <div class="field-edit">
  <input type="date" class="col-xs-12 col-lg-9" />
  <button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish"  data-for-type="task" data-for-column="dueDate">
  <span class="glyphicon glyphicon-ok-sign"></span>
  </button>
  </div>
  
  <h4 class="col-xs-12">Edit finish date</h4>
  <div class="field-edit">
  <input type="date" class="col-xs-12 col-lg-9" />
  <button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish"  data-for-type="task" data-for-column="endDate">
  <span class="glyphicon glyphicon-ok-sign"></span>
  </button>
  </div>
  
  </div>
  
  
  
  <?php 
  if(!$PERMISSIONS_ENGINE->canCompleteRequestForOperation($rolePermissions->getRoleInfo()->getPermissionsValue(), P_FULL_CONTROL))
    return;
  ?>
  <div class="row popup-edit content-container">
  <h3>Task Relations</h3>
  
  <!-- assignees -->
  <h4 class="col-xs-12">Assignees</h4>
  <div class="field-edit">
  <select class="col-xs-12 col-lg-9 create-system-item-list list-users" data-items-field="assignees">
  	<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
  </select>
  <button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish"  data-for-type="task" data-for-column="TAILORED_assignees">
  <span class="glyphicon glyphicon-ok-sign"></span>
  </button>
  </div>
  <div class="col-xs-12 assignees"></div>
  
  
  
  <!-- subtasks -->
  <h4 class="col-xs-12">Sub-tasks</h4>
  <div class="field-edit">
  <select class="col-xs-12 col-lg-9" data-items-field="project-subtasks">
  	<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
  </select>
  <button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish"  data-for-type="task" data-for-column="TAILORED_subtasks">
  <span class="glyphicon glyphicon-ok-sign"></span>
  </button>
  </div>
  <div class="col-xs-12 project-subtasks"></div>
  
  
  <!-- milestones that this task belongs to -->
  <h4 class="col-xs-12">Milestones</h4>
  <div class="field-edit">
  <select class="col-xs-12 col-lg-9 create-system-item-list list-milestones" data-items-field="task-milestones">
  	<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
  </select>
  
  <!--
  <h4 class="col-xs-12">Edit milestones this task belongs to</h4>
  <div class="field-edit">
  <input type="text" class="col-xs-12 col-lg-9" />example: 23,41
  -->
  
  <button type="button" class="col-xs-1 col-lg-2 col-lg-offset-2 btn-edit-publish"  data-for-type="task" data-for-column="TAILORED_milestonesThatTaskBelongsTo">
  <span class="glyphicon glyphicon-ok-sign"></span>
  </button>
  </div>
  <div class="col-xs-12 col-lg-9 task-milestones"></div>
</div>