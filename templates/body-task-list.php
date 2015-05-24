<div class="row content-container">
	<h2 class="col-xs-12">Tasks assigned to you</h2>
	<table class="col-xs-12">
		<thead>
			<tr>
				<th>ID</th>
				<th>Priority</th>
				<th>Task title</th>
				<th>Project title</th>
				<th>Status</th>												
			</tr>
		</thead>
		<tbody class="tasks-assigned-to-user">				
		</tbody>
	</table>
</div>
<?php
if($PERMISSIONS_ENGINE->canCompleteRequestForOperation($rolePermissions->getRoleInfo()->getPermissionsValue(), P_FULL_CONTROL))
{
?>
<div class="row content-container">
	<h2 class="col-xs-12">All tasks</h2>
	<table class="col-xs-12">
		<thead>
			<tr>
				<th>ID</th>
				<th>Priority</th>
				<th>Task title</th>
				<th>Project title</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody class="all-tasks">
		</tbody>
	</table>
</div>
<?php } ?>