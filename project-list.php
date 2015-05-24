<?php 
require('permissionsheader.php');
include_once('templates/header.php'); 

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