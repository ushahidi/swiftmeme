/**
 * Created by IntelliJ IDEA.
 * User: Admin
 * Date: Sep 29, 2010
 * Time: 1:32:36 PM
 * To change this template use File | Settings | File Templates.
 */

function MapViewWidget(baseUrl, widgetID) {
    this.RenderView = function() {
        $.get(baseUrl + "api/maps/get/" + widgetID,
            function(mapTemplate) {
                $(widgetID).html(mapTemplate);
            }
        );
    }
}

function TrendingKeywordsWidget(baseUrl, widgetID) {
    this.RenderView = function() {
        $.get(baseUrl + "api/trendingkeywords/get",
            function(graphTemplate) {
                $(widgetID).html(graphTemplate);
            }
        );
    }
}

function ActiveSourcesWidget(baseUrl, widgetID) {
    this.RenderView = function() {
        $.get(baseUrl + "api/activesources/get",
            function(graphTemplate) {
                $(widgetID).html(graphTemplate);
            }
        );
    }
}