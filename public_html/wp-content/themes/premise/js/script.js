(function ($) {
    $(document).ready(function () {
        headerNavigation();
        cardLinks();
        shareResults();
        saveQueryInputsToFooterForm();
        storeQueryStringArray();
    });

    //Takes a querystring as an input and saves all parameters as inputs in the contact form.
    function saveQueryInputsToFooterForm() {
        var inputs = window.location.search.replace("?", "").split('&');
        Object.keys(inputs).forEach(function (index) {
            var currentInput = decodeURIComponent(inputs[index]).split('=');
            var inputName = currentInput[0];
            var inputVal = currentInput[1];

            if ($('form#_form_12_').length) {
                $('form#_form_12_').prepend("<input type='hidden' name='" + inputName + "' value='" + inputVal + "' type='" + inputName + "'>");
            }
        });
    }

    function storeQueryStringArray() {
        var inputs = window.location.search.replace("?", "").split('&');
        var queryObj = {};


        //Then before the object gets stored, iterate over defaultParams and for each item in that array, 
        //check to see if that element is contained in queryObj , and if not just add that element to the queryObj array

        var defaultParams = {
            offer_id: "unknown",
            aff_sub: "unknown",
            aff_id: "1"
        }

        Object.keys(inputs).forEach(function (index) {
            var currentInput = decodeURIComponent(inputs[index]).split('=');
            var inputName = currentInput[0];
            var inputVal = currentInput[1];
            //Dont save page-ids. Dont save empty values.
            if (!(inputName == "page-id") && ((inputName != "") && (inputVal != ""))) {
                queryObj[inputName] = inputVal;
            }

        });

        Object.keys(defaultParams).forEach(function (key) {
            if (!queryObj.hasOwnProperty(key)) {
                queryObj[key] = defaultParams[key];
            }
        })

        //We only want to actually store this if we dont have a value stored, and if our query object has more than 0 items.
        if ((store('querystrings') == null) && (Object.keys(queryObj).length > 0)) {
            store('querystrings', JSON.stringify(queryObj));
        }
    }

    function headerNavigation() {
        var $trigger = $('.nav-trigger');
        var $nav = $('.site-nav nav');

        $trigger.click(function (e) {
            e.preventDefault();
            $trigger.toggleClass('active');
            $nav.stop().slideToggle(250, 'swing');
        });
    }

    function cardLinks() {
        $('.card.panel').click(function (e) {
            var $link = $(this).find('a');
            if ($link.length > 0) {
                window.location.href = $link.attr('href');
            }
        });
    }

    function shareResults() {
        $('.share-target').click(function (e) {
            e.preventDefault();

            $('.share').slideToggle('fast');
        });
    }

})(jQuery)
