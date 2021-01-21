(function($) {
	$(document).ready(function() {
        trackViews();
	});	

    function trackViews() {
        var $quiz = $('#quiz');
        if($quiz.length > 0) {
            var quizID = $quiz.data('quiz-id');
            $.ajax({
                url: track_views_obj.ajaxurl, 
                data: {
                    'action'    : 'track_views_ajax',
                    'quiz'      : quizID,
                },
                success:function(data) {
                },
                error: function(errorThrown){
                }
            });
        }
    }

})( jQuery )
