(function($) {
	$(document).ready(function() {
        bindEvents();
    });	

    function bindEvents(e){
        // setting up a goal in the Google Analytics that takes the click on the next page button on the Quiz page
        $('#advance-button').click(function(e){
            ga('send', 'event', 'Quiz', 'Click', 'Next Page');
        });

        // if the user lands on the landing page/starting page of the Quiz
        if ($('#start-quiz').length) {
            ga('send', 'event', 'Quiz', 'Loaded', 'Quiz Start');
        }

        // setting up a goal if the user click on the Get Results button.
        $('#funnel-button').click(function(e){
            ga('send', 'event', 'Quiz', 'Click', 'Get Results');
        });
    }
})( jQuery );
