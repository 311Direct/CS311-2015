<?php 

/* Database stuff */
include('php/Direct/config/db.config.php');
include('php/Direct/config/db.inc.php');
include('php/Direct/config/json.inc.php');

/* Load Up Permissions! */
include('php/Direct/model/roles.permission.php');
include('php/Direct/model/mappings.permission.php');
include('php/Direct/model/objects.permission.php');
require('php/Direct/config/permission.inc.php');
require('php/Direct/auth/permissions.engine.php');

$PERMISSIONS_ENGINE = new PermissionsEngine();
/* 
  1) Find the user's place in this project
  2) If they do not have one, find the project default's
  3) If they do have one, get the Role's permission
*/
global $userRole;
global $rolePermissions;

$userRole = new DBPermAssignmentMappingsModel(1, 1);
$rolePermissions = new DBRoleInformationModel($userRole->getAssignmentModel()->getRoleMappingID(), 1);
