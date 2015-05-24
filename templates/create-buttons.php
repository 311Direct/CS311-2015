<?php
if($PERMISSIONS_ENGINE->canCompleteRequestForOperation($rolePermissions->getRoleInfo()->getPermissionsValue(), P_FULL_CONTROL))
  echo '<button class="col-xs-6 btn-create-project" data-item-type="project"><span class="glyphicon glyphicon-plus"></span> Project</button>';
  
if($PERMISSIONS_ENGINE->canCompleteRequestForOperation($rolePermissions->getRoleInfo()->getPermissionsValue(), P_CREATE))
  echo '<button class="col-xs-6 btn-create-milestone" data-item-type="milestone"><span class="glyphicon glyphicon-plus"></span> Milestone</button>';

if($PERMISSIONS_ENGINE->canCompleteRequestForOperation($rolePermissions->getRoleInfo()->getPermissionsValue(), P_CREATE))
  echo '<button class="col-xs-6 btn-create-task" data-item-type="task"><span class="glyphicon glyphicon-plus"></span> Task</button>';

if($PERMISSIONS_ENGINE->canCompleteRequestForOperation($rolePermissions->getRoleInfo()->getPermissionsValue(), P_FULL_CONTROL))
  echo '<button class="col-xs-6 btn-create-user" data-item-type="user"><span class="glyphicon glyphicon-plus"></span> User</button>';
