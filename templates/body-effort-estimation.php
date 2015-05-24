<div class="row content-container">
    <h1 class="col-xs-12">Effort Estimation</h1>

    <h2 class="col-xs-12">High-level effort estimation</h2>

    <div class="col-xs-12 cocomo-1-info-that-can-be-calculated">
        We have not yet calculated your project&#39;s:
        <ul>
            <li>project type</li>
            <li>approximate lines of code</li>
            <li>required man months</li>
            <li>required months</li>
            <li>required employee count</li>
        </ul>
        because we don&#39;t have enough project information.
        <button type="button" class="btn-cocomo1-questionnaire-start">provide information</button>
    </div>
    <!-- Begin COCOMO1 visualization -->
    <div class="col-xs-12 cocomo1">
        <div class="col-xs-12 project-type">
            <span>Your project is a &quot;</span><span class="value"></span><span>&quot; project &amp;</span>
        </div>
        <div class="col-xs-12 kloc">
            <span>requires </span><span class="value"></span><span> lines of code.</span>
        </div>
        <div class="col-xs-12 effort">
            <span>From a resource perspective, your project requires </span><span class="value"></span>
        </div>
        <div class="col-xs-12 dev-time">
            <span> man-hours over a course of </span><span class="value"></span><span> months &amp; will take</span>
        </div>
        <div class="col-xs-12 people-req">
            <span class="value"></span><span> employees to complete.</span>
        </div>
    </div>
    <!-- End COCOMO1 visualization -->
    <!-- Begin COCOMO1 questionnaire -->
    <div class="col-xs-12 cocomo1-questionnaire">
        <div class="col-xs-12 questions">
            <h2 class="col-xs-12">Please answer the following questions</h2>

            <div class="for-embedded-sys">
            </div>
            <div class="for-not-embedded-sys">
            </div>
        </div>
        <div class="col-xs-12 cocomo1-questionnaire-spacer"></div>
        <div class="col-xs-10 tech-details">
            <h2 class="col-xs-12">What is the technical scope of your solution?</h2>
        </div>
        <button class="col-xs-2 btn-add-tech-detail">
            <span class="glyphicon glyphicon-plus"></span>
        </button>
        <button type="button" class="col-xs-12 btn-calc-cocomo-1">calculate</button>
    </div>
    <!-- End COCOMO1 questionnaire -->


    <h2 class="col-xs-12">COCOMO2 Analysis</h2>

    <div class="col-xs-12 cocomo-2-not-calculated">
        We have not yet calculated your project&#39;s estimated required man months using COCOMO2 because we don&#39;t
        have enough information about the organization and project.
        <button type="button" class="btn-cocomo2-questionnaire-start">provide information</button>
    </div>
    <!-- Begin COCOMO2 questionnaire -->
    <div class="col-xs-12 cocomo2-questionnaire">
        <div class="col-xs-12 cocomo2-questions">
            <h2 class="col-xs-12">Please answer the following questions</h2>
        </div>
        <button type="button" class="col-xs-12 btn-calc-cocomo-2">calculate</button>
    </div>
    <!-- End COCOMO2 questionnaire -->

    <!-- Begin COCOMO2 results -->
    <div class="col-xs-12 cocomo2-results">
        <h4> The estimated person months is: <b></b></h4>
    </div>
    <!-- End COCOMO2 results -->


    <h2 class="col-xs-12 function-point">Function Point Analysis</h2>

    <div class="col-xs-12 fp2-not-calculated">
        Use Function Point Analysis to estimate the required person months for the project based on the tasks for the
        project.
        <button type="button" class="btn-fp-start">provide information</button>
    </div>
    <div class="function-point-questionaire">
        <ul class="function-point-q">
        </ul>
        <button type="button" class="btn-function-point-calc">Estimate</button>
    </div>

    <div class="fp-results fp-summary"></div>

    <h3 class="col-xs-12 fp-results">Function Point Breakdown</h3>
    <ul class="fp-milestones">
    </ul>
</div>

<!-- Begin effort estimation dependencies -->
<script src="js/d3.js"></script>
<script src="js/cocomo-1-questionnaire.js"></script>
<script src="js/cocomo1-visualize.js"></script>
<script src="js/cocomo2-table.js"></script>
<script src="js/functionPointBreakdown.js"></script>
<!-- End effort estimation dependencies -->