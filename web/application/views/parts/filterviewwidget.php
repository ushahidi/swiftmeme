<?php
    $json_content = json_decode($json);

    $first_col_items = "";
    $second_col_items = "";

    $content_exists = false;
    $num_tags = 0;

    $json_tags = null;

    if(isset($json_content->navigation->Tags->facets)) {
        $content_exists = true;
        
        $json_tags = $json_content->navigation->Tags->facets;

        $second_column = false;

        foreach($json_tags as $tag) {
            if(!$second_column) {
                $first_col_items.= "<div><a id='lnk_".$num_tags."' href='#'>".$tag->name."</a></div>";
            }
            else {
                $second_col_items.= "<div><a id='lnk_".$num_tags."' href='#'>".$tag->name."</a></div>";
            }

            $second_column = !$second_column;
            $num_tags ++;
        }
    }
?>

<script type="text/javascript">
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

    $(document).ready(function(){

        $("div#filter-widget-bottom").hide();
<?php
    $num_tags = 0;

    if($content_exists) {
        foreach($json_tags as $tag) {
?>
        $("a#lnk_<?php echo($num_tags); ?>").click(function() {
            setInterval("Update()", 10000);
            listController = new ListControllerFiltered(nav_baseUrl, "div#content-list ul", "div#nav-container", "<?php echo($tag->name); ?>");
            listController.NavigationStateChange(new NavigationState(nav_state, nav_minVeracity, nav_maxVeracity, nav_type, nav_subType, nav_source, nav_pageSize, nav_pageStart, nav_orderBy));
            $("#more_content a").attr("href", "javascript:MoreContent("+ nav_pageSize +")");

            $("div#filter-widget-top").fadeOut();
            $("div#filter-widget-bottom").fadeIn();

            return false;
        });
<?php
            $num_tags ++;
        }
    }
?>
        $("a#link_slideup").click(function() {
            setInterval("Update()", 10000);
            listController = new ListController(nav_baseUrl, "div#content-list ul", "div#nav-container");
            listController.NavigationStateChange(new NavigationState(nav_state, nav_minVeracity, nav_maxVeracity, nav_type, nav_subType, nav_source, nav_pageSize, nav_pageStart, nav_orderBy));
            $("#more_content a").attr("href", "javascript:MoreContent("+ nav_pageSize +")");

            $("div#filter-widget-top").fadeIn();
            $("div#filter-widget-bottom").fadeOut();

            return false;
        });
    });
</script>

<div style="text-align:center;font-weight:bold;">FILTER VIEW</div>
<div class="widget-seperator"></div>
<div class="widget-box">
    <div id="filter-widget-top">
<?php
    if($content_exists) {
?>
        <div class="filter-view-widget-item-left">
            <?php echo($first_col_items);?>
        </div>
        <div class="filter-view-widget-item-right">
            <?php echo($second_col_items);?>
        </div>
<?php
    }
    else {
        echo("<div style='text-align:center;'>No tags to show</div>");
    }
?>
    </div>
    <div id="filter-widget-bottom" style="text-align:center;">
        <a href="#" id="link_slideup">Display all</a>
    </div>
</div>
 
