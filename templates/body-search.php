<div class="row search-row content-container">
	<h2 class="col-xs-12">Search</h2>
	<p class="col-xs-12">for...</p>
	<select class="col-xs-12 type">
		<option value="projects">Projects</option>
		<option value="milestones">Milestones</option>
		<option value="tasks">Tasks</option>
		<option value="users">Users</option>				
	</select>
</div>
<div class="row search-row content-container">
	<p class="col-xs-12">by...</p>
	<select class="col-xs-12 criteria criteria-projects">
		<option value="id" selected>ID</option>
		<option value="title">Title</option>
		<option value="status">Status</option>
		<option value="projectManagerDisplayName">Project manager display name</option>				
	</select>
	<select class="col-xs-12 criteria criteria-milestones">
		<option value="id">ID</option>
		<option value="title">Title</option>
		<option value="status">Status</option>
	</select>
	<select class="col-xs-12 criteria criteria-tasks">
		<option value="id">ID</option>
		<option value="title">Title</option>
		<option value="status">Status</option>
		<option value="assigneeDisplayName">Assignee display name</option>				
	</select>
	<select class="col-xs-12 criteria criteria-users">
		<option value="username">Username</option>
		<option value="displayName">Display name</option>				
	</select>			
</div>
<div class="row search-row content-container">
	<p class="col-xs-12">where value equals...</p>
	<input type="text" class="col-xs-12 value-text value" placeholder="Anything!" />
	<input type="number" class="col-xs-12 value-numeric value" min="0" value="6" />
	<button type="button" class="btn-search-do">SEARCH</button>
</div>
<div class="row content-container search-again-row">
	<button type="button" class="col-xs-12 btn-new-search">NEW SEARCH</button>
</div>
<div class="row content-container results-row">
	<h2 class="col-xs-12">Results</h2>
	<p class="col-xs-12"><span class="result-count"></span> search result(s) found.</p>
	<table class="col-xs-12 results-projects">
		<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Project managers</th>
				<th>Status</th>								
			</tr>
		</thead>
		<tbody class="results">
			<!-- search results -->
		</tbody>
	</table>
	<table class="col-xs-12 results-milestones">
		<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Status</th>								
			</tr>
		</thead>
		<tbody class="results">
			<!-- search results -->
		</tbody>
	</table>
	<table class="col-xs-12 results-tasks">
		<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Status</th>
				<th>Assignees</th>
			</tr>
		</thead>
		<tbody class="results">
			<!-- search results -->
		</tbody>
	</table>
	<table class="col-xs-12 results-users">
		<thead>
			<tr>
				<th>Display name</th>
				<th>Hours worked</th>
			</tr>
		</thead>
		<tbody class="results">
			<!-- search results -->
		</tbody>
	</table>		
</div>