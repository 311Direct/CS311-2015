<?php
require('Direct/config/db.config.php');
require('../permissionsheader.php');

function doSql($sql) {
	// Connect
	$conn = mysqli_connect('localhost', $GLOBALS['DBUSER'], $GLOBALS['DBPASS'], 'vitawebs_csci311_v2');
	
	// Run SQL
	if (is_array($sql)) {
		foreach ($sql as $sqlSingle) {
			$resultObj = $conn->query($sqlSingle);
		}
	} else {
		$resultObj = $conn->query($sql);
	}
	
	// Informative error handling
	if (!$resultObj) {
		error_log('You tried to execute the following SQL, but it had a syntax error(s):<br>' . print_r($sql, true));
		exit(1);
	}
	
	// Get results
	$results = [];
	if ($resultObj === true) return;	// Executed a query that does NOT fetch anything from db, so don't attempt to get the results
	while ($row = $resultObj->fetch_assoc()) {
		$results[] = $row;
	}

	$conn->close();
	return $results;
}

function projMgrsForProj($id) {
	$sql = 'SELECT U.username, U.displayName ' .
		'FROM projectManager PM JOIN user U ON PM.username = U.username ' .
		'WHERE PM.projectId = ' . $id;
	return doSql($sql);
}

function attFor($type, $id) {
	$sql = 'SELECT * ' .
		'FROM attachment ' .
		'WHERE forType = "' . $type . '" ' .
		'AND forId = ' . $id;
	return doSql($sql);
}

function milestonesForProj($id) {
	$sql = 'SELECT * ' .
		'FROM milestone ' .
		'WHERE projectId = ' . $id;
	return doSql($sql);
}

function milestonesForTask($id) {
	$sql = 'SELECT M.id, M.title ' .
		'FROM tasksInMilestone TIM JOIN milestone M ON TIM.milestoneId = M.id ' .
		'WHERE taskId = ' . $id;
	return doSql($sql);
}

function tasksForProj($id) {
	$sql = "SELECT T.id, T.priority, T.title, T.status, GROUP_CONCAT(U.displayName SEPARATOR ', ') AS assignees, T.startDate, T.dueDate " .
		'FROM taskAssignee TA RIGHT JOIN task T ON TA.taskId = T.id ' .
		'LEFT JOIN user U ON TA.username = U.username ' .
		'WHERE projectId = ' . $id . ' ' .
		'GROUP BY T.id';
	return doSql($sql);		
}

function usrHoursForProj($username, $id) {
	$sql = 'SELECT W.username, IFNULL(SUM(W.hours), 0) AS manhours ' .
		'FROM workLog W ' .
		'WHERE W.taskId IN (' .
			'SELECT id ' .
			'FROM task ' .
			'WHERE projectId = ' . $id .
		') ' .
		"AND W.username = '" . $username . "' ";
	$usrHrInfo = doSql($sql)[0];
	$manhours = $usrHrInfo['manhours'];
	return $manhours;
}

function usersWorkingOnProj($id) {
	$sql = 'SELECT DISTINCT U.username, U.displayName ' .
		'FROM taskAssignee TA JOIN task T ON TA.taskId = T.id ' .
		'JOIN user U ON TA.username = U.username ' .
		'WHERE T.projectId = ' . $id;
	$users = doSql($sql);
	for ($uIndex = 0; $uIndex < count($users); $uIndex++) {
		$user = $users[$uIndex];
		$username = $user['username'];
		$users[$uIndex]['manhours'] = usrHoursForProj($username, $id);
	}
	
	// Sort by manhours put in, descending
	usort($users, function($a, $b) {
		return $b['manhours'] > $a['manhours'];
	});
	
	return $users;
}

function commentsFor($type, $id) {
	$sql = 'SELECT U.username, U.displayName, C.datetime, C.comment ' .
		'FROM comment C JOIN user U ON C.username = U.username ' .
		'WHERE forType = "' . $type . '" ' .
		'AND forId = ' . $id . ' ' .
		'ORDER BY datetime ASC';
	return doSql($sql);
}

function tasksForMilestone($id) {
	$sql = 'SELECT T.id, T.priority, T.title, T.projectId, T.status, T.startDate, T.dueDate ' .
		'FROM tasksInMilestone TIM JOIN task T ON TIM.taskId = T.id ' .
		'WHERE TIM.milestoneId = ' . $id;
	return doSql($sql);
}

function manhrsForMilestone($username, $id) {
	$sql = 'SELECT W.username, IFNULL(SUM(W.hours), 0) AS manhours ' .
		'FROM tasksInMilestone TIM RIGHT JOIN workLog W ON TIM.taskId = W.taskId ' .
		'WHERE TIM.taskId IN ( ' .
			'SELECT taskId ' .
		    'FROM tasksInMilestone ' .
		    'WHERE milestoneId = ' . $id . ' ' .
		') ' .
		'AND W.username = "' . $username . '" ' .
		'GROUP BY W.username';
	$manhrs = doSql($sql);
	if (!count($manhrs)) return 0;	// If user has not logged work on a task in this milestone yet, they have spent 0 hours on this milestone
	return $manhrs[0]['manhours'];
}

function assigneesForTask($id) {
	$sql = 'SELECT U.username, U.displayName ' .
		'FROM taskAssignee TA JOIN user U ON TA.username = U.username ' .
		'WHERE TA.taskId = ' . $id;
	return doSql($sql);
}

function flagsForTask($id) {
	$sql = 'SELECT flag ' .
		'FROM taskFlag TF ' .
		'WHERE TF.taskId = ' . $id;
	return doSql($sql);
}

function subtasksForTask($id) {
	$sql = 'SELECT DISTINCT ST.taskId, T.priority, T.title, T.status ' .
		'FROM subtask ST JOIN task T ON ST.taskId = T.id ' .
		'WHERE ST.parentTaskId = ' . $id;
	return doSql($sql);
}

function dependeesForTask($id) {
	$sql = 'SELECT dependeeTaskId AS id ' .
		'FROM taskDependency ' .
		'WHERE taskId = ' . $id . ' ' . 
		'AND dependeeTaskId IS NOT NULL';
	return doSql($sql);
}

function dependantsForTask($id) {
	$sql = 'SELECT dependantTaskId AS id ' .
		'FROM taskDependency ' .
		'WHERE taskId = ' . $id . ' ' .
		'AND dependantTaskId IS NOT NULL';
	return doSql($sql);
}

function permsFor($username) {
	$sql = 'SELECT name ' .
		'FROM permission ' .
		'WHERE roleCode = (' .
			'SELECT roleCode ' .
			'FROM user U ' .
			'WHERE U.username = "' . $username . '" ' .
			'LIMIT 1' .
		')';
	return doSql($sql);	
}

function rolesServedForProj($username, $id) {
	$sql = 'SELECT DISTINCT PERM.role ' .
		'FROM workLog W JOIN task T ON W.taskId = T.id ' .
		'JOIN project P ON T.projectId = P.id ' .
		'JOIN permission PERM ON W.roleCode = PERM.roleCode ' .
		'WHERE P.id = ' . $id . ' ' .
		'AND W.username = "' . $username . '"';
	$rolesRaw = doSql($sql);	// raw = each role contains a nested array (e.g.: [[X], [X], [X]]), we just want an array with a depth of 1 (e.g.: [X, X, X])
	$roles = [];
	foreach ($rolesRaw as $role) {
		$roles[] = $role['role'];
	}
	
	return $roles;
}

function pastProjsFor($username) {
	$sql = 'SELECT DISTINCT P.id, P.title ' .
		'FROM workLog W JOIN task T ON W.taskId = T.id ' .
		'JOIN project P ON T.projectId = P.id ' .
		'WHERE W.username = "' . $username . '"';
	$pastProjs = doSql($sql);
	
	// For each project
	for ($pIndex = 0; $pIndex < count($pastProjs); $pIndex++) {
		// Set project managers
		// Get project manager
		$prj = $pastProjs[$pIndex];
		$mgrs = projMgrsForProj($prj['id']);
		// For each manager
		$pastProjs[$pIndex]['projectManagerUserName'] = [];
		$pastProjs[$pIndex]['projectManagerDisplayName'] = [];
		foreach ($mgrs as $mgr) {
			// Record as a manager
			array_push($pastProjs[$pIndex]['projectManagerUserName'], $mgr['username']);
			array_push($pastProjs[$pIndex]['projectManagerDisplayName'], $mgr['displayName']);
		}
		
		// Set roles served
		$pastProjs[$pIndex]['rolesServed'] = rolesServedForProj($username, $prj['id']);
	}
	
	return $pastProjs;
}

function searchProjs() {
	$crit = $_POST['criteria'];
	$val = $_POST['value'];
	
	$searchingByProjMgrDisName = ($crit == 'projectmanagerdisplayname');
	// Set SQL
	if ($searchingByProjMgrDisName) {
		$sql = 'SELECT id, title, status ' .
			'FROM project';
		$val = strtolower($val);	// Enable case-insensitive searching
	} else {
		$sql = 'SELECT id, title, status ' .
			'FROM project ' .
			'WHERE ' . $crit . " LIKE '%" . $val . "%'";	
	}

	$projs = doSql($sql);
	
	// Init separate resultset, if searching by project manager display name
	$projsForDisName = [];
	
	// Set project managers
	$searchAllProjs = ($searchingByProjMgrDisName && !strlen($val));
	for ($pIndex = 0; $pIndex < count($projs); $pIndex++) {
		$projs[$pIndex]['projectManagerUserNames'] = [];
		$projs[$pIndex]['projectManagerDisplayNames'] = [];	
		
		// For each manager
		$mgrs = projMgrsForProj($projs[$pIndex]['id']);
		foreach ($mgrs as $mgr) {
			$disName = strtolower($mgr['displayName']);
			
			// Record as a manager
			array_push($projs[$pIndex]['projectManagerUserNames'], $mgr['username']);
			array_push($projs[$pIndex]['projectManagerDisplayNames'], $disName);
			
			// If searching by manager AND current display name matches, add project to resultset
			if ($searchAllProjs || $searchingByProjMgrDisName && strpos($disName, $val) !== false) {
				$projsForDisName[] = $projs[$pIndex];
			}			
		}
	}
	
	// If search by manager name, return the right resulset
	if ($searchingByProjMgrDisName)	return $projsForDisName;
	return $projs;
}

function searchMilestones() {
	$crit = $_POST['criteria'];
	$val = $_POST['value'];
	$sql = 'SELECT id, title, status ' .
		'FROM milestone ' .
		'WHERE ' . $crit . ' LIKE "%' . $val . '%"';
	return doSql($sql);
}

function searchTasks() {
	$crit = $_POST['criteria'];
	$val = $_POST['value'];
	$searchingByAssigneeDisName = ($crit == 'assigneedisplayname');
	// Set SQL
	if ($searchingByAssigneeDisName) {
		$sql = 'SELECT id, title, status ' .
			'FROM task';
		$val = strtolower($val);	// Enable case-insensitive searching
	} else {
		$sql = 'SELECT id, title, status ' .
			'FROM task ' .
			'WHERE ' . $crit . " LIKE '%" . $val . "%'";	
	}
	$tasks = doSql($sql);
	
	// Init separate resultset, if searching by project manager display name
	$tasksForDisName = [];
	
	// Set project managers
	$searchAllTasks = ($searchingByAssigneeDisName && !strlen($val));
	for ($tIndex = 0; $tIndex < count($tasks); $tIndex++) {
		$tasks[$tIndex]['assigneeIds'] = [];
		$tasks[$tIndex]['assigneeDisplayNames'] = [];	
		
		// For each assignee
		$asses = assigneesForTask($tasks[$tIndex]['id']);
		foreach ($asses as $ass) {
			$disName = strtolower($ass['displayName']);

			// Record as an asignee
			array_push($tasks[$tIndex]['assigneeIds'], $ass['username']);
			array_push($tasks[$tIndex]['assigneeDisplayNames'], $disName);

			// If searching by assignee name AND current display name matches, add task to resultset
			if ($searchAllTasks || $searchingByAssigneeDisName && strpos($disName, $val) !== false) {
				$tasksForDisName[] = $tasks[$tIndex];
			}
		}
	}
	
	// If search by manager name, return the right resulset
	if ($searchingByAssigneeDisName) return $tasksForDisName;
	return $tasks;
}

function searchUsers() {
	$crit = $_POST['criteria'];
	$val = $_POST['value'];
	$sql = 'SELECT U.username, U.displayName, IFNULL(SUM(W.hours), 0) AS manhours ' .
		'FROM workLog W RIGHT JOIN user U ON W.username = U.username ' .
		'WHERE U.' . $crit . ' LIKE "%' . $val . '%" ' .
		'GROUP BY U.username';
	$users = doSql($sql);
	
	// Convert username & displayName fields to arrays, so loadContent.cvtToHyperlink can be used
	for ($uIndex = 0; $uIndex < count($users); $uIndex++) {
		$username = $users[$uIndex]['username'];
		$users[$uIndex]['username'] = [$username];
		$displayName = $users[$uIndex]['displayName'];
		$users[$uIndex]['displayName'] = [$displayName];
	}
	
	return $users;
}

function addPercentToProg($projs) {
	// Append '%' to end of progress
	for ($pIndex = 0; $pIndex < count($projs); $pIndex++) {
		$projs[$pIndex]['progress'] = $projs[$pIndex]['progress'] . '%';
	}
	return $projs;
}

/*
 * An implicit assumption in the design of
 * this function, is that only project managers
 * can edit users. Hence, this function will
 * only be called for projects, milestones, tasks
 * (as project managers don't send approval requests)
 */
function projMgrFor($type, $id) {
	$res = [];
	if ($type == 'project') {
		$sql = 'SELECT username ' .
			'FROM projectManager PM ' .
			'WHERE PM.projectId = ' . $id;
		$res = doSql($sql);
	} else
if ($type == 'milestone') {
		$sql = 'SELECT username ' .
			'FROM milestone M JOIN projectManager PM ON M.projectId = PM.projectId ' .
			'WHERE M.id = ' . $id;
		$res = doSql($sql);
	} else
if ($type == 'task') {
		$sql = 'SELECT username ' .
			'FROM task T JOIN projectManager PM ON T.projectId = PM.projectId ' .
			'WHERE T.id = ' . $id;
		$res = doSql($sql);
	} else {
		error_log('projMgrFor(): tried to get project managers, but encountered an unknown item type "' . $type . '"');
	}
	return $res;
}

function projIdFor($type, $id) {
	$pId = null;
	if ($type == 'project') {
		$pId = $id;
	} else
if ($type == 'milestone') {
		$sql = 'SELECT M.projectId ' .
			'FROM milestone M ' .
			'WHERE M.id = ' . $id;
		$pId = doSql($sql)[0]['projectId'];
	} else
if ($type == 'task') {
		$sql = 'SELECT T.projectId ' .
			'FROM task T ' .
			'WHERE T.id = ' . $id;
		$pId = doSql($sql)[0]['projectId'];
	} else
if ($type == 'user') {
		// Since only project managers can edit users, skip
		// the process of determine if the user is a project manager.
		// This can be one by just returning an empty array
		$pId = 'EDITING_A_USER';
	} else {
		error_log('projIdFor(): tried to get project id of an item, but encountered an unknown item type "' . $type . '"');	
	}
	return $pId;
}

function isProjMgrForProj($pId) {
	session_start();
	$sql = 'SELECT username ' .
		'FROM projectManager PM ' .
		'WHERE PM.projectId = ' . $pId . ' ' .
		"AND PM.username = '" . $_SESSION['username'] . "'";

	$res = doSql($sql);
	if (count($res)) return true;
	return false;
}

function sqlForTailoredEdit($type, $id, $col, $val, $pId) {
	$sql = [];
	$editCmd = $col;

	// Generate SQL for tailored edit
	if ($editCmd == 'TAILORED_projectManagers') {
		// Clear current managers
		$sql[] = 'DELETE FROM projectManager ' .
			"WHERE projectId = $pId";
			
		// Assign new managers
		$mgrs = explode(',', $val);
		foreach ($mgrs as $mgr) {
			$sql[] = 'INSERT INTO projectManager (projectId, username) ' .
				"VALUES ($pId, '$mgr')";
		}
	} else
if ($editCmd == 'TAILORED_milestones') {
		// Put current milestones under no project. To implement
		// this in a time efficient way, just set their
		// projectIds to a project that will likely never exist
		$sql[] = 'UPDATE milestone ' .
			'SET projectId = 9999 ' .
			"WHERE projectId = $pId";
		
		// Assign new milestones
		$mIds = explode(',', $val);
		foreach ($mIds as $mId) {
			$sql[] = 'UPDATE milestone ' .
				"SET projectId = $pId " .
				"WHERE id = $mId";
		}
	} else
if ($editCmd == 'TAILORED_tasks') {
		// Put current tasks under no project. To implement
		// this in a time efficient way, just set their
		// projectIds to a project that will likely never exist
		$sql[] = 'UPDATE task ' .
			"SET projectId = 9999 " .
			"WHERE projectId = $pId";
		
		// Assign new tasks
		$tIds = explode(',', $val);
		foreach ($tIds as $tId) {
			$sql[] = 'UPDATE task ' .
				"SET projectId = $pId " .
				"WHERE id = $tId";
		}
	} else
if ($editCmd == 'TAILORED_tasksInMilestone') {
		// Clear current tasks in milestone
		$sql[] = 'DELETE FROM tasksInMilestone ' .
			"WHERE milestoneId = $id";
		
		// Assign new tasks to milestone
		$tIds = explode(',', $val);
		foreach ($tIds as $tId) {
			$sql[] = 'INSERT INTO tasksInMilestone (taskId, milestoneId) ' .
				"VALUES ($tId, $id)";
		}
	} else
if ($editCmd == 'TAILORED_assignees') {
		// Clear current assignes to task
		$sql[] = 'DELETE FROM taskAssignee ' .
			"WHERE taskId = $id";
		
		// Assign new assignees to task
		$usernames = explode(',', $val);
		foreach ($usernames as $username) {
			$sql[] = 'INSERT INTO taskAssignee (taskId, username) ' .
				"VALUES ($id, '$username')";
		}
	} else
if ($editCmd == 'TAILORED_flags') {
		// Clear current flags
		$sql[] = 'DELETE FROM taskFlag ' .
			"WHERE taskId = $id";
		
		// Add new flags
		$flags = explode(',', $val);
		foreach($flags as $flag) {
			$sql[] = 'INSERT INTO taskFlag (taskId, flag) ' .
				"VALUES ($id, '$flag')";
		}
	} else
if ($editCmd == 'TAILORED_subtasks') {
		// Clear current subtasks
		$sql[] = 'DELETE FROM subtask ' .
			"WHERE parentTaskId = $id";
		
		// Assign new subtasks
		$subTIds = explode(',', $val);
		foreach ($subTIds as $subTId) {
			$sql[] = 'INSERT INTO subtask (taskId, parentTaskId) ' .
				"VALUES ($subTId, $id)";
		}
	} else
if ($editCmd == 'TAILORED_milestonesThatTaskBelongsTo') {
		// Clear current milestones that this task belongs to
		$sql[] = 'DELETE FROM tasksInMilestone ' .
			"WHERE taskId = $id";
	
		// Record the new milestones that this task belongs to
		$mIds = explode(',', $val);
		foreach ($mIds as $mId) {
			$sql[] = 'INSERT INTO tasksInMilestone (taskId, milestoneId) ' .
				"VALUES ($id, $mId)";
		}
	} else
if ($editCmd == 'TAILORED_ROLE') {
		$sql[] = 'UPDATE user ' .
			"SET roleCode = '$val' " .
			"WHERE username = '$id'";
	} else {
		error_log("sqlForTailoredEdit(): attempted to generated SQL for a tailored edit, but encountered an unexpected edit command");
	}

	return $sql;
}

function cvtTmFrmt($item) {
	$mStart = DateTime::createFromFormat('Y-m-d', $item['startDate']);
	$item['startDate'] = $mStart->format('d-m-Y');
	$mDue = DateTime::createFromFormat('Y-m-d', $item['dueDate']);
	$item['dueDate'] = $mDue->format('d-m-Y');
	return $item;
}

function ganttDur($item) {
	$due = new DateTime($item['dueDate']);
	$start = new DateTime($item['startDate']);
	return $due->diff($start)->format("%a");
}

function projsBelongingToFor($username) {
	$sql = 'SELECT projectId ' .
		'FROM userBelongsToProject ' .
		"WHERE username = '$username'";
	return doSql($sql);
}

function workLogFor($id) {
	session_start();
	$sql = 'SELECT hours, datetime ' .
		'FROM workLog ' .
		'WHERE taskId = ' . $id . ' ' .
		"AND username = '$_SESSION[username]' " .
		'ORDER BY datetime DESC';	
	return doSql($sql);
}

function usrRoleCode() {
	session_start();
	$sql = 'SELECT roleCode ' .
		'FROM user ' .
		"WHERE username = '$_SESSION[username]'";
	return doSql($sql)[0]['roleCode'];
}

function projForMilestone($id) {
	$sql = 'SELECT projectId ' .
		'FROM milestone ' .
		"WHERE id = $id";
	return doSql($sql)[0];
}

function projForTask($id) {
	$sql = 'SELECT projectId ' .
		'FROM task ' .
		"WHERE id = $id";
	return doSql($sql)[0];
}

function milestoneForTask($id) {
	$sql = 'SELECT milestoneId ' .
		'FROM tasksInMilestone ' .
		"WHERE taskId = $id";
	return doSql($sql)[0];
}

function bCrumbsFor($pg, $id, $rolePermissions) {
	$bCrumbs = [];
  global $PERMISSIONS_ENGINE;
  
	// If project manager, add "project list" to beginning of breadcrumbs.
	// Otherwise, add "task list" to beginning of breadcrumbs.
	$role = usrRoleCode();
	if($PERMISSIONS_ENGINE->canCompleteRequestForOperation($rolePermissions->getRoleInfo()->getPermissionsValue(), P_FULL_CONTROL))
	{
		$bCrumbs[] = [
			'label' => 'Project list',
			'url' => 'project-list.php'
		];
	} else {
		$bCrumbs[] = [
			'label' => 'Task list',
			'url' => 'task-list.php'
		];			
	}

	// Generate breadcrumbs (N.B.: don't need to check project-list page, because it's already done above)
	$pageHasId = is_numeric($id);
	if ($pg == 'task-list') {
		$bCrumbs[] = [
			'label' => 'task list',
			'url' => 'task-list.php'
		];
	} else
if ($pg == 'project' && $pageHasId) {
		// Page type is 'project' for (1) project-list.php (2) project-details.php (3) project-visualization.php
		// (1) will have ID = string, whilst (2) and (3) will have ID = integer, so
		// only add a breadcrumb if on 'project-details.php' & 'project-visualization.php' page
		$bCrumbs[] = [
			'label' => "P-$id",
			'url' => "project-details.php?id=$id"
		];
	} else
if ($pg == 'milestone') {
		$pId = projForMilestone($id)['projectId'];
		$bCrumbs[] = [
			'label' => "P-$pId",
			'url' => "project-details.php?id=$pId"
		];
		$bCrumbs[] = [
			'label' => "M-$id",
			'url' => "milestone-details.php?id=$id"
		];
	} else
if ($pg == 'task' && $pageHasId) {
		// Page type is 'task' for (1) task-list.php (2) task-details.php
		// (1) will have ID = string, whilst (2) will have ID = integer, so
		// only add a breadcrumb if on 'task-details.php'
		$pId = projForTask($id)['projectId'];
		$mId = milestoneForTask($id)['milestoneId'];
		$bCrumbs[] = [
			'label' => "P-$pId",
			'url' => "project-details.php?id=$pId"
		];
		$bCrumbs[] = [
			'label' => "M-$mId",
			'url' => "milestone-details.php?id=$mId"
		];
		$bCrumbs[] = [
			'label' => "T-$id",
			'url' => "task-details.php?id=$id"
		];	
	} else
if ($pg == 'user') {
		$bCrumbs[] = [
			'label' => "$id details",
			'url' => "user-details.php?id=$id"
		];
	} else
if ($pg == 'search.ph') {	// The search page should actually return 'search'. Recommended to fix the bug & refactor this code in the future
		$bCrumbs[] = [
			'label' => "Search",
			'url' => "search.php"
		];
	} else
if ($pg == 'effort') {
		$bCrumbs[] = [
			'label' => "Effort estimation",
			'url' => "effort-estimation.php?id=$id"
		];
	} else {
		if ($pageHasId) error_log('bCrumbs(): attempted to generate breadcrumbs, but encountered an unknown page type');
	}
	
	// Generate hyperlinks
	for ($bIndex = 0; $bIndex < count($bCrumbs); $bIndex++) {
		$url = $bCrumbs[$bIndex]['url'];	
		$label = $bCrumbs[$bIndex]['label'];
		$bCrumbs[$bIndex] = "<a href=\"$url\">$label</a>";
	}
	
	return implode(' > ', $bCrumbs);
}

function costPerHrForTask($id) {
	$sql = 'SELECT (allocatedBudget / allocatedTime) AS costPerHr ' .
		'FROM task ' .
		"WHERE id = $id";
	$costPerHr = doSql($sql)[0]['costPerHr'];
	return floatVal($costPerHr);
}

header("Content-Type: application/json");

$act = $_POST['action'];
IF ($act == 'TASK_LIST') {
	session_start();
	$username = $_SESSION['username'];

	// Get tasks assigned to a user
	$sql = 'SELECT DISTINCT T.id AS id, T.priority AS priority, T.title AS taskTitle, P.title AS projectTitle, T.status AS status ' .
		'FROM taskAssignee TA ' . 
		'JOIN task T ON TA.taskId = T.id ' .
		'JOIN project P ON T.projectId = P.id ' .
		'WHERE TA.username = "' . $username . '"';
	$payload['assignedToUser'] = doSql($sql);

	// Get all tasks
	$sql = 'SELECT DISTINCT T.id AS id, T.priority AS priority, T.title AS taskTitle, P.title AS projectTitle, T.status AS status ' .
		'FROM taskAssignee TA ' . 
		'JOIN task T ON TA.taskId = T.id ' .
		'JOIN project P ON T.projectId = P.id';
	$payload['all'] = doSql($sql);

	echo json_encode([
		'action' => $act,
		'payload' => $payload
	]);
} else
if ($act == 'PROJECT_LIST_I_AM_MANAGING') {
	session_start();
	$username = $_SESSION['username'];
	$sql = 'SELECT DISTINCT P.id AS id, P.title AS title, P.progress AS progress ' .
		'FROM projectManager PM JOIN project P ON PM.projectId = P.id ' .
		'JOIN user U ON PM.username = U.username ' .
		'WHERE PM.username = "' . $username . '"';
	$payload = doSql($sql);
	
	// Set project managers
	for ($pIndex = 0; $pIndex < count($payload); $pIndex++) {
		$proj = $payload[$pIndex];
		$pId = $proj['id'];
		$mgrs = projMgrsForProj($pId);
		$payload[$pIndex]['managerUsernames'] = [];
		$payload[$pIndex]['managerDisplayNames'] = [];
		
		foreach ($mgrs as $mgr) {
			array_push($payload[$pIndex]['managerUsernames'], $mgr['username']);
			array_push($payload[$pIndex]['managerDisplayNames'], $mgr['displayName']);
		} 
	}
	
	$payload = addPercentToProg($payload);
	
	echo json_encode([
		'action' => $act,
		'payload' => $payload
	]);
} else
if ($act == 'PROJECT_LIST_ALL') {
	session_start();
	$username = $_SESSION['username'];
	$sql = 'SELECT DISTINCT P.id AS id, P.title AS title, P.progress AS progress ' .
		'FROM projectManager PM RIGHT JOIN project P ON PM.projectId = P.id ' .
		'LEFT JOIN user U ON PM.username = U.username';
	$payload = doSql($sql);
	
	// Set project managers
	for ($pIndex = 0; $pIndex < count($payload); $pIndex++) {
		$proj = $payload[$pIndex];
		$pId = $proj['id'];
		$mgrs = projMgrsForProj($pId);
		$payload[$pIndex]['managerUsernames'] = [];
		$payload[$pIndex]['managerDisplayNames'] = [];
		
		foreach ($mgrs as $mgr) {
			array_push($payload[$pIndex]['managerUsernames'], $mgr['username']);
			array_push($payload[$pIndex]['managerDisplayNames'], $mgr['displayName']);
		} 
	}
	
	$payload = addPercentToProg($payload);
	
	echo json_encode([
		'action' => $act,
		'payload' => $payload
	]);
} else
if ($act == 'PROJECT_GET') {
	$id = $_POST['id'];
	$sql = 'SELECT P.title, P.creatorUserId, U_CREATOR.displayName AS creatorDisplayName, P.createdDate, P.dateStart, P.dateExpectedFinish, P.dateFinished, P.status, P.allocatedBudget, P.usedBudget, P.allocatedTime, P.usedTime, P.description ' .
		'FROM projectManager PM RIGHT JOIN project P ON PM.projectId = P.id ' .
		'LEFT JOIN user U ON PM.username = U.username ' .
		'LEFT JOIN user U_CREATOR ON P.creatorUserId = U_CREATOR.username ' .
		'WHERE P.id = ' . $id;
	$project = doSql($sql)[0];
	
	$project['attachments'] = attFor('project', $id);
	$project['milestones'] = milestonesForProj($id);
	$project['tasks'] = tasksForProj($id);
	$project['users'] = usersWorkingOnProj($id);
	$project['comments'] = commentsFor('project', $id);
	
	// Set managers
	$project['projectManagerUserIds'] = [];
	$project['projectManagerDisplayNames'] = [];
	$mgrs = projMgrsForProj($id);
	
	foreach($mgrs as $mgr) {
		array_push($project['projectManagerUserIds'], $mgr['username']);
		array_push($project['projectManagerDisplayNames'], $mgr['displayName']);
	}

	echo json_encode([
		'action' => $act,
		'payload' => $project
	]);
} else
if ($act == 'PROJECT_EDIT') {
	echo json_encode([
		'action' => $act,
		'payload' => 1
	]);
} else
if ($act == 'PROJECT_ATTACH_DELIVERABLE') {
	echo json_encode([
		'action' => 'PROJECT_ATTACH_DELIVERABLE',
		'payload' => 1
	]);
} else
if ($act == 'PROJECT_ASSIGN_TASK') {
	echo json_encode([
		'action' => $act,
		'payload' => 1
	]);
} else
if ($act == 'COMMENT_PUBLISH') {
	session_start();
	$sql = 'INSERT INTO comment (username, datetime, comment, forType, forId) ' .
		"VALUES ('$_SESSION[username]', NOW(), '$_POST[comment]', '$_POST[forType]', '$_POST[forId]')";
	doSql($sql);

	echo json_encode([
		'action' => $act,
		'payload' => 1
	]);
} else
if ($act == 'MILESTONE_GET') {
	$id = $_POST['id'];
	$sql = 'SELECT M.id, M.title, M.creatorUsername, U.displayName, M.createdDate, M.startDate, M.dueDate, M.endDate, M.projectId, M.status, M.allocatedBudget, M.usedBudget, M.allocatedTime, M.usedTime, M.description ' .
		'FROM milestone M JOIN user U ON M.creatorUsername = U.username ' .
		'WHERE M.id = ' . $id;
	$milestone = doSql($sql)[0];
	
	// Set milestone managers
	$mgrs = projMgrsForProj($milestone['projectId']);
	$milestone['managerUserNames'] = [];
	$milestone['managerDisplayNames'] = [];
	for ($mIndex = 0; $mIndex < count($mgrs); $mIndex++) {
		array_push($milestone['managerUserNames'], $mgrs[$mIndex]['username']);
		array_push($milestone['managerDisplayNames'], $mgrs[$mIndex]['displayName']);
	}

	// Set tasks & assignees to a milestone's tasks
	$tasks = tasksForMilestone($id);
	$milestone['tasks'] = $tasks;
	$users = [];
	for ($tIndex = 0; $tIndex < count($tasks); $tIndex++) {
		$task = $tasks[$tIndex];
		$users = assigneesForTask($task['id']);			
		foreach ($users as $user) {
			// Record the user as working in this milestone, if they haven't been added yet
			if (in_array($user, $users)) continue;
			array_push($users, $user);
		}
	}
	// Set the manhours that each user in this milestone has worked (for the milestone's tasks)
	$mId = $milestone['id'];
	for ($uIndex = 0; $uIndex < count($users); $uIndex++) {
		$username = $users[$uIndex]['username'];
		$users[$uIndex]['manhours'] = manhrsForMilestone($username, $mId);
	}
	// Sort users by manhours put in, descending
	usort($users, function($a, $b) {
		return $b['manhours'] > $a['manhours'];
	});
	$milestone['users'] = $users;

	echo json_encode([
		'action' => $act,
		'payload' => $milestone
	]);
} else
if ($act == 'MILESTONE_EDIT') {
	echo json_encode([
		'action' => $act,
		'payload' => 1
	]);
} else
if ($act == 'MILESTONE_ASSIGN_TASK') {
	echo json_encode([
		'action' => $act,
		'payload' => 1
	]);
} else
if ($act == 'TASK_GET') {
	$id = $_POST['id'];
	$sql = 'SELECT T.title, P.Id AS projectId, P.title AS projectTitle, T.priority, T.status, T.allocatedBudget, T.usedBudget, T.allocatedTime, T.usedTime, T.startDate, T.dueDate, T.endDate, T.description, T.id ' .
		'FROM task T JOIN project P ON T.projectId = P.id ' .
		'WHERE T.id = ' . $id;
	$task = doSql($sql)[0];
	
	// Set milestones this task belongs to
	$task['milestones'] = milestonesForTask($id);
	
	// Set assignees
	$task['assigneeUsernames'] = [];
	$task['assigneeDisplayNames'] = [];
	$assignees = assigneesForTask($id);
	foreach ($assignees as $ass) {
		array_push($task['assigneeUsernames'], $ass['username']);
		array_push($task['assigneeDisplayNames'], $ass['displayName']);
	}

	$task['flags'] = flagsForTask($id);
	$task['attachments'] = attFor('task', $id);
	$task['comments'] = commentsFor('task', $id);

	// Set work logged by viewing user
	$task['workLog'] = workLogFor($id);

	// Set subtasks
	$subtasks = subtasksForTask($id);
	for ($sIndex = 0; $sIndex < count($subtasks); $sIndex++) {
		$subTId = $subtasks[$sIndex]['taskId'];
		$asses = assigneesForTask($subTId);
		
		// For each assignee
		$subtasks[$sIndex]['assigneeUsernames'] = [];
		$subtasks[$sIndex]['assigneeDisplayNames'] = [];
		foreach ($asses as $ass) {
			// Mark user as assigned to this subtask
			array_push($subtasks[$sIndex]['assigneeUsernames'], $ass['username']);
			array_push($subtasks[$sIndex]['assigneeDisplayNames'], $ass['displayName']);
		}
	}
	$task['subtasks'] = $subtasks;
	
	// Set the task's dependencies
	$task['dependeeIds'] = dependeesForTask($id);
	$task['dependantIds'] = dependantsForTask($id);
	
	echo json_encode([
		'action' => $act,
		'payload' => $task
	]);
} else
if ($act == 'TASK_EDIT') {
	echo json_encode([
		'action' => $act,
		'payload' => 1
	]);
} else
if ($act == 'TASK_WATCH') {
	echo json_encode([
		'action' => $act,
		'payload' => 1
	]);
} else
if ($act == 'TASK_ATTACH_FILE') {
	echo json_encode([
		'action' => $act,
		'payload' => 1
	]);
} else
if ($act == 'TASK_ASSIGN_SUBTASK') {
	echo json_encode([
		'action' => $act,
		'payload' => 1
	]);	
} else
if ($act == 'TASK_LOG_WORK') {	
	$id = $_POST['id'];
	$time = intVal($_POST['hours']);
	$costPerHr = costPerHrForTask($id);
	$usedBudget = ($costPerHr * $time);

	// Get rolecode
	session_start();
	$sql = 'SELECT roleCode ' .
		'FROM user ' .
		"WHERE username = '$_SESSION[username]'";
	$roleCode = doSql($sql)[0]['roleCode'];

	// Log work
	$sql = 'INSERT INTO workLog (username, taskId, hours, datetime, roleCode) ' .
		"VALUES ('$_SESSION[username]', $id, $time, NOW(), '$roleCode')";
	doSql($sql);
	
	// Update used resources for task
	$sql = 'UPDATE task ' .
		"SET usedBudget = usedBudget + $usedBudget, " .
		"usedTime = usedTime + $time " .
		"WHERE id = $id";
	doSql($sql);
	
	// For each milestone that this task belongs in
	$milestones = milestonesForTask($id);
	foreach ($milestones as $milestone) {
		// Update used resources
		$mId = $milestone['id'];
		$sql = 'UPDATE milestone ' .
			"SET usedBudget = usedBudget + $usedBudget, " .
			"usedTime = usedTime + $time " .
			"WHERE id = $mId";
		doSql($sql);
		
		// Update used resources for project of this milestone
		$pId = projForMilestone($mId)['projectId'];
		$sql = 'UPDATE project ' .
			"SET usedBudget = usedBudget + $usedBudget, " .
			"usedTime = usedTime + $time " .
			"WHERE id = $pId";
		doSql($sql);
	// Endfor
	}
	
	echo json_encode([
		'action' => $act,
		'payload' => 1
	]);
} else
if ($act == 'USER_GET') {
	$username = $_POST['username'];
	$sql = "SELECT U.displayName, U.username, U.expertise, IFNULL(P.role, 'unknown') AS role " .
		'FROM user U LEFT JOIN permission P ON U.roleCode = P.roleCode ' .
		'WHERE U.username = "' . $username . '" ' .
		'LIMIT 1';

	$user = doSql($sql)[0];
	$user['belongsToProjects'] = projsBelongingToFor($username);
	$user['permissions'] = permsFor($username);
	$user['pastProjects'] = pastProjsFor($username);

	echo json_encode([
		'action' => $act,
		'payload' => $user
	]);

} else
if ($act == 'USER_EDIT') {
	echo json_encode([
		'action' => $act,
		'payload' => 1
	]);
} else
if ($act == 'SEARCH_PROJECTS') {
	echo json_encode([
		'action' => $act,
		'payload' => searchProjs()
	]);
} else
if ($act == 'SEARCH_MILESTONES') {
	echo json_encode([
		'action' => $act,
		'payload' => searchMilestones()
	]);
} else
if ($act == 'SEARCH_TASKS') {
	echo json_encode([
		'action' => $act,
		'payload' => searchTasks()
	]);
} else
if ($act == 'SEARCH_USERS') {
	echo json_encode([
		'action' => $act,
		'payload' => searchUsers()
	]);
} else
if ($act == 'LOGIN') {
	$mysqli = new mysqli('localhost', $GLOBALS['DBUSER'], $GLOBALS['DBPASS'], 'vitawebs_csci311_v2');
	$result = $mysqli->query('SELECT username, roleCode ' .
		'FROM user ' .
		'WHERE username = "' . $_POST['username'] . '" AND password = "' . $_POST['password'] . '"');
	$loginSuccess = ($result->num_rows >= 1);
	
	$user = $result->fetch_assoc();
	if ($loginSuccess && session_start()) {
		$_SESSION['username'] = $user['username'];
	} else {
		$loginSuccess = 0;
	}
	$mysqli->close();
		
	// Tell client-side if login was successful
	echo json_encode([
		'action' => $act,
		'payload' => [
			'success' => $loginSuccess,
			'roleCode' => $user['roleCode']
		]
	]);	
} else
if ($act == 'LOGOUT') {
	session_start();
	session_unset();
	echo json_encode([
		'action' => $act,
		'payload' => 1
	]);
} else
if ($act == 'PROJECT_MANAGERS_GET') {
	echo json_encode([
		'action' => $act,
		'payload' => [
			[
				'displayName' => 'Michael Nguyen',
				'username' => 'michaeln'
			],
			[
				'displayName' => 'Chrissy Banks',
				'username' => 'chrissyb'
			],
			[
				'displayName' => 'Jessy Jackson',
				'username' => 'jessyj'
			],
			[
				'displayName' => 'Emma stozer',
				'username' => 'emmas'
			],
			[
				'displayName' => 'Julian Crane',
				'username' => 'julianc'
			]								
		]
	]);
} else
if ($act == 'PERMISSIONS_GET') {	
	echo json_encode([
		'action' => $act,
		'payload' => [
			'Create, edit & delete Project plans',
			'Create, edit & delete Milestones',
			'Create, edit & delete Tasks',
			'Create, edit & delete Users',
			'Create, edit & delete user-defined roles',
			'Watch tasks',			
			'Message project managers',
			'Message development managers',
			'Message SMEs',
			'Message FAs',
			'Message SAs',
			'Message dev. leads',
			'Message developers',
			'Message QAs',
			'Message deployment specialists',
			'Message training specialists',
			'Send approval requests',
			'Accept & reject approval requests',
			'View project list page',
			'View projects they are managing'
		]
	]);
} else
if ($act == 'ROLES_GET') {
	echo json_encode([
		'action' => $act,
		'payload' => [
			'Project manager',
			'Development manager',
			'Subject Matter Expert',
			'Functional Analyst',
			'Solutions Architect',
			'Developer lead',
			'Developer',
			'Quality Assurance',
			'Deployment specialist',
			'Training specialist',
		]
	]);
} else
if ($act == 'PROJECT_CREATE') {
	session_start();
	
	// Create project
	$sql = 'INSERT INTO project (' .
		'id,' .
		'title,' .
		'dateStart,' .
		'dateExpectedFinish,' .
		'dateFinished,' .
		'allocatedBudget,' .
		'allocatedTime,' .
		'usedBudget,' .
		'usedTime,' .
		'description,' .
		'creatorUserId,' .
		'createdDate,' .
		'status,' .
		'progress' .
	') VALUES (' .
		'NULL,' .
		"'$_POST[title]', " .
		"'$_POST[dateStart]', " .
		"'$_POST[dateExpectedFinish]', " .
		"'$_POST[dateFinished]', " .
		$_POST['allocatedBudget'] . ',' .
		$_POST['allocatedTime'] . ',' .
		'0,' .
		'0,' .
		"'$_POST[description]', " .
		"'$_SESSION[username]', " .
		'NOW(),' .
		"'$_POST[status]', " .
		'0' .
	')';
	doSql($sql);

	// Get the id of the project just created
	$sql = 'SELECT id ' .
		'FROM project ' .
		'ORDER BY id DESC ' .
		'LIMIT 1';
	$id = doSql($sql)[0]['id'];
	
	// Create project managers
	$mgrs = explode(',', $_POST['projectManagerUsernames']);

	foreach ($mgrs as $mgr) {
		$sql = 'INSERT INTO projectManager (projectId, username) ' .
			"VALUES ($id, '$mgr')";
		doSql($sql);
	}

	// Record the milestones for this new project
	$mIds = $_POST['projectMilestones'];
	if (strlen(trim($mIds)) > 0) {
		$sql = 'UPDATE milestone ' .
			'SET projectId = ' . $id . ' ' .
			'WHERE id IN (' . $mIds . ')';
		doSql($sql);
	}
	
	echo json_encode([
		'action' => $act,
		'payload' => [
			'forType' => 'project',
			'forId' => $id
		]
	]);
} else
if ($act == 'MILESTONE_CREATE') {
	session_start();

	// Create milestone
	$sql = 'INSERT INTO milestone (' .
		'id, ' .
		'title, ' .
		'projectid, ' .
		'allocatedBudget, ' .
		'allocatedTime, ' .
		'usedBudget, ' .
		'usedTime, ' .
		'description, ' .
		'creatorUsername, ' .
		'createdDate, ' .
		'startDate, ' .
		'dueDate, ' .
		'endDate, ' .
		'status' .
	') VALUES (' .
		'NULL, ' .
		"'$_POST[title]', " .
		"$_POST[projectId], " .
		"$_POST[allocatedBudget], " .
		"$_POST[allocatedTime], " .
		'0, ' .
		'0, ' .
		"'$_POST[description]', " .
		"'$_SESSION[username]', " .
		'NOW(), ' .
		"'$_POST[startDate]', " .		
		"'$_POST[dueDate]', " .
		"'$_POST[endDate]', " .
		"'$_POST[status]'" .
	')';

	doSql($sql);

	// Get the id of the milestone just created
	$sql = 'SELECT id ' .
		'FROM milestone ' .
		'ORDER BY id DESC ' .
		'LIMIT 1';
	$id = doSql($sql)[0]['id'];

	// Set the tasks in this new milestone
	$tIds = explode(',', $_POST['milestoneTasks']);
	foreach ($tIds as $tId) {
		$sql = 'INSERT INTO tasksInMilestone (taskId, milestoneId) ' .
			"VALUES ($tId, $id)";
		doSql($sql);
	}
	
	echo json_encode([
		'action' => $act,
		'payload' => [
			'forType' => 'milestone',
			'forId' => $id
		]
	]);
} else
if ($act == 'TASK_CREATE') {
	// Create milestone
	$sql = 'INSERT INTO task (' .
		'id, ' .
		'priority, ' .
		'title, ' .
		'projectId, ' .
		'status, ' .
		'allocatedBudget, ' .
		'allocatedTime, ' .
		'usedBudget, ' .
		'usedTime, ' .
		'startDate, ' .
		'dueDate, ' .
		'endDate, ' .
		'description' .
	') VALUES (' .
		'NULL, ' .
		"'$_POST[priority]', " .
		"'$_POST[title]', " .
		"$_POST[projectId], " .
		"'$_POST[status]', " .
		"$_POST[allocatedBudget], " .
		"$_POST[allocatedTime], " .
		'0, ' .
		'0, ' .
		"'$_POST[startDate]', " .
		"'$_POST[dueDate]', " .
		"'$_POST[endDate]', " .
		"'$_POST[description]'" .
	')';

	doSql($sql);

	// Get the id of the task just created
	$sql = 'SELECT id ' .
		'FROM task ' .
		'ORDER BY id DESC ' .
		'LIMIT 1';
	$id = doSql($sql)[0]['id'];

	// Set assignees
	$asses = explode(',', $_POST['assigneeUserIds']);
	foreach ($asses as $ass) {
		$sql = 'INSERT INTO taskAssignee (taskId, username) ' .
			'VALUES (' . $id . ", '" . $ass . "')";
		doSql($sql);
	}

	// Set flags
	$flags = explode(',', $_POST['flags']);
	foreach ($flags as $flag) {
		$sql = 'INSERT INTO taskFlag (taskId, flag) ' .
			'VALUES (' . $id . ", '" . $flag . "')";
		doSql($sql);
	}

	// Set subtasks
	$subtasks = explode(',', $_POST['subTaskIds']);
	foreach ($subtasks as $sT) {
		$sql = 'INSERT INTO subtask (taskId, parentTaskId) ' .
			'VALUES (' . $sT . ', ' . $id .')';
		doSql($sql);
	}
	
	// Set dependencies
	$dependees = explode(',', $_POST['dependeeIds']);
	foreach($dependees as $dep) {
		$sql = 'INSERT INTO taskDependency (taskId, dependeeTaskId) ' .
			'VALUES (' . $id . ', ' . $dep . ')';
		doSql($sql);
	}
	$dependants = explode(',', $_POST['dependantIds']);
	foreach ($dependants as $dep) {
		$sql = 'INSERT INTO taskDependency (taskId, dependantTaskId) ' .
			'VALUES (' . $id . ', ' . $dep . ')';
		doSql($sql);
	}

	// Set the milestones that this new task belongs to
	$mIds = explode(',', $_POST['taskMilestones']);
	foreach ($mIds as $mId) {
		$sql = 'INSERT INTO tasksInMilestone (taskId, milestoneId) ' .
			"VALUES ($id, $mId)";
		doSql($sql);
	}

	echo json_encode([
		'action' => $act,
		'payload' => [
			'forType' => 'task',
			'forId' => $id
		]
	]);
} else
if ($act == 'USER_CREATE') {
	session_start();

	// Get role code for a given role
	$sql = 'SELECT roleCode ' .
		'FROM permission ' .
		"WHERE role = '" . $_POST['role'] . "'";
	$roleCode = doSql($sql)[0]['roleCode'];

	// Create milestone
	$sql = 'INSERT INTO user ( ' .
		'username, ' .
		'displayName, ' .
		'password, ' .
		'expertise, ' .
		'roleCode' .
	') VALUES (' .
		"'$_POST[username]', " . 
		"'$_POST[displayName]', " . 
		"'$_POST[password]', " . 
		"'$_POST[expertise]', " . 
		"'$roleCode'" .
	')';
	doSql($sql);

	// Get the username of the user just created
	$sql = 'SELECT username ' .
		'FROM user ' .
		'ORDER BY id DESC ' .
		'LIMIT 1';		
	$username = doSql($sql)[0]['username'];

	// Set the projects that this user belongs to
	$pIds = explode(',', $_POST['userProjects']);
	foreach ($pIds as $pId) {
		$sql = 'INSERT INTO userBelongsToProject (username, projectId) ' .
			"VALUES ('$username', $pId)";
		doSql($sql);
	}
	
	echo json_encode([
		'action' => $act,
		'payload' => [
			'forType' => 'user',
			'forId' => $username
		]
	]);
} else
if ($act == 'EDIT') {
	/*
	 * An implicit assumption in the design of
	 * this code block, is that users (that are
	 * not project managers) can only edit
	 * projects, milestones & tasks.
	 */
	$type = $_POST['for-type'];
	$id = $_POST['for-id'];
	$col = $_POST['for-column'];
	$val = $_POST['value'];

	$pId = projIdFor($type, $id);
	$editingAUser = ($pId == 'EDITING_A_USER');

	if ($editingAUser) {
		$isProjMgr = true;
		$sql = "UPDATE $type " .
			"SET $col = '$val' " .
			"WHERE username = '$id'";
	} else {
		$isProjMgr = isProjMgrForProj($pId);
		$sql = "UPDATE $type " .
			"SET $col = '$val' " .
			"WHERE id = '$id'";
	}
	
	if (strpos($col, 'TAILORED_') !== false) $sql = sqlForTailoredEdit($type, $id, $col, $val, $pId);

	if ($isProjMgr) {
		// No need for approval requests, just update the item
		doSql($sql);
		
		// For edited tasks, send watch alert
		if ($type == 'task') {
			require_once('notificationSystemAPI.php');
			$ns = new notificationSystem();
			$ns->taskChanged($id);
		}
	} else {
		// Send an approval request
		require_once('notificationSystemAPI.php');
		$ns = new notificationSystem();
		$pjMgr = projMgrFor($type, $id);
		$ns->sendApprovalReq($pjMgr, $type, $id, $col, $sql);
	}
		
	echo json_encode([
		'action' => $act,
		'payload' => [
			'column' => $col,
			'isProjMgr' => $isProjMgr,
			'success' => 1
		]
	]);
} else
if ($act == 'GANTT') {
	$payload = [];
	$milestones = milestonesForProj($_POST['id']);
	foreach($milestones as $milestone) {		
		// Convert date formats & calculate duration, so that it can be shown in Gantt chart
		$milestone['duration'] = ganttDur($milestone);
		$milestone = cvtTmFrmt($milestone);
		
		$mId = $milestone['id'];
		$tasks = tasksForMilestone($mId);
		for ($tIndex = 0; $tIndex < count($tasks); $tIndex++) {
			$tasks[$tIndex]['duration'] = ganttDur($tasks[$tIndex]);		
			$tasks[$tIndex] = cvtTmFrmt($tasks[$tIndex]);
		}
		
		$milestone['tasks'] = $tasks;
		$payload[] = $milestone;
	}

	echo json_encode([
		'action' => $act,
		'payload' => $payload
	]);
} else
if ($act == 'PERT_GET') {
	// Set project start date
	$id = $_POST['id'];
	$sql = 'SELECT dateStart ' .
	  'FROM project ' .
		'WHERE id = ' . $id;
	$proj = doSql($sql)[0];
	$projStartDate = $proj['dateStart'];
	
	// Set tasks
	$tasks = tasksForProj($id);
	for ($tIndex = 0; $tIndex < count($tasks); $tIndex++) {
		$task = $tasks[$tIndex];
		$tId = $task['id'];
		$tasks[$tIndex]['duration'] = ganttDur($task);
		$tasks[$tIndex]['left'] = dependeesForTask($tId);
		$tasks[$tIndex]['right'] = dependantsForTask($tId);
	}

	echo json_encode([
	  'action' => $act,
	  'payload' => [
	    'projectStartDate' => $projStartDate,
	    'tasks' => $tasks
	  ]
	]);
} else
if ($act == 'FUNCTION_POINTS') {
	// Get milestones
	$milestones = milestonesForProj($_POST['id']);	
	// Init empty arr
	$tasks = [];
	// For each milestone
	foreach ($milestones as $milestone) {
	  // For each task
	  $mId = $milestone['id'];
	  $tIM = tasksForMilestone($mId);
	  foreach ($tIM as $task) {
	    // Insert task w/ 4 details into empty arr
	    $tasks[] = [
	      'milestoneId' => $mId,
	      'milestoneTitle' => $milestone['title'],
	      'taskId' => $task['id'],
	      'taskTitle' => $task['title']
	    ];
	  // Endfor
	  }
	// Endfor
	}

	// Echo arr
	echo json_encode([
	  'action' => $act,
	  'payload' => $tasks
	]);
} else
if ($act == 'BREADCRUMBS') {
	$pg = $_POST['pageType'];
	$id = $_POST['id'];
	
	echo json_encode([
		'action' => $act,
		'payload' => bCrumbsFor($pg, $id, $rolePermissions)
	]);	
} else
if ($act == 'ITEM_CREATION_LIST') {
	$lsItems = [];
	$fields = 'username AS label, username AS val';
	$itemType = $_POST['itemType'];
			
	// Determine which fields to get for the list's items
	$projMgrLs = false;
	$tailoredSql = false;
	if ($itemType == 'projectManager') {
		$projMgrLs = true;
		$fields = 'DISTINCT username AS label, username AS val';
		$itemType = 'user';	// Select from the user table, not the projectManager table
	} else
if ($itemType == 'milestone') {
		$fields = "CONCAT('M-', id, ': ', title) AS label, id AS val";
	} else
if ($itemType == 'task') {
		$fields = "CONCAT('T-', id, ': ', title) AS label, id AS val";
	} else
if ($itemType == 'user') {
		$fields = 'DISTINCT username AS label, username AS val';
	} else
if ($itemType == 'project') {
		$fields = "CONCAT('P-', id, ': ', title) AS label, id AS val";
	} else {
		error_log('action "CREATE_SYS_ITEM_LS" attempted to get a lists items, but encountered an unknown item type "' . $itemType . '"');
	}
	
	// Get list items
	if (!$tailoredSql) {
		$sql = "SELECT $fields " .
			"FROM $itemType";
		// If fetching list of project managers, ensure to get only project managers	
		if ($projMgrLs) $sql .= " WHERE roleCode = 'projectManager'";
		$lsItems = doSql($sql);
	}
	
	echo json_encode([
		'action' => $act,
		'payload' => $lsItems
	]);
} else {
	echo 'ERROR: unknown action specified';
}