(function($) {
	$(document).ready(function() {
        window.quizID = "quiz-"+$('#quiz').data('quiz-id')+"-"+$("#quiz").data('curr-page');

        reloadQuizAnswers();
        bindQuizButtons();
    });	
    
    function reloadQuizAnswers() {
        var quizCookie = getQuizCookie();
        if(quizCookie != null){
            var selectedAnswers = quizCookie.split(',');
            $('.answers').find('.button').each(function() {
                $(this).removeClass('selected');
            });
            console.log(selectedAnswers)
            selectedAnswers.forEach(function(answer) {
                var target = ".button[data-answer-id='"+answer+"']";
                selectButton($(target));
                console.log($(target));
            });
        }

    }

    function getQuizCookie(){
        //Incase it starts with an empty string
        var value = "; " + document.cookie;
        var parts = value.split("; " + window.quizID + "=");
        if(parts.length == 2) return parts.pop().split(";").shift();
    }

    function bindQuizButtons() {
        $('.answers').find('.button').each(function() {
            $(this).click(function(e) {
                e.preventDefault();
                selectButton($(this)); 
            });
        })
    }

    function selectButton(target){
        console.log("selecting")
        console.log(target)
        var selected = target;
        var siblings = target.siblings();
        if(!selected.hasClass('selected')){
            selected.addClass('selected');
        }
        $(siblings).each(function(){
            $(this).removeClass('selected');
        });
        updateStoredAnswers();
    }

    function updateStoredAnswers(){
        var cookieString = "";
        var answersArray = [];
        $('.answers').find('.button.selected').each(function(){
            var answerID = $(this).data('answer-id');
            answersArray.push(answerID);
        });
        cookieString = window.quizID+"="+answersArray.toString();
        document.cookie = cookieString;
    }
})( jQuery );
