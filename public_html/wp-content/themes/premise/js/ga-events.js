(function($) {
	$(document).ready(function() {
        bindEvents();
    });	

    function bindEvents(e){
        // if the user lands on the landing page/starting page of the Quiz
        if ($('#start-quiz').length) {
            ga('send', 'event', 'Quiz', 'Loaded', 'Quiz Start');
        }
    }
})( jQuery );
