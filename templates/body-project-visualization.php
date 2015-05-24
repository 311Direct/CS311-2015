<div class="row content-container">
	<h2>Project Visualization</h2>
	
	<!-- Schedule -->
	<h3>Schedule</h3>
	<div id="gantt"></div>
	
	<!-- Pert scheduling info -->
	<h3>Program Evaluation &amp; Review</h3>
	<table>
		<thead>
			<tr>
				<th rowspan="2">Task</th>
				<th rowspan="2">Dependees</th>
				<th colspan="3">Time estimates</th>
				<th rowspan="2">Expected time</th>
				<th rowspan="2">Safety buffer</th>
			</tr>
			<tr>
				<th>Opt. (O)</th>
				<th>Normal (M)</th>
				<th>Pess. (P)</th>
			</tr>
		</thead>
		<tbody class="tasks-pert-info">
		</tbody>
	</table>
	<div id="myDiagram" style="border: solid 1px gray; width:100%; height:400px"></div>
	
	<!-- Pert buffer info -->
	<h3>Project buffer</h3>
	<div>
		This project has a project buffer of <span class="project-buffer"></span> days, split between:
		<ul class="finish-tasks">
		</ul>
	</div>
	<h3>Feeding buffers</h3>
	<ul class="buffer-info">
		<li>There are no feeding buffers</li>
	</ul>

	<!-- Pert probability to finish by deadline -->
	<h3>Can this project finish in 80 days?</h3>
	<div><span class="chance-to-meet-project-deadline"></span> chance to finish project in 80 days.</div>
	<div>PERT z-index = <span class="pert-z"></span></div>
	<img src="img/graph-chance-of-meeting-target.jpg" alt="graph to identify chance of meeting target" class="graph-chance-of-meeting-target" />
</div>

<!-- Begin visualization dependencies -->
<script src="js/go.js"></script>
<script src="js/dhtmlxgantt.js"></script>
<script src="js/gantt.js"></script>
<script src="js/pert.js"></script>
<script src="js/math.js"></script>	<!-- required for calculations with imaginary numbers, which is encountered during PERT analysis -->
<script src="js/pertMeetDeadline.js"></script>
<script src="js/pert-analysis.js"></script>
<script src="js/critical-chain-analysis.js"></script>
<!-- End visualization dependencies -->