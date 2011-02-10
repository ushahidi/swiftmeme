/**
 * Created by IntelliJ IDEA.
 * User: Admin
 * Date: Sep 29, 2010
 * Time: 1:32:54 PM
 * To change this template use File | Settings | File Templates.
 */

function FilterViewWidget(baseUrl, widgetID, data) {
    this.RenderView = function() {
        $.post(baseUrl + "api/filterview/get/", { postdata: data },
            function(filterViewTemplate) {
                $(widgetID).html(filterViewTemplate);
            }
        );
    }
}

function TermsToMonitorWidget(baseUrl, widgetID) {
    this.RenderView = function() {
        $.get(baseUrl + "api/termstomonitor/get/true",
            function(termsToMonitorTemplate) {
                $(widgetID).html(termsToMonitorTemplate);
            }
        );
    }
}

function IdentifiedSourcesWidget(baseUrl, widgetID) {
    this.RenderView = function() {
        $.get(baseUrl + "api/identifiedsources/get/true",
            function(identitiedSourcesTemplate) {
                $(widgetID).html(identitiedSourcesTemplate);
            }
        );
    }
}