<?php 
require('permissionsheader.php');
require('templates/header.php'); 

if(!$PERMISSIONS_ENGINE->canCompleteRequestForOperation($rolePermissions->getRoleInfo()->getPermissionsValue(), P_FULL_CONTROL))
{
  ?>
<div class="row content-container">
	<div class="col-xs-12"><a href="task-list.php">Tasks</a></div>
</div>
<?php 
  
if($PERMISSIONS_ENGINE->canCompleteRequestForOperation($rolePermissions->getRoleInfo()->getPermissionsValue(), (P_FULL_CONTROL | P_CREATE)))
{ 
  echo '<div class="row content-container visible-xs visible-sm">';
  require_once('templates/create-buttons.php');
  echo '</div>';
}

?>
<div id="project-list" class="row content-container">
	<h4>Permission Denied</h4>
	<p>You do not have Full Control access. You may not view this page.</p>
</div>
<?php
  include('templates/footer.php');
}


if($PERMISSIONS_ENGINE->canCompleteRequestForOperation($rolePermissions->getRoleInfo()->getPermissionsValue(), P_FULL_CONTROL))
  include_once('templates/create-project.php'); 
  

if($PERMISSIONS_ENGINE->canCompleteRequestForOperation($rolePermissions->getRoleInfo()->getPermissionsValue(), P_CREATE))
 include_once('templates/create-milestone.php'); 


if($PERMISSIONS_ENGINE->canCompleteRequestForOperation($rolePermissions->getRoleInfo()->getPermissionsValue(), P_CREATE))
 include_once('templates/create-task.php'); 


if($PERMISSIONS_ENGINE->canCompleteRequestForOperation($rolePermissions->getRoleInfo()->getPermissionsValue(), P_FULL_CONTROL))
 include_once('templates/create-user.php'); 
 
 include_once('templates/body-project-list.php'); 
 include_once('templates/footer.php'); 

?>