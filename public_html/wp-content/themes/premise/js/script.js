(function($) {
	$(document).ready(function() {
        headerNavigation();
        cardLinks();
        shareResults();
        getResults();
        saveQueryInputs(window.location.search);
	});	

    function getResults() {
        var $quiz = $('#quiz');
        $('.get-results').click(function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var result = Math.floor(Math.random() * $quiz.data('num-results')) + 1;

            url += 'quiz_id=' + $quiz.data('quiz-id') + '&';
            url += 'result=' + result;
            window.location.href = url;
        });
    }

    //Takes a querystring as an input and saves all parameters as inputs in the contact form.
    function saveQueryInputs(input) {
        var inputs = input.replace("?", "").split('&');
        Object.keys(inputs).forEach(function(index){
            var currentInput = decodeURIComponent(inputs[index]).split('=');
            var inputName = currentInput[0];
            var inputVal = currentInput[1];
            if($('form#_form_7_').length){
                $('form#_form_7_').prepend("<input type='hidden' name='"+inputName+"' value='"+inputVal+"' type='"+inputName+"'>");
            }
        });
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
