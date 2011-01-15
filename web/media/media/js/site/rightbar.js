/**
 * Created by IntelliJ IDEA.
 * User: Admin
 * Date: Sep 29, 2010
 * Time: 1:32:54 PM
 * To change this template use File | Settings | File Templates.
 */

function FilterViewWidget(baseUrl, widgetID) {
    this.RenderView = function() {
        $.get(baseUrl + "api/filterviews/get",
            function(filterViewTemplate) {
                $(widgetID).html(filterViewTemplate);
            }
        );
    }
}

function TermsToMonitorWidget(baseUrl, widgetID) {
    this.RenderView = function() {
        $.get(baseUrl + "api/termstomonitor/get",
            function(termsToMonitorTemplate) {
                $(widgetID).html(termsToMonitorTemplate);
            }
        );
    }
}

function IdentifiedSourcesWidget(baseUrl, widgetID) {
    this.RenderView = function() {
        $.get(baseUrl + "api/identifiedsources/get",
            function(identitiedSourcesTemplate) {
                $(widgetID).html(identitiedSourcesTemplate);
            }
        );
    }
}