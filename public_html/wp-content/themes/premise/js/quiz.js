(function ($) {
    $(document).ready(function () {

        // Configure base quiz settings

        /*
        reloadQuizAnswers();
        bindQuizButtons();
        initAlert();
        // Only run on quiz pages
        if ($("#quiz").length > 0) {
            setupCoins();
        }
        */
    });

    /*

    function reloadQuizAnswers() {
        var quizCookie = getQuizCookie();
        if (quizCookie != null) {
            var selectedAnswers = quizCookie.split(',');
            $('.answers').find('.button').each(function () {
                $(this).removeClass('selected');
            });
            selectedAnswers.forEach(function (answer) {
                var target = ".button[data-answer-id='" + answer + "']";
                selectButton($(target));
            });
        }

    }

    function getQuizCookie() {
        //Incase it starts with an empty string
        var value = "; " + document.cookie;
        var parts = value.split("; " + window.quizID + "=");
        if (parts.length == 2) return parts.pop().split(";").shift();
    }


    function updateStoredAnswers() {
        var cookieString = "";
        var answersArray = [];
        $('.answers').find('.button.selected').each(function () {
            var answerID = $(this).data('answer-id');
            answersArray.push(answerID);
        });
        cookieString = window.quizID + "=" + answersArray.toString();
        document.cookie = cookieString;
    }
*/
})(jQuery);
