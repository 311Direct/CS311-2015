			<?php
			$atLoginPg = (strpos($_SERVER['PHP_SELF'], 'login') !== false);
			if (!$atLoginPg) {
			?>					
			<div class="visible-md visible-lg row col-md-4 secondary-panel-container content-container nano">
				<div class="col-md-12 secondary-panel nano-content">
					<div class="subpanel-creation">
						<h1><span class="glyphicon glyphicon-book"></span>Creation</h1>
						<?php require('create-buttons.php'); ?>
						<?php require('create-project.php'); ?>
						<?php require('create-milestone.php'); ?>
						<?php require('create-task.php'); ?>
						<?php require('create-user.php'); ?>															
					</div>
					<div class="subpanel-notes">
						<h1><span class="glyphicon glyphicon-pushpin"></span>Notes</h1>
						<?php require_once('notes.php'); ?>
					</div>
				</div>		
			</div>		
			<?php
			}
			?>
		</div>
		<footer>
			<span class="footer-product-title">direct.</span><span class="slogan">your lead in project management.</span>
		</footer>

		<!-- jquery core is loaded in header template, so that $ can be used in file upload -->
		<script src="js/jquery-ui.js"></script>	
		<script src="js/project-details-anchors.js"></script>
		<script src="js/page-type.js"></script>	
		<script src="js/mobile-menu.js"></script>
		<script src="js/project-list.js"></script>
		<script src="js/springy.js"></script>
		<script src="js/springyui.js"></script>
		<script src="js/dependency-chain.js"></script>		
		<script src="js/new-comment.js"></script>
		<script src="js/expandable-panels.js"></script>
		<script src="js/login.js"></script>
		<script src="js/print.js"></script>
		<script src="js/logout.js"></script>
		<script src="js/search.js"></script>
		<script src="js/roles.js"></script>
		<script src="js/create-system-item.js"></script>
		<script src="js/notification-system.js"></script>
		<script src="js/notifications.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/notes.js"></script>
		<script src="js/scrollbar.js"></script>
		<script src="js/secondary-panel-scrollbar.js"></script>
		<script src="js/breadcrumbs.js"></script>		
		<script src="js/edit.js"></script>
		<script src="js/create-system-item-lists.js"></script>
	</body>
</html>