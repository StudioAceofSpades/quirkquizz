(function($) {
	$(document).ready(function() {
        window.quizRef = "quiz-"+$('#quiz').data('quiz-id');
        window.quizID = "quiz-"+$('#quiz').data('quiz-id')+"-"+$("#quiz").data('curr-page');
        window.passthrough_strings = store('querystrings');
        getUserLocation();
        reloadQuizAnswers();
        bindQuizButtons();
        
        if($("#quiz").length > 0){
            setupCoins();
        }
    });	

    function getUserLocation(_callback){
        //If we dont already have a user location, we are going to retreive and store it
        if(store("country-code") == null){
            $.ajax ({
                async: true,
                method: "GET",
                url: "https://api.ipgeolocation.io/ipgeo?fields=country_code2",
                contentType: "application/json",
                dataType: "json",
                success: function (location) {
                    store("country-code", location.country_code2);
                    setWindowLocation();
                },
                error: function () {
                    console.log("loc not reached")
                }
            });
        } else {
            setWindowLocation();
        }

        function setWindowLocation(){
            window.country = store("country-code");
            loadAds();
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
        //In case it starts with an empty string
        var value = "; " + document.cookie;
        var parts = value.split("; " + window.quizID + "=");
        if(parts.length == 2) return parts.pop().split(";").shift();
    }

    function bindQuizButtons() {
        $('.answers').find('.button').each(function() {
            $(this).click(function(e) {
                e.preventDefault();
                addCoins(3, $(this));
                selectButton($(this)); 
                //validateQuiz();
            });
        });

        $('#funnel-button').click(function(e) {
            e.preventDefault();
            buildOutboundLink($(this));
        });
    }

    function selectButton(target){
        var selected = target;
        var siblings = target.siblings();
        if(!selected.hasClass('selected')){
            selected.addClass('selected');
            selected.parents('.question').addClass('answered');
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

    function getCoins() {
        //See if this quiz has a coin total set
        var coinsID = "coins-"+window.quizRef;
        var coinTotal = store(coinsID);
        if (coinTotal == null){
            coinTotal = 0;
        }
        return coinTotal;
    }

    function setCoins(total){
        var coinsID = "coins-"+window.quizRef;
        store(coinsID, total);
        setCoinCounter(total);
    }

    function addCoins(coinVal, answer) {
        var question = answer.parents(".question");
        if(!question.hasClass('answered')) {
            question.addClass('answered');
            var currCoins = getCoins();
            var newCoins = currCoins+coinVal;
            setCoins(newCoins);
        }
    }

    function setupCoins() {
        var coinTotal = getCoins();
        setCoinCounter(coinTotal);
    }

    function setCoinCounter(coinVal) {
        $("#coin-counter").addClass('active');
        $("#coin-total").text(coinVal);
    }

    function buildOutboundLink(btn) {
        var link = btn.attr('href');
        var possibleAnswers = window.possible_answers;
        var coinsVal = btoa(getCoins());
        var newLink = link+"&c="+coinsVal;
        //adding a random possible answer to link
        var quizAnswer = possibleAnswers[Math.floor(Math.random()*possibleAnswers.length)]['result_text'];
        newLink = newLink+"&a="+btoa(quizAnswer);
        //adding any other passthrough params
        var passthrough_strings = window.passthrough_strings;
        for(const querystring in passthrough_strings) {
            newLink += `&${querystring}=${passthrough_strings[querystring]}`;
        }
        window.location.href = newLink;
    }

    function loadAds(){
        country = window.country;
        if(window.country != "US"){
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
