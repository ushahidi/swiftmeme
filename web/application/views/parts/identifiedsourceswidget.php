<?php
    function display_channels($display_channels, $loginstatus) {
        $current_item = 0;
        foreach($display_channels as $channel) {
            if($loginstatus)
                echo("<div id='is_".$channel->id."'>".$channel->name."&nbsp;<a href=\"javascript:DeleteIdentifiedSourcesChannel('".$channel->id."');\">".Html::image("media/images/button-markas-inaccurate.png")."</a></div>");
            else
                echo("<div>".$channel->name."</div>");

            $current_item ++;
        }
    }

    $channels_decoded = json_decode($json);
    $channels_decoded = $channels_decoded->data->channels;

    $channels = array();

    foreach($channels_decoded as $channel_item) {
        if(($channel_item->type == "Twitter") && ($channel_item->subType == "Follow User")) {
            $channels[] = $channel_item;
        }
        else if(($channel_item->type == "Feeds") && ($channel_item->subType == "News Feeds")) {
            $channels[] = $channel_item;
        }
    }

    if($withcontrols) {
?>
<script type="text/javascript">
    $(document).ready(function() {
        //place an event handler for the submit button
        $("input#identifiedsourcetwittersubmit").click(function() {
            // Submit the value
            var termtosubmit = $("input#identifiedsourcetwittertext").val();

            // Add the two channels
            if(termtosubmit != "") {
                // Not empty - add follow channel
                var twitterFollowAdded = false;

                // Add twitter channel
                var updatePeriod = 1; //default
                var json =
                    '{"type":"Twitter",'+
                     '"subType":"Follow User",'+
                     '"name":"'+termtosubmit+'",'+
                     '"updatePeriod":'+updatePeriod+','+
                     '"parameters":';

                // Add parameters

                json += '{"TwitterAccount":"'+termtosubmit+'"},';

                json = json.substring(0, json.length - 1) + '}';

                $.post("<?php echo(url::base()); ?>api/channels/add",
                    { channel : json },
                    function(data) {
                        //TODO: do something if data.message != 'OK''
                        twitterFollowAdded = true;

                        // Refresh the panel
                        $.get("<?php echo(url::base()); ?>api/identifiedsources/get/false", function(data) {
                            $("div#identifiedsourcescontent").html(data);
                            $("input#identifiedsourcetwittertext").val("");
                        });
                    },
                    'json'
                );
            }
        });

        $("input#identifiedsourcersssubmit").click(function() {
            // Submit the value
            var rssFollowAdded = false;

            var termtosubmit = $("input#identifiedsourcersstext").val();

            // Add the two channels
            if(termtosubmit != "") {
                // Not empty - add follow channel
                var rssFollowAdded = false;

                // Add twitter channel
                var updatePeriod = 1; //default
                var json =
                    '{"type":"Feeds",'+
                     '"subType":"News Feeds",'+
                     '"name":"'+termtosubmit+'",'+
                     '"updatePeriod":'+updatePeriod+','+
                     '"parameters":';

                // Add parameters

                json += '{"feedUrl:":"'+termtosubmit+'"},';

                json = json.substring(0, json.length - 1) + '}';
                $.post("<?php echo(url::base()); ?>api/channels/add",
                    { channel : json },
                    function(data) {
                        //TODO: do something if data.message != 'OK''
                        twitterFollowAdded = true;

                        // Refresh the panel
                        $.get("<?php echo(url::base()); ?>api/identifiedsources/get/false", function(data) {
                            $("div#identifiedsourcescontent").html(data);
                            $("input#identifiedsourcersstext").val("");
                        });
                    },
                    'json'
                );
            }
        });
    });
</script>
<?php
    //process the passed JSON
    $json_array = json_decode($json);
    $channel_data = $json_array->data->channels;

    //initiate the array
    $source_array = array();

    foreach($channel_data as $data_item) {
        if(isset($data_item->parameters->feedUrl)) {
            $source_array[] = $data_item->parameters->feedUrl;
        }
        else if(isset($data_item->parameters->TwitterAccount)) {
            $source_array[] = $data_item->parameters->TwitterAccount;
        }
    }
?>
<div class="widget-seperator"></div>
<div class="widget-box">
    <div style="text-align:center;font-weight:bold;">IDENTIFIED SOURCES</div>
    <br/>
<?php
        if($isloggedin) {
?>
    <form action="#">
        <div>
            Twitter follow:
        </div>
        <div>
            <input type="text" id="identifiedsourcetwittertext" />
            <input type="button" id="identifiedsourcetwittersubmit" value="Submit"/>
        </div>
        <div>
            RSS feed:
        </div>
        <div>
            <input type="text" id="identifiedsourcersstext" />
            <input type="button" id="identifiedsourcersssubmit" value="Submit"/>
        </div>
        <br/>
    </form>
<?php
        }
?>
    <div id="identifiedsourcescontent">
<?php
    display_channels($channels, $isloggedin);
?>
    </div>
</div>
<?php
    }
    else {
        display_channels($channels, $isloggedin);
    }
?>