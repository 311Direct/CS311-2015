<?php include_once('templates/edit-user.php'); ?>

<div class="row content-container">
	<h2 class="display-name"><!-- display name --></h2>
	<div class="summary">
		<!-- summary -->
	</div>
</div>
<div class="row content-container">
	<div>
		<h3 class="col-xs-12">permissions</h3>
	</div>
	<p class="col-xs-12">Role: <span class="role"><!-- role --></span></p>
	<div class="col-xs-12">Permissions:</div>
	<ul class="col-xs-12 permissions">
		<!-- permissions, e.g.:
		<li>Create project plan, milestone, task &amp; user</li>
		-->
	</ul>
</div>
<div class="row content-container">
	<h3 class="col-xs-12">past projects</h3>
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Project title</th>
				<th>Project manager</th>
				<th>Roles served</th>				
			</tr>
		</thead>
		<tbody class="past-projects">
			<!-- past projects, e.g.:
			<tr>
				<td><a href="">P-690</a></td>
				<td>Telstra NBN rollout</td>
				<td>Hans</td>
				<td>
					<ul>
						<li>Software Developer</li>					
						<li>Quality Assurance</li>																		
					</ul>
				</td>				
			</tr>
			-->
		</tbody>
	</table>
</div>