(function($) {
	$(document).ready(function() {
        headerNavigation();
        cardLinks();
        shareResults();
        getResults();
        saveQueryInputsToFooterForm();
        storeQueryStringArray();
	});	

    function getResults() {
        var $quiz = $('#quiz');
        $('.get-results').click(function(e) {
            e.preventDefault();
            var comp = new RegExp(location.host);
            var url = $(this).attr('href');
            if(comp.test($(this).attr('href'))){
                var result = Math.floor(Math.random() * $quiz.data('num-results')) + 1;
                url += 'quiz_id=' + $quiz.data('quiz-id') + '&';
                url += 'result=' + result;
            }
            //clearing stored query strings out of memory.
            store("querystrings", null);
            window.location.href = url;
        });
    }

    //Takes a querystring as an input and saves all parameters as inputs in the contact form.
    function saveQueryInputsToFooterForm() {
        var inputs = window.location.search.replace("?", "").split('&');
        Object.keys(inputs).forEach(function(index){
            var currentInput = decodeURIComponent(inputs[index]).split('=');
            var inputName = currentInput[0];
            var inputVal = currentInput[1];

            if($('form#_form_7_').length){
                $('form#_form_7_').prepend("<input type='hidden' name='"+inputName+"' value='"+inputVal+"' type='"+inputName+"'>");
            }
        });
    }

    function storeQueryStringArray() {
        var inputs = window.location.search.replace("?", "").split('&');
        var queryObj = {};
        Object.keys(inputs).forEach(function(index){
            var currentInput = decodeURIComponent(inputs[index]).split('=');
            var inputName = currentInput[0];
            var inputVal = currentInput[1];
            //Dont save page-ids. Dont save empty values.
            if(!(inputName == "page-id") && ((inputName != "") && (inputVal != ""))){
                queryObj[inputName] = inputVal;
            }
        });
        //We only want to actually store this if we dont have a value stored, and if our query object has more than 0 items.
        if((store('querystrings') == null) && (Object.keys(queryObj).length > 0)) {
            store('querystrings', JSON.stringify(queryObj));
        }
    }

    function headerNavigation() {
        var $trigger    = $('.nav-trigger');
        var $nav        = $('.site-nav nav');

        $trigger.click(function(e) {
            e.preventDefault();
            $trigger.toggleClass('active');
            $nav.stop().slideToggle(250,'swing');
        });
    }

    function cardLinks() {
        $('.card.panel').click(function(e) {
            var $link = $(this).find('a');
            if($link.length > 0 ) {
                window.location.href = $link.attr('href');
            }
        });
    }

    function shareResults() {
        $('.share-target').click(function(e) {
            e.preventDefault();

            $('.share').slideToggle('fast');
        });
    }

})( jQuery )
