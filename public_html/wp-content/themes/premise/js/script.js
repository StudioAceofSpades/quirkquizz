(function($) {
	$(document).ready(function() {
        headerNavigation();
        cardLinks();
        shareResults();
	});	

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
