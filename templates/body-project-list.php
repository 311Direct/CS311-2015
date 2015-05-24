<div class="row content-container">
	<div class="col-xs-12"><a href="task-list.php">Tasks</a></div>
</div>
<?php 
  
if($PERMISSIONS_ENGINE->canCompleteRequestForOperation($rolePermissions->getRoleInfo()->getPermissionsValue(), (P_FULL_CONTROL | P_CREATE)))
{ 
  echo '<div class="row content-container visible-xs visible-sm">';
  include_once('create-buttons.php');
  echo '</div>';
}

?>
<div id="project-list" class="row content-container">
	<ul class="col-xs-12">
		<li><a href="#tab-projects-managing">projects managing</a></li>
		<li><a href="#tab-all-projects">all projects</a></li>
	</ul>
	<div id="tab-projects-managing" class="col-xs-12">
		<table class="projects-managing">
			<thead>
				<tr>
					<th>ID</th>
					<th>Project manager</th>
					<th width="50%">Title</th>
					<th>Progress</th>
				</tr>
			</thead>
			<tbody>			
				<!-- A list of the projects a project a manager is managing, will be inserted in here -->	
			</tbody>
		</table>
	</div>
	<div id="tab-all-projects" class="col-xs-12">
		<table class="all-projects">
			<thead>
				<tr>
					<th>ID</th>
					<th>Project manager</th>
					<th width="50%">Title</th>
					<th>Progress</th>
				</tr>
			</thead>
			<tbody>
				<!-- A list of all the projects in the system will be inserted in here -->
			</tbody>
		</table>
	</div>
</div>