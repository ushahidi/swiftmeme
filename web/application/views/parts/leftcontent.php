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
        var nav_baseUrl = "<?php echo(url::base()); ?>";
        
        // Fill in the map widget
        filterViewWidget = new FilterViewWidget(nav_baseUrl, "div#filter-view-widget", nav_state, nav_minVeracity,
                nav_maxVeracity, nav_type, nav_subType, nav_source, nav_pageSize, nav_pageStart, nav_orderBy);
        filterViewWidget.RenderView();

        // Fill in the trending keywords widget
        trendingKeywordsWidget = new TrendingKeywordsWidget(nav_baseUrl, "div#trending-keywords-widget");
        trendingKeywordsWidget.RenderView();

        // Fill in the active sources widget
        activeSourcesWidget = new ActiveSourcesWidget(nav_baseUrl, "div#active-sources-widget");
        activeSourcesWidget.RenderView();
    });
</script>

<div id="filter-view-widget">
</div>

<div class="widget-seperator"></div>

<div id="trending-keywords-widget">
</div>

<div class="widget-seperator"></div>

<div id="active-sources-widget">
</div>