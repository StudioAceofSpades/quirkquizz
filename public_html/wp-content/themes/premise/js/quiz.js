(function($) {
	$(document).ready(function() {
        window.quizID = "quiz-"+$('#quiz').data('quiz-id')+"-"+$("#quiz").data('curr-page');
        getUserLocation();
        reloadQuizAnswers();
        bindQuizButtons();
        validateQuiz();
    });	

    function getUserLocation(_callback){
        //If we dont already have a user location, we are going to retreive and store it
        if(store("country-code") == null){
            console.log("requesting location");
            $.ajax ({
                async: true,
                method: "GET",
                url: "https://api.ipgeolocation.io/ipgeo?fields=country_code2",
                contentType: "application/json",
                dataType: "json",
                success: function (location) {
                    store("country-code", location.country_code2);
                    window.country = store("country-code");
                    loadAds();
                    chooseResultsLink();
                },
                error: function () {
                    console.log("api not reached.")
                }
            });
        } else {
            setWindowLocation();
        }

        function setWindowLocation(){
            window.country = store("country-code");
            loadAds();
            chooseResultsLink();
        }
    }
    
    function reloadQuizAnswers() {
        var quizCookie = getQuizCookie();
        if(quizCookie != null){
            var selectedAnswers = quizCookie.split(',');
            $('.answers').find('.button').each(function() {
                $(this).removeClass('selected');
            });
            selectedAnswers.forEach(function(answer) {
                var target = ".button[data-answer-id='"+answer+"']";
                selectButton($(target));
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
                validateQuiz();
            });
        })
    }

    //Really simple hacky quiz validation, assuming we are going to swap this out with something else entirely at some point
    function validateQuiz() {
        var unansweredQ = false;
        var selectedAnswer = false;
        $('.question').each(function() {
            //This is to make sure we dont try to validate any questions without answers/intro cards.
            if($(this).find('.answers').length > 0){
                selectedAnswer = false;
                $(this).find('.answers').find('.button').each(function() {
                    if($(this).hasClass('selected')){
                        selectedAnswer = true;
                    }
                })
                if(selectedAnswer != true){
                    unansweredQ = true;
                }
            }
        });
        if(unansweredQ == true){
            disableButton();
        }else{
            enableButton();
        }
    }

    function disableButton(){
        $("#advance-button").addClass("disabled");
    }

    function enableButton(){
        $("#advance-button").removeClass("disabled");
        chooseResultsLink();
    }

    function selectButton(target){
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

    function chooseResultsLink(){
        country = window.country;
        if(country && (country == "US")){
            $("#results-button").attr('href', $("#survey_link").val());
        }else{
            $('#results-button').attr('href', $("#result_link").val());
        }
    }

    function loadAds(){
        country = window.country;
        if((country == null) || (country != "US")){
            console.log("Loading Ads");
            var adscript = document.createElement("script");
            adscript.type = "text/javascript";
            adscript.src = "https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js";
            adscript.async;
            adscript.setAttribute('data-ad-client', 'ca-pub-4411421854869090');
            adscript.onload = function(){
                console.log("Ads Loaded");
            }
            document.body.appendChild(adscript);
        }
    }

})( jQuery );
