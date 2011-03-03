<?php
    $json_content = json_decode($json["json"]);

    $first_col_items = "";
    $second_col_items = "";

    $content_exists = false;
    $num_tags = 0;

    $json_tags = null;

    if(isset($json_content->navigation->Tags->facets)) {
        $content_exists = true;
        
        $json_tags = $json_content->navigation->Tags->facets;
        $tag_html = "<div class='filter-tags'><ol class='tag-list'>";

        $second_column = false;

        foreach($json_tags as $tag) {
            if($num_tags == intval($json["tag_count"]))
                continue;

            $tag_html.="<li><a class=\"tag-remove\" href=\"#\" title=\"Filter this tag\"><span>&nbsp;</span></a>";
            $tag_html.="<a class=\"tag-select\" id='lnk_".$num_tags."' href='#'>".$tag->name."</a></li>";

            $num_tags ++;
        }
        $tag_html.="</ol></div>";
    }
?>

<script type="text/javascript">
    $(document).ready(function(){

        $("div#filter-widget-bottom").hide();
<?php
    $num_tags = 0;

    if($content_exists) {
        foreach($json_tags as $tag) {
            if($num_tags == intval($json["tag_count"]))
                continue;
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
<div class="widget-box-tags">
    <div id="filter-widget-top">
<?php
    if($content_exists) {
?>
        <?php echo($tag_html);?>
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
 
