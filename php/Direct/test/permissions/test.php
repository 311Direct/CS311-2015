<?php
require('../../config/db.config.php'); 
require('../../config/db.inc.php'); 

require('../../config/json.inc.php');

require('../../config/permission.inc.php');
require('../../auth/permissions.engine.php');
require('../../model/oid.php');
require('../../model/rid.php');


function printPermsInfo($perms)
{
  $result = array();
  if( P_FULL_CONTROL     & $perms){ $result[] = "P_FULL_CONTROL"; }
  if( P_READ             & $perms){ $result[] = "P_READ"; }
  if( P_WRITE            & $perms){ $result[] = "P_WRITE"; }
  if( P_CREATE           & $perms){ $result[] = "P_CREATE"; }
  if( P_DELETE           & $perms){ $result[] = "P_DELETE"; }
  if( P_MODIFY_ATTR      & $perms){ $result[] = "P_MODIFY_ATTR"; }
  if( P_CHANGE_ACCESS    & $perms){ $result[] = "P_CHANGE_ACCESS"; }
  if( P_LIST_CONTENTS    & $perms){ $result[] = "P_LIST_CONTENTS"; }
  if( P_INHERITS_PARENT  & $perms){ $result[] = "P_INHERITS_PARENT"; }
  if( 0 == $perms){ $result[] = "P_PERMISSION_UNDEF "; }
    
  $results = "";
  foreach($result as $r)
    $results .= "$r, ";
    
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

const PRE_TAG = '<pre>';
const PRE_TAE = '</pre>';

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Permissions Testing</title>
  </head>
  <link href="thecss.css" rel="stylesheet" />
  <body>
    <div class="container">
      <h2>Permissions Test</h2>
      <p>This will test the permissions of various permissions objects with increasing permission levels.</p>
      <hr />
      <h3>Test Results:</h3>
      <table class="table table-bordered">
        <thead>
          <th>Int</th>
          <th>Action</th>
          <th>Required</th>
          <th>Operation</th>
        </thead>
        <tbody>
      <?php
        
      ### COMMENCE TESTING! ###
        
      $permissionsEngine = new PermissionsEngine();
      
      $bdx = 0;
      for($idx = 0; $idx <= P_MAX_ENTRY; $idx+=2)
      {
        $currentRoleObject = new PermissionRole(1, 2, 12, "Calle Test", $idx);
        #$currentRoleObject = $currentRoleObject->getRoleInfo();
        $currentRoleValue = printPermsInfo($currentRoleObject->getValue());
        
        echo "<tr><td colspan=\"4\"><strong>Access for Permissions Value of $idx</strong></td></tr>";
        echo "<tr><td colspan=\"4\">$currentRoleValue</td></tr>";
        $ddx = 1;
        for($cdx = 0; $cdx < 12; $cdx++)
        {
          $actual = ($permissionsEngine->requestPermissionForOperation($currentRoleObject->getProjectID(), $currentRoleObject->getUserID(), 2, "Tasks", $ddx, $currentRoleObject) ? "Allowed" :"Disallowed");
          $color = ($actual == "Allowed" ? "success" :"danger");
          
          echo '<tr>';
          echo "<td>$idx</td>";
          echo '<td>'.printPermsName($ddx).'</td>';
          echo "<td>$ddx</td>";
          echo "<td class=\"$color\">$actual</td>";
          echo '</tr>';
          
          $ddx = 2 << $cdx; 
        }
         $bdx = 2 << ($idx%2)+1;    
      }
      
      ?>
        </tbody>
      </table>
  </body>
</html>
