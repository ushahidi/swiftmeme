<?php
    function display_channels($display_channels, $loginstatus) {
        $output = "";

        foreach($display_channels as $channel) {
            if(!$loginstatus)
                $output.=$channel->name.", ";
            else
                $output.=$channel->name."<a href=\"javascript:DeleteTermsToMonitorChannel('".$channel->id."');\">".Html::image("media/images/button-markas-inaccurate.png")."</a>";
        }
        if(!$loginstatus)
            rtrim($output,", ");

        echo($output);
    }

    $channels_decoded = json_decode($json);
    $channels_decoded = $channels_decoded->data->channels;

    $channels = array();

    foreach($channels_decoded as $channel_item) {
        if(($channel_item->type == "Google News") && ($channel_item->subType == "Keyword Search")) {
            $channels[] = $channel_item;
        }
    }

    if($withcontrols) {
?>
<script type="text/javascript">
    $(document).ready(function() {
        //place an event handler for the submit button
        $("input#termtomonitorsubmit").click(function() {
            // Submit the value
            var termtosubmit = $("input#termtomonitortext").val();

            // Add the two channels
            if(termtosubmit != "") {
                // Not empty - add two channels
                var twitterSearchAdded = false;
                var googleNewsAdded = false;

                // Add twitter channel
                var updatePeriod = 1; //default
                var json =
                    '{"type":"Twitter",'+
                     '"subType":"Search",'+
                     '"name":"'+termtosubmit+'",'+
                     '"updatePeriod":'+updatePeriod+','+
                     '"parameters":';

                // Add parameters

                json += '{"SearchKeyword":"'+termtosubmit+'"},';

                json = json.substring(0, json.length - 1) + '}';
                $.post("<?php echo(url::base()); ?>api/channels/add",
                    { channel : json },
                    function(data) {
                        //TODO: do something if data.message != 'OK''
                        twitterSearchAdded = true;
                    },
                    'json'
                );

                // Add google news channel
                var json =
                    '{"type":"Google News",'+
                     '"subType":"Keyword Search",'+
                     '"name":"'+termtosubmit+'",'+
                     '"updatePeriod":'+updatePeriod+','+
                     '"parameters":';

                // Add parameters

                json += '{"SearchPhrase:":"'+termtosubmit+'"},';

                json = json.substring(0, json.length - 1) + '}';

                $.post("<?php echo(url::base()); ?>api/channels/add",
                    { channel : json },
                    function(data) {
                        //TODO: do something if data.message != 'OK''
                        googleNewsAdded = true;
                        
                        $.get("<?php echo(url::base()); ?>api/termstomonitor/get/false", function(data) {
                            $("div#termstomonitorcontent").html(data);
                            $("input#termtomonitortext").val("");
                        });
                    },
                    'json'
                );
            }
        });
    });
</script>

<div class="widget-seperator"></div>
<div class="widget-box">
    <div>
<?php
        if($isloggedin) {
?>
        <form action="#">
            <input type="text" id="termtomonitortext" />
            <input type="button" id="termtomonitorsubmit" value="Submit"/>
        </form>
<?php
        }
?>
    </div>
    <br/>
    <div id="termstomonitorcontent">
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