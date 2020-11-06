(function ($) {
    $(document).ready(function () {
        window.quizRef = "quiz-" + $('#quiz').data('quiz-id');
        window.quizID = "quiz-" + $('#quiz').data('quiz-id') + "-" + $("#quiz").data('curr-page');
        window.passthrough_strings = store('querystrings');
        getUserLocation();
        renderQuestions();
        reloadQuizAnswers();
        bindQuizButtons();
        //validateQuiz();
        //Only run on quiz pages
        if ($("#quiz").length > 0) {
            setupCoins();
        }
    });

    function getUserLocation(_callback) {
        //If we dont already have a user location, we are going to retreive and store it
        if (store("country-code") == null) {
            $.ajax({
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
                    store("country-code", "US");
                    setWindowLocation();
                }
            });
        } else {
            setWindowLocation();
        }

        function setWindowLocation() {
            window.country = store("country-code");
            loadAds();
        }
    }

    function renderQuestions() {
        console.log('country', window.country);
        console.log('allQuestions', window.allQuestions);
        //storing current quiz page (starts at 1) and offset
        var offset = 0;
        for (var i = 0; i < Number(window.currentPage); i++) {
            if (window.questionsByPage[i]) {
                offset += window.questionsByPage[i];
            }
        }
        console.log(offset);
        //stores country's max question count; for example US: 10
        console.log('jsbutton', window.nextBtn);
        var button = window.nextBtn;
        var maxQuestionsForCountry = window.maxQuestionsByCountry[window.country];
        //retrieving the array of questions ex. for US: [0, 10]
        var allQuestions = window.allQuestions.slice(0, maxQuestionsForCountry);
        var maxQuestionsForCurrentPage = window.questionsByPage[window.currentPage] || 9999999;
        //looping through all questions by text/image answer types and appending to questions-container


        var countQuestions = 0;
        allQuestions.forEach((question, index) => {
            let answersHtml;
            if (offset <= index && countQuestions < maxQuestionsForCurrentPage) {
                let question = window.allQuestions[index];
                console.log(question.answerType);
                if (question.answerType === "text") {
                    let answers = question.answers.map(answer => {
                        return `

                        <a data-answer-id="${answer.name}" class="button ib offwhite" href="#">
                        ${answer.text}
                        <div class="coins-get"><i class="fas fa-plus"></i><img src="${question.coins}"><img src="${question.coins}"><img src="${question.coins}"></div>
                        </a>`
                    });
                    answersHtml = answers.join("");
                } else {
                    let answers = question.imageAnswers.map(imganswer => {

                        return `
                        <div data-answer-id="${imganswer.name}" class="button ib image offwhite" href="#">
                        <div class="coins-get"><i class="fas fa-plus"></i><img src="${question.coins}"><img src="${question.coins}"><img src="${question.coins}"></div>
                        <div class="image-container" style="background-image: url(${imganswer.url});">
                        <span class="title">${imganswer.title}</span>
                        </div>
                        </div>
    
                        `
                    });
                    answersHtml = answers.join("");

                }

                $('.questions-container').append(`
                        <div class="card question">
                        <h2>Question ${index + 1} </h2>
                        <img src=${question.image}>
                        <h3>${question.question}</h3>
                        <div class="ad-slot above-answers-ad">
                            <!-- above_answers -->
                                <ins class="adsbygoogle"
                                style="display:block"
                                data-ad-client="ca-pub-4411421854869090"
                                data-ad-slot="9359346398"
                                data-ad-format="auto"
                                data-full-width-responsive="true"></ins>
                                <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                                </script>
                                </div>
                        <div class="answers">
                        ${answersHtml}
                        </div>
                        </div>
                        <div class="ad-slot after-questions">
                        <!-- after_questions -->
                        <ins class="adsbygoogle"
                            style="display:block"
                            data-ad-client="ca-pub-4411421854869090"
                            data-ad-slot="9848083946"
                            data-ad-format="auto"
                            data-full-width-responsive="true"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                `);


                countQuestions += 1;
            }
        })


        $('.pagination-buttons').append(
            `<a href=${button.href} id=${button.id} ${button.class}>${button.text}</a>`


        );


    }

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

    function bindQuizButtons() {
        $('.answers').find('.button').each(function () {
            $(this).click(function (e) {
                e.preventDefault();
                addCoins(3, $(this));
                selectButton($(this));
                //validateQuiz();
            });
        });

        $('#funnel-button').click(function (e) {
            e.preventDefault();
            buildOutboundLink($(this));
        });



    }

    //Really simple hacky quiz validation, assuming we are going to swap this out with something else entirely at some point
    function validateQuiz() {
        var unansweredQ = false;
        var selectedAnswer = false;
        $('.question').each(function () {
            //This is to make sure we dont try to validate any questions without answers/intro cards.
            if ($(this).find('.answers').length > 0) {
                selectedAnswer = false;
                $(this).find('.answers').find('.button').each(function () {
                    if ($(this).hasClass('selected')) {
                        selectedAnswer = true;
                    }
                })
                if (selectedAnswer != true) {
                    unansweredQ = true;
                }
            }
        });
        if (unansweredQ == true) {
            disableButton();
        } else {
            enableButton();
        }
    }

    function disableButton() {
        $(".next-page-btn").addClass("disabled");
    }

    function enableButton() {
        $(".next-page-btn").removeClass("disabled");
    }

    function selectButton(target) {
        var selected = target;
        var siblings = target.siblings();
        if (!selected.hasClass('selected')) {
            selected.addClass('selected');
            selected.parents('.question').addClass('answered');
        }
        $(siblings).each(function () {
            $(this).removeClass('selected');
        });
        updateStoredAnswers();
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

    function getCoins() {
        //See if this quiz has a coin total set
        var coinsID = "coins-" + window.quizRef;
        var coinTotal = store(coinsID);
        if (coinTotal == null) {
            coinTotal = 0;
        }
        return coinTotal;
    }

    function setCoins(total) {
        var coinsID = "coins-" + window.quizRef;
        store(coinsID, total);
        setCoinCounter(total);
    }

    function addCoins(coinVal, answer) {
        var question = answer.parents(".question");
        if (!question.hasClass('answered')) {
            question.addClass('answered');
            var currCoins = getCoins();
            var newCoins = currCoins + coinVal;
            setCoins(newCoins);
        }
        var audioURL = $("#audiolink").val();
        var coinsound = new Audio(audioURL);
        coinsound.play();
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
        var newLink = link + "&c=" + coinsVal;
        //adding a random possible answer to link
        var quizAnswer = possibleAnswers[Math.floor(Math.random() * possibleAnswers.length)]['result_text'];
        newLink = newLink + "&a=" + btoa(quizAnswer);
        //adding any other passthrough params
        var passthrough_strings = window.passthrough_strings;
        for (const querystring in passthrough_strings) {
            newLink += `&${querystring}=${passthrough_strings[querystring]}`;
        }
        window.location.href = newLink;
        console.log('asdas', window.possible_answers);
    }

    function loadAds() {
        country = window.country;
        if (window.country != "US") {
            var adscript = document.createElement("script");
            adscript.type = "text/javascript";
            adscript.src = "https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js";
            adscript.async;
            adscript.setAttribute('data-ad-client', 'ca-pub-4411421854869090');
            adscript.onload = function () {
                console.log("Ads Loaded");
            }
            document.body.appendChild(adscript);
        }
    }

})(jQuery);
