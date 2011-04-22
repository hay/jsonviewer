// JSON viewer
// (c) 2008-2009 by Hay Kranen <http://www.haykranen.nl/projects/jsonviewer>
// Released under the MIT license, see jsonviewer.php
(function($){
    jQuery.fn.extend({
        "serializeObject" : function() {
            var form = this.serializeArray();
            var obj = {};

            for (var i in form) {
                obj[form[i].name] = form[i].value;
            }

            return obj;
        }
    });

    function submitForm() {
        $("#output").empty();
        $("#expandAll").hide();

        // Fade in the loading animation
        $("#loading").fadeIn();

        $.post('jsonviewer.php', $("#jsonviewer").serializeObject(), function(data) {
            // Data loaded, hide animation
            $("#loading").hide();

            // Put the data in the <pre> field
            $("#expandAll").show();
            $("#output").html(data);

            // Tree logic
            $("ul").hide();
            $("img.arrow").click(function() {
                if ($(this).attr('src') == "img/arrow.png") {
                    $(this).parent().find("ul:first").toggle();
                }

                // Scroll the screen to the arrow for easier navigation
                $("html").animate({scrollTop: $(this).offset().top - 10 + 'px'}, { duration: 300});
            });

            $("#root, #first").show();
        }, 'html');
    }

    function init() {
        var settings = {
            // These are some examples for in the url and data field
            "url"  : "http://twitter.com/statuses/user_timeline.json?screen_name=huskyr",
            "data" : "{ \"subject\" : \"Nice websites\", \"websites\" : [ \
                      \"http://www.haykranen.nl\", \"http://www.365dagenhay.nl\", \
                      \"http://www.google.com\"] }"
        };

        // Hide all objects with a 'jshide' class
        $(".jshide").hide();

        $("#toggle-options").click(function() {
            $("#options").toggle();
        });

        // Switch between 'url' and 'data' entry
        $("button[data-target]").click(function() {
            $(".dataentry").hide();
            $("#form" + $(this).attr('data-target')).show();
            $("button[data-target]").removeClass('current');
            $(this).addClass('current');
            $("#entrytype").val($(this).attr('data-target'));
        });

        // Fill 'url' and 'data' value fields with default examples
        $("#url").val(settings.url);
        $("#data").val(settings.data);

        // When clicking on the input field, delete that value if it is the default
        $("#url, #data").click(function() {
            if($(this).val() == settings[$(this).attr('id')]) {
                $(this).val('');
            } else {
                return true;
            }
        });

        $("#expandAll").click(function() {
            $("#output ul").show();
        });

        $("#btnClear").click(function() {
            var target = $("#entrytype").val();
            $("#" + target).val('');
            return false;
        });

        // Submit the form when the user hits enter
        $("#url, #data").keydown(function(e) {
            if (e.keyCode == "13") {
                submitForm();
                return false;
            } else {
                return true;
            }
        });

        // When clicking 'submit', show the loading GIF and get the data back via a
        // GET call from the PHP script
        $("#submit").click(function() {
            submitForm();

            // We don't want the form to submit, except if JavaScript is turned off
            return false;
        });
    }

    $(document).ready(init);
})(jQuery);