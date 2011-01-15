<script type="text/javascript" language="javascript">
    // This script fills in the 3 divs
    var nav_state = '<?php echo(isset($_SESSION["nav_state"]) ? $_SESSION["nav_state"] : $state); ?>'; <!-- changed -->
    var nav_minVeracity = <?php echo(isset($_SESSION["nav_minVeracity"]) ? $_SESSION["nav_minVeracity"] : "0"); ?>;
    var nav_maxVeracity = <?php echo(isset($_SESSION["nav_maxVeracity"]) ? $_SESSION["nav_maxVeracity"] : "100"); ?>;
    var nav_type = '<?php echo(isset($_SESSION["nav_type"]) ? $_SESSION["nav_type"] : "null"); ?>';
    var nav_subType = '<?php echo(isset($_SESSION["nav_subType"]) ? $_SESSION["nav_subType"] : "null"); ?>';
    var nav_source = '<?php echo(isset($_SESSION["nav_source"]) ? $_SESSION["nav_source"] : "null"); ?>';
    var nav_pageSize = <?php echo(isset($_SESSION["nav_pageSize"]) ? $_SESSION["nav_pageSize"] : "20"); ?>;
    var nav_pageStart = <?php echo(isset($_SESSION["nav_pageStart"]) ? $_SESSION["nav_pageStart"] : "0"); ?>;
    var nav_orderBy = '<?php echo(isset($_SESSION["nav_orderBy"]) ? $_SESSION["nav_orderBy"] : "null"); ?>';
    var nav_baseUrl = "<?php echo(url::base()); ?>";
    
    $(document).ready(function() {
        // Fill in the terms monitor widget
        termsToMonitorWidget = new TermsToMonitorWidget(nav_baseUrl, "div#terms-monitor-widget");
        termsToMonitorWidget.RenderView();

        // Fill in the identified sources widget
        identifiedSourcesWidget = new IdentifiedSourcesWidget(nav_baseUrl, "div#identified-sources-widget");
        identifiedSourcesWidget.RenderView();

        // Map view widget content
        $("div#map-widget").html('<iframe src="<?php echo(url::base()); ?>api/maps/get/' + nav_state + '/' + nav_minVeracity +'/' +
            nav_maxVeracity +'/' + nav_type + '/' + nav_subType + '/' + nav_source + '/' + nav_pageSize + '/' + nav_pageStart + '/' +
            nav_orderBy + '/" width="80%" height="150px"/>');
    });
</script>

<div id="map-widget">
</div>

<div class="widget-seperator"></div>

<div id="terms-monitor-widget">
</div>

<div class="widget-seperator"></div>

<div id="identified-sources-widget">
</div>