<?php include_once('templates/header.php'); ?>
    <div class="row">
        <h2>Rollout new Telstra database system</h2>
        <div class="summary">
            Created by <a href="user-details.php">Michael Nguyen</a> on 18th April, 2015<br />
            Managed by <a href="">Hans</a><br />
            In Progress<br />
        </div>
    </div>

    <div class="row">
        <h2>Schedule</h2>
        <table id="gannt-table" class="table table-striped">
            <col width="300px">
            <col width="700px">
            <thead>
                <tr>
                    <th scope="col">Task</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr><td scope="row">Finish PHP API</td>
                    <td rowspan="4" id="gannt-container">
                        <div class="gannt-panel">
                            <div class="gannt-date-range" style="left: 30px; background: red; width="><time class="gannt-task-start" datetime="2015-04-22"></time><span>Finish PHP API</span><time class="gannt-task-end" datetime="2015-05-03"></time></div>
                            <div class="gannt-date-range" style="left: 30px; background: orange; width="><time class="gannt-task-start" datetime="2015-04-26"></time><span>Create DB</span><time class="gannt-task-end" datetime="2015-07-03"></time></div>
                            <div class="gannt-date-range" style="left: 30px; background: cornflowerblue;"><time class="gannt-task-start" datetime="2015-06-26"></time><span>Lorem</span><time class="gannt-task-end" datetime="2015-08-03"></time></div>
                            <div class="gannt-date-range" style="left: 30px; background: lightsalmon;"><time class="gannt-task-start" datetime="2015-07-03"></time><span>Ipsum</span><time class="gannt-task-end" datetime="2015-07-03"></time></div>

                        </div>
                    </td>
                </tr>
                <tr><td scope="row">Create DB</td></tr>
                <tr><td scope="row">Lorem</td></tr>
                <tr><td scope="row">Ipsum</td></tr>
            </tbody>
        </table>
        </div>
    </div>

<?php include_once('templates/footer.php'); ?>