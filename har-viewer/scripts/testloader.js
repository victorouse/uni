if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
} else {
    // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}

var xml = $("#current a").attr("file") + ".xml";

$(document).ready(function() {
    $.ajax({
        type: "GET",
        url: "test_logs/" + xml,
        dataType: "xml",
        success: parseTest,
        error: noTest
    });
});

/*
$.urlParam = function(name){
    var results = new RegExp('[\\?&amp;]' + name + '=([^&amp;#]*)').exec(window.location.href);
    if (results == null) {
        return 0;
    } else {
        return results[1] || 0;
    }
}
*/

function parseTest(xml) {
    var testNum = 0;
    // var current = $.urlParam('step');

    $(xml).find("testcase").each(function() {
        var name = "<div class='name'>" + "Description: " + ($(this).attr("name")) + "</div>";
        var time = "<div class='time'>" + "Time: " + ($(this).attr("time")) + " seconds"  + "</div>";
        var failure = ($(this).find("failure"));

        if (failure[0] != null) {
            $("#testsuite").append("<div class='failure'>" + "<strong>Test " + (testNum + 1) + ":</strong>"
                + name 
                + time
                + "</div");
        } else {
            $("#testsuite").append("<div class='test'>" + "<strong>Test " + (testNum + 1) + ":</strong>"
                + name 
                + time
                + "</div");
        }

        /*
        if (testNum == current) {
            $("#testsuite").append("<div class='selectedtest'>" + "Step " + (testNum + 1) + "." 
                + name 
                + time 
                + "</div");
        } else {
            $("#testsuite").append("<div class='test'>" + "Step " + (testNum + 1) + "." 
                + name 
                + time 
                + "</div");
        }
        */

        testNum++;
    });
}

function noTest() {
    $("#testsuite").append("<div class='test'>No tests exist for this step</div>");
}

