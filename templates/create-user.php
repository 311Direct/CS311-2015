<div class="row col-md-12 popup popup-new-user content-container" data-create-item-type="user">
	<h3 class="col-xs-10">new user</h3>
	<button type="button" class="col-xs-2 cancel"><span class="glyphicon glyphicon-remove"></span></button>
	<h4 class="col-xs-12">Display name</h4>	
	<input type="text" class="col-xs-12 display-name" placeholder="Michael Nguyen" />
	<h4 class="col-xs-12">Username</h4>	
	<input type="text" class="col-xs-12 username" placeholder="michaeln" />
	<h4 class="col-xs-12">Password</h4>
	<input type="text" class="col-xs-12 password" placeholder="example: bluesky43" />
	<h4 class="col-xs-12">Expertise</h4>
	<input type="text" class="col-xs-12 expertise" placeholder="Web technologies" />	
	<h4 class="col-xs-12">Role</h4>
	<select class="col-xs-12 roles">
		<!-- roles -->
	</select>
	<h4 class="col-xs-12">Permissions</h4>	
	<table class="col-xs-12">
		<thead>
			<tr>
				<th>Permit</th>
				<th>Permission</th>
			</tr>
		</thead>
		<tbody class="permissions">
			<!-- permissions -->
		</tbody>
	</table>
	
	<!-- projects that this user belongs in -->
	<h4 class="col-xs-12">Projects belonging to</h4>
	<select class="col-xs-12 create-system-item-list list-projects" data-items-field="user-projects">
		<option value=""></option>	<!-- initially have a dummy option, so that the 'onchange' can be fired -->
	</select>
	<div class="col-xs-12 user-projects"></div>
	
	<button type="button" class="col-xs-4 col-xs-offset-2 create"><span class="glyphicon glyphicon-ok"></span></button>
	<button type="button" class="col-xs-4 col-xs-offset-1 cancel"><span class="glyphicon glyphicon-remove"></span></button>
</div>