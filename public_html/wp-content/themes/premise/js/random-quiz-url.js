(function($) {
	$(document).ready(function() {
        randomURL();
	});	

    function randomURL() {
        $('.random-url').click(function(e) {
            e.preventDefault();

            $.ajax({
                url: random_quiz_url_obj.ajaxurl, 
                data: {
                    'action'    : 'random_quiz_url_ajax',
                },
                success:function(data) {
                    window.location.href = data;
                },
                error: function(errorThrown){
                }
            });
        });
    }

})( jQuery )
