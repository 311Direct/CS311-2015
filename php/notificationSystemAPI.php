<?php
  require('Direct/config/db.config.php');

class notificationSystem {
	function handleReq() {
		$act = $_POST['action'];
		if ($act == 'USER_MSG_READ') $this->userMsgRead();
		else if ($act == 'USER_MSG_SEND') $this->userMsgSend();
		else if ($act == 'MSG_POLL') $this->msgPoll();
		else if ($act == 'APPROVAL_REQ_ATTENDED') $this->approvalReqAttended();
		else if ($act == 'WATCH_TASK') $this->watchTask();
		else if ($act == 'USER_LIST_GET') $this->userListGet();
	}

	private function execSql($sql) {
		$mysqli = new mysqli('localhost', $GLOBALS['DBUSER'], $GLOBALS['DBPASS'], 'vitawebs_csci311_v2');
		if (($result = $mysqli->query($sql)) == false) {
			error_log($mysqli->error);
			$mysqli->close();
			return false;
		} else {
			return $result;
		}
	}

	private function userMsgRead() {
		$ntfs = $_POST['ntfs'];
		foreach($ntfs as $ntf) {
			$type = $ntf['type'];
			$id = $ntf['id'];
			if ($type == 'message') {
				$this->execSql('UPDATE message ' .
					'SET hasRead = 1 ' .
					"WHERE usernameTo = '$_SESSION[username]' " .
					"AND id = $id");
			} else if ($type == 'approvalRequest') {
				$this->execSql('UPDATE approvalRequest ' .
					'SET hasRead = 1 ' .
					"WHERE approver = '$_SESSION[username]' " .
					"AND id = $id");			
			} else {
				error_log('userMsgRead(): encountered an unknown notification type');
				die();
			}
		}
		echo json_encode([
			'action' => 'USER_MSG_READ',
			'payload' => '1'
		]);
	}

	private function userMsgSend() {
		$result = $this->execSql('INSERT INTO message (usernameFrom, usernameTo, datetime, message) ' .
			"VALUES ('$_SESSION[username]', '$_POST[usernameTo]', NOW(), '$_POST[msg]')");
		if ($result != false) $result = true;
		echo json_encode([
			'action' => 'USER_MSG_SEND',
			'payload' => $result
		]);
	}

	private function msgPoll() {
		$payload = [];
		
		// Get messages	(inc. watch alerts)
		$sqlMsgs = "SELECT id, usernameFrom, datetime, message, watchAlert " .
			'FROM message ' .
			"WHERE usernameTo = '$_SESSION[username]' " .
			'AND hasRead = 0 ';
		$result = $this->execSql($sqlMsgs);
		while ($msg = $result->fetch_assoc()) {
			$msg['type'] = 'msg';
			$from = $msg['usernameFrom'];
			$msg['message'] = 'From <a href="user-details.php?id=' . $from . '">' . $from . '</a>: ' . $msg['message'];
			$payload[] = $msg;
		}
		
		// Get approval requests
		$sqlApprovalReqs = "SELECT 'approvalReq', id, approver, approvee, datetime, message, forType, forId " .
			'FROM approvalRequest ' .
			"WHERE approver = '$_SESSION[username]' " . 
			'AND hasRead = 0 ' .
			'ORDER BY datetime DESC';
		$result = $this->execSql($sqlApprovalReqs);
		while ($req = $result->fetch_assoc()) {
			$req['type'] = 'approvalReq';
			$payload[] = $req;
		}

		// Return messages & approval requests
		echo json_encode([
			'action' => 'MSG_POLL',
			'payload' => $payload
		]);
	}

	private function saveApprovalReqEdit($aRId, $sql) {
		$sqlEditSql = 'INSERT INTO approvalRequestSql (approvalRequestId, sqlStr) ' .
			"VALUES ($aRId, \"$sql\")";
		doSql($sqlEditSql);	
	}

	function sendApprovalReq($pjMgr, $type, $id, $col, $sqlRaw) {
		$idWithTypePrefix = strtoupper($type[0]) . '-' . $id;
		$msg = '<a href="user-details.php?id=' . $_SESSION['username'] . '">' . $_SESSION['username'] . '</a> wants to change the ' . $col . ' for ' . $type . ' <a href="' . $type . '-details.php?id=' . $id . '">' . $idWithTypePrefix . '</a>';
		
		// Get project manager for the item that the user is editing
		if ($type == 'project') {
			$sql = 'SELECT username ' .
				'FROM projectManager PM ' .
				"WHERE PM.projectId = $id";
		} else if ($type == 'milestone') {
			$sql = 'SELECT username ' .
				'FROM milestone M JOIN projectManager PM ON M.projectId = PM.projectId ' .
				"WHERE M.id = $id";
		} else if ($type == 'task') {
			$sql = 'SELECT username ' .
				'FROM task T JOIN projectManager PM ON T.projectId = PM.projectId ' .
				"WHERE T.id = $id";
		} else {
			error_log('sendApprovalReq(): encountered an unexpected item type');
			die();
		}		
		$mysqli = new mysqli('localhost', $GLOBALS['DBUSER'], $GLOBALS['DBPASS'], 'vitawebs_csci311_v2');
		$result = $mysqli->query($sql);
		
		while ($approver = $result->fetch_assoc()['username']) {
			// Send approval request
			$sqlSendApprovalReq = 'INSERT INTO approvalRequest (approver, approvee, datetime, forType, forId, message) ' .			
			"VALUES ('$approver', '$_SESSION[username]', NOW(), '$type', $id, '$msg')";			
			$this->execSql($sqlSendApprovalReq);

			// Get database ID of this new approval request			
			$sql = 'SELECT id ' .
				'FROM approvalRequest ' .
				'ORDER BY id DESC';
			$aRId = doSql($sql)[0]['id'];
			
			// Save the edit for the approval request
			if (is_array($sqlRaw)) {
				foreach ($sqlRaw as $sql) {
					$this->saveApprovalReqEdit($aRId, $sql);		
				}
			} else {
				$this->saveApprovalReqEdit($aRId, $sqlRaw);
			}

		}
	}

	private function publishApprovalReqEditsFor($id) {
		// Get edits
		$sql = 'SELECT sqlStr ' .
			'FROM approvalRequestSql ' .
			"WHERE approvalRequestId = $id";
		$edits = $this->execSql($sql);
		
		// For each edit
		foreach ($edits as $edit) {
			// Publish it
			$editSql = $edit['sqlStr'];
			$this->execSql($editSql);
		}
	}

	private function approvalReqAttended() {
		// Get approval information
		$id = $_POST['id'];
		$result = $this->execSql('SELECT * ' .
			'FROM approvalRequest ' .
			"WHERE id = $id");
		$approval = $result->fetch_assoc();
		$approvee = $approval['approvee'];	
		$accepted = $_POST['accepted'];
		if ($accepted == 'true') {
			$res = 'accepted';
		} else {
			$res = 'rejected';
		}

		// Build the message, to be sent to the approvee on the approval outcome
		$itemType = $approval['forType'];
		$itemId = $approval['forId'];
		$itemIdWithTypePrefix = strtoupper($itemType[0]) . '-' . $itemId;
		$msg = $_SESSION['username'] . ' ' . $res . ' your edit request for ' . $itemType . ' ' . $itemIdWithTypePrefix;

		// Notify the approvee on the approval outcome
		$payload = 1;
		$queryRes = $this->execSql('INSERT INTO message (usernameFrom, usernameTo, datetime, message) ' .
			"VALUES ('$_SESSION[username]', '$approvee', NOW(), '$msg')");
		if ($queryRes == false) $payload = 0;

		// If approval request accepted, push the edit live
		if ($res == 'accepted') $this->publishApprovalReqEditsFor($id);
		
		// Remove the approval request
		$queryRes = $this->execSql('DELETE FROM approvalRequest ' .
			"WHERE id = $id");
		if ($queryRes == false) $payload = 0;

		// If approval request accepted, notify task watchers of a change
		if ($res == 'accepted' && $itemType == 'task') $this->taskChanged($itemId);
		
		echo json_encode([
			'action' => 'APPROVAL_REQ_ATTENDED',
			'payload' => $payload
		]);		
	}

	function taskChanged($id) {
		$watchers = $this->execSql('SELECT username ' .
			'FROM watchedTask ' .
			"WHERE taskId = $id"
		);

		$msg = $_SESSION['username'] . ' updated task T-' . $id;
		while ($watcher = $watchers->fetch_assoc()['username']) {
			$sql = 'INSERT INTO message (usernameFrom, usernameTo, datetime, message, watchAlert) ' .
				"VALUES ('$_SESSION[username]', '$watcher', NOW(), '$msg', 1)";
			$this->execSql($sql);
		}
	}

	private function watchTask() {
		// Watch/unwatch
		$resultWatching = $this->execSql('SELECT * ' .
			'FROM watchedTask ' .
			"WHERE username = '$_SESSION[username]' " .
			"AND taskId = $_POST[id]");			
		$watching = ($resultWatching->num_rows > 0);
		if ($watching) {
			// Un-watch
			$result = $this->execSql('DELETE FROM watchedTask ' .
				"WHERE username = '$_SESSION[username]' " .
				"AND taskId = $_POST[id]");
		} else {
			// Watch
			$result = $this->execSql('INSERT INTO watchedTask (username, taskId) ' .
				"VALUES ('$_SESSION[username]', $_POST[id])");			
		}
		
		// Tell user if watch/unwatch was successful
		$success = 1;
		if ($result == false) $success = 0;
		echo json_encode([
			'action' => 'WATCH_TASK',
			'payload' => [
				'success' => $success,
				'nowWatching' => !$watching
			]
		]);
	}
	
	private function userListGet() {
		$mysqli = new mysqli('localhost', $GLOBALS['DBUSER'], $GLOBALS['DBPASS'], 'vitawebs_csci311_v2');
		$sql = 'SELECT username ' .
			'FROM user ' .
			"WHERE username != '$_SESSION[username]'";

		if (($result = $mysqli->query($sql)) == false) {
			error_log($mysqli->error);
			$mysqli->close();
			$payload = 0;
		} else {
			$payload = [];
			while ($user = $result->fetch_assoc()) {
				$payload[] = $user['username'];
			}
		}
		
		echo json_encode([
			'action' => 'USER_LIST_GET',
			'payload' => $payload
		]);
	}
};

if (session_start() && isset($_SESSION['username'])) {
	$ns = new notificationSystem();	
	$ns->handleReq();
}