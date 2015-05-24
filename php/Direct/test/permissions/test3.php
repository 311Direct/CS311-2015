<?php
require('../../config/db.config.php'); 
require('../../config/db.inc.php'); 

require('../../config/json.inc.php');

require('../../config/permission.inc.php');
require('../../auth/permissions.engine.php');

require('../../model/user.php');

function printPermsInfo($perms)
{
  $result = array();
  if( P_FULL_CONTROL     & $perms){ $result[] = "<li>P_FULL_CONTROL</li>"; }
  if( P_READ             & $perms){ $result[] = "<li>P_READ</li>"; }
  if( P_WRITE            & $perms){ $result[] = "<li>P_WRITE</li>"; }
  if( P_CREATE           & $perms){ $result[] = "<li>P_CREATE</li>"; }
  if( P_DELETE           & $perms){ $result[] = "<li>P_DELETE</li>"; }
  if( P_MODIFY_ATTR      & $perms){ $result[] = "<li>P_MODIFY_ATTR</li>"; }
  if( P_CHANGE_ACCESS    & $perms){ $result[] = "<li>P_CHANGE_ACCESS</li>"; }
  if( P_LIST_CONTENTS    & $perms){ $result[] = "<li>P_LIST_CONTENTS</li>"; }
  if( P_INHERITS_PARENT  & $perms){ $result[] = "<li>P_INHERITS_PARENT</li>"; }
  if( 0 == $perms){ $result[] = "<li>P_PERMISSION_UNDEF </li>"; }    
  $results = "";
  foreach($result as $r)
    $results .= $r;
    
  return $results;
}

function printPermsName($perms)
{
  if( P_FULL_CONTROL     & $perms){ return "P_FULL_CONTROL "; }
  if( P_READ             & $perms){ return "P_READ "; }
  if( P_WRITE            & $perms){ return "P_WRITE "; }
  if( P_CREATE           & $perms){ return "P_CREATE "; }
  if( P_DELETE           & $perms){ return "P_DELETE "; }
  if( P_MODIFY_ATTR      & $perms){ return "P_MODIFY_ATTR "; }
  if( P_CHANGE_ACCESS    & $perms){ return "P_CHANGE_ACCESS "; }
  if( P_LIST_CONTENTS    & $perms){ return "P_LIST_CONTENTS "; }
  if( P_INHERITS_PARENT  & $perms){ return "P_INHERITS_PARENT"; }
  if( 0 == $perms){ return "P_PERMISSION_UNDEF "; }
}

function permCheck($perm, $operation)
{
  return (($perm & $operation) == $operation ?"YES":"NO");
}

function randomPermission($z)
{
  $a = ($z == null ? mt_rand(1,9) : $z);
  
  switch($a)
  {
    case 9: return P_FULL_CONTROL; break;
    case 8: return P_READ; break;
    case 7: return P_WRITE; break;
    case 6: return P_CREATE; break;
    case 5: return P_DELETE; break;
    case 4: return P_MODIFY_ATTR; break;
    case 3: return P_CHANGE_ACCESS; break;
    case 2: return P_LIST_CONTENTS; break;
    case 1: return P_INHERITS_PARENT; break;
    default: return 0; break;
  }
}

const PRE_TAG = '<pre>';
const PRE_TAE = '</pre>';

function pre_dump($ob)
{
  echo PRE_TAG;
  var_dump($ob);
  echo PRE_TAE;
}
include('test.class.php');

$OBJC = 10;
$LOOP = 10;

if(isset($_GET['reset']))
{
  $t = new TestSQL;
  $t->doResetUsers($OBJC);
  $t->doResetRoles();
  $t->doResetObjects($OBJC, $LOOP);
  $t->doResetRoleMappings($OBJC, $LOOP);
  header("Location:test3.php");
}

$PERMISSIONS_ENGINE = new PermissionsEngine();

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Permissions Testing</title>
  </head>
  <link href="thecss.css" rel="stylesheet" />
  <body>
    <div class="container-fluid">
      <h2>Permissions Test</h2>
      <p>This will test the permissions of various permissions of various test users with a series of random objects in the database.</p>
      <p><a href="test2.php?reset">Reset Database and Re-Run</a> | <a href="test3.php">Re-test with current data</a></p>
      <hr />
      <h3>Test Results:</h3>
      <h4>Full Control User</h4>
      <?php
        /* We are going to load a User 1 of Project 0 and Test from there */
        $testUser1 = new DBPermAssignmentMappingsModel(0, 1);
        pre_dump($testUser1);
        
        // We now have our role mapping id. So lets find out information about it
        $roleInfo = new DBRoleInformationModel($testUser1->getModel()->getRoleMappingID(), 0);
        pre_dump($roleInfo);
        
        /* Armed with this information, we will now go ahead and look at Task whose id = 1 */
        $mapInfo = new DBPermRolesToObjectMappings($testUser1->getModel()->getRoleMappingID(), 'Tasks', 1);
        pre_dump($mapInfo);
        
      
      ?>           
  </body>
</html>