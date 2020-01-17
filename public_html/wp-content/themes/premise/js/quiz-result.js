(function($) {
	$(document).ready(function() {
        quizResult();
	});	

    function quizResult() {
        if($('#results').length > 0) {
            $.ajax({
                url: quiz_result_obj.ajaxurl, 
                data: {
                    'action'    : 'quiz_result_ajax',
                },
                success:function(data) {
                    console.log(data);
                },
                error: function(errorThrown){
                }
            });
        }
    }

})( jQuery )
