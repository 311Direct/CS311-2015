<?php
require('../../config/db.config.php'); 
require('../../config/db.inc.php'); 

require('../../config/json.inc.php');

require('../../config/permission.inc.php');
require('../../auth/permissions.engine.php');
require('../../model/oid.php');
require('../../model/rid.php');

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


include('test.class.php');

$OBJC = 25;
$LOOP = 5;

if(isset($_GET['reset']))
{
  $t = new TestSQL;
  $t->doResetUsers($OBJC);
  $t->doResetRoles();
  $t->doResetObjects($OBJC, $LOOP);
  $t->doResetRoleMappings($OBJC*$LOOP);
  $t->doResetProjectDefault();
  header("Location:test2.php");
}
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
      <p><a href="test2.php?reset">Reset Database and Re-Run</a> | <a href="test2.php">Re-test with current data</a></p>
      <hr />
      <h3>Test Results:</h3>
      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th width="1%">User</th>
            <th colspan="10" width="20%">Permissions</th>
            <th colspan="4">Intended Object</th>
            <th>Requested Operation</th>
            <th>Result</th>
          </tr>
          <tr>
            <th>ID</th>
            <th>SQL</th>
            <th>P_FULL_CONTROL</th>
            <th>P_READ</th>
            <th>P_WRITE</th>
            <th>P_CREATE</th>
            <th>P_DELETE</th>
            <th>P_MODIFY_ATTR</th>
            <th>P_CHANGE_ACCESS</th>
            <th>P_LIST_CONTENTS</th>
            <th>P_INHERITS_PARENT</th>
            <th>ID</th>
            <th>Table</th>
            <th>OID</th>
            <th>Value</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>           
      <?php
        
      ### COMMENCE TESTING! ###
        
      $permissionsEngine = new PermissionsEngine();
      
      for($idx = 1; $idx < $OBJC; $idx++)
      {          
          $currentRoleObject = new PermissionRoleMapper(0, $idx);
          $currentRoleObject = $currentRoleObject->getRoleInfo(); 
          if($currentRoleObject == NULL){
            continue;
          }
          echo '<tr><td colspan="17"><strong>Testing New User</strong></td></tr>';
          
        for($pdx = 9; $pdx > 0; $pdx--)
        {  
          $requestedOperation = randomPermission($pdx);
          echo '<tr>';
          echo '<td>'.$currentRoleObject->getUserID().'</td>'; 
          echo '<td>'.$currentRoleObject->getValue().'</td>';   
          
  
          echo '<td>'.permCheck($currentRoleObject->getValue(), P_FULL_CONTROL).'</td>';
          echo '<td>'.permCheck($currentRoleObject->getValue(), P_READ).'</td>';
          echo '<td>'.permCheck($currentRoleObject->getValue(), P_WRITE).'</td>';
          echo '<td>'.permCheck($currentRoleObject->getValue(), P_CREATE).'</td>';
          echo '<td>'.permCheck($currentRoleObject->getValue(), P_DELETE).'</td>';
          echo '<td>'.permCheck($currentRoleObject->getValue(), P_MODIFY_ATTR).'</td>';
          echo '<td>'.permCheck($currentRoleObject->getValue(), P_CHANGE_ACCESS).'</td>';
          echo '<td>'.permCheck($currentRoleObject->getValue(), P_LIST_CONTENTS).'</td>';
          echo '<td>'.permCheck($currentRoleObject->getValue(), P_INHERITS_PARENT).'</td>';
          
          $oid = mt_rand(0, $OBJC*$LOOP);
          
          $perms = new PermissionsObjectMapper($currentRoleObject->getUserID(), 'Tasks', $currentRoleObject->getUserID());
          if($perms->getPermissionObjects() != null)
          {
            $ppID = $perms->getPermissionObjects()[0]->getID();
            $ppTable = $perms->getPermissionObjects()[0]->getTable();
            $ppOID = $perms->getPermissionObjects()[0]->getOID();
            $ppValue = $perms->getPermissionObjects()[0]->getValue();
          }
          else
          {
            $ppID = "ERR";
            $ppTable = "ERR";
            $ppOID = "ERR";
            $ppValue = "ERR";
          }
          
          echo "<td>$ppID</td><td>$ppTable</td><td>$ppOID</td><td>$ppValue</td>";
          
          
          echo '<td>'.printPermsInfo($requestedOperation).'</td>';
          
          $actual = ($permissionsEngine->requestPermissionForOperationWithObjects($currentRoleObject, $perms, $requestedOperation) ? "Allowed" :"Disallowed");
          $color = ($actual == "Allowed" ? "success" :"danger");
          
          echo "<td class=\"$color\">$actual</td>";
          echo '</tr>';  
        }
      } 
  ?>
        </tbody>
      </table>
  </body>
</html>