(function($) {
	$(document).ready(function() {
        loadMore();
	});	

    function loadMore() {
        var $trigger    = $('.load-trigger');
        var $arena      = $('.arena');

        if($trigger.length > 0) {
            $trigger.click(function(e) {
                e.preventDefault();

                var cardCount = $arena.find('.card').length;
                $.ajax({
                    url: load_more_obj.ajaxurl, 
                    data: {
                        'action'    : 'load_more_ajax',
                        'cards'     : cardCount
                    },
                    success:function(data) {

                        if(data == 'false') {
                            $trigger.remove();
                            $arena.append('<div class="col-12 no-more-posts"><p>Looks like there are not any more quizzes. Check back soon!</p></div>');
                        } else {
                            $arena.append(data);
                        }
                    },
                    error: function(errorThrown){
                    }
                });
            });
        }
    }

})( jQuery )
