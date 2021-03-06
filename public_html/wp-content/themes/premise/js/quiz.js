(function ($) {
    $(document).ready(function () {
        window.quizRef = "quiz-" + $('#quiz').data('quiz-id');
        window.quizID = "quiz-" + $('#quiz').data('quiz-id') + "-" + $("#quiz").data('curr-page');
        window.passthrough_strings = store('querystrings');
        if($('#quiz').length > 0) {
            getUserLocation();
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
                    store('found-location', 'true');
                    setWindowLocation();
                    initQuiz();
                },
                error: function () {
                    store("country-code", "US");
                    store('found-location', 'false');
                    setWindowLocation();
                    initQuiz();
                }
            });
        } else {
            setWindowLocation();
            initQuiz();
        }

        function initQuiz() {
            reloadQuizAnswers();
            bindQuizButtons();
            initAlert();
            setupCoins();
        }

        function setWindowLocation() {
            window.country = store("country-code");
            loadAds();
            renderQuestions();
        }
    }


    function renderQuestions() {
        var offset = 0;
        var countQuestions = 0;
        var button = window.nextBtn;
        if (store('found-location') == true) {
            var maxQuestionsForCountry = window.maxQuestionsByCountry[window.country];
        } else {
            var maxQuestionsForCountry = window.default_questions;
        }
        var allQuestions = window.allQuestions.slice(0, maxQuestionsForCountry);

        var maxQuestionsForCurrentPage = window.questionsByPage[window.currentPage] || 9999999;

        // Calculate our offset which is the number of questions already displayed
        for (var i = 0; i < Number(window.currentPage); i++) {
            if (window.questionsByPage[i]) {
                offset += window.questionsByPage[i];
            }
        }

        // Display each question for this page
        allQuestions.forEach((question, index) => {
            let answersHtml;
            if (offset <= index && countQuestions < maxQuestionsForCurrentPage) {
                let question = window.allQuestions[index];
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

                // Insert ads and answers into all of our questions
                $('.questions-container').append(`
                        <div class="card question">
                        <h2>Question ${index + 1} </h2>
                        <img src=${question.image}>
                        <h3>${question.question}</h3>
                        <div class="ad-slot above-answers-ad">
                            <!-- above_answers -->
                            ${window.adSettingsForCountry.above_answers ?

                        `<ins class="adsbygoogle"
                            style="display:block"
                            data-ad-client="ca-pub-4411421854869090"
                            data-ad-slot="9359346398"
                            data-ad-format="auto"
                            data-full-width-responsive="true"></ins>
                            <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                            </script>` : ""}
                                </div>

                        <div class="answers">
                        ${answersHtml}
                        </div>
                        </div>
                        <div class="ad-slot after-questions">
                        <!-- after_questions -->
                        ${window.adSettingsForCountry.after_questions ?

                        `<ins class="adsbygoogle"
                            style="display:block"
                            data-ad-client="ca-pub-4411421854869090"
                            data-ad-slot="9848083946"
                            data-ad-format="auto"
                            data-full-width-responsive="true"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>` : ""}
                    
                    </div >

                `);
                countQuestions++;
            }
        });
        $('[href="#start-quiz"]').click(function(e) {
            e.preventDefault();
            $('html,body').animate({
                scrollTop : ($('.questions-container').offset().top - $('.header').height()),
            }, 'fast');
        });
        outputPagination();
    }

    function outputPagination() {

        //storing current quiz page (starts at 1) and offset
        var offset = 0;
        for (var i = 0; i < Number(window.currentPage); i++) {
            if (window.questionsByPage[i]) {
                offset += window.questionsByPage[i];
            }
        }

        // Determine the most possible questions this quiz can display
        // based on the quiz_pages repeater set in Site Settings > Global Options.
        var maxGlobalQuestions = 0;
        for (var n = 1; n <= Object.keys(window.questionsByPage).length; n++) {
            maxGlobalQuestions += window.questionsByPage[n];
        }

        var maxCountryQuestions = window.maxQuestionsByCountry[window.country];
        var maxCurrentPageQuestions = window.questionsByPage[window.currentPage] || 9999999;
        var questionsDisplayed = offset + maxCurrentPageQuestions;
        var isLastPage = false;

        if (questionsDisplayed >= maxGlobalQuestions) {
            isLastPage = true;
        } else {
            if (questionsDisplayed >= maxCountryQuestions) {
                isLastPage = true;
            }
        }

        var buttonClass = 'button large ib purple';
        var buttonText = 'Next Page';
        var buttonLink = window.location.href.split('?')[0] + '?page-id=' + (Number(window.currentPage) + 1);
        var buttonID = '';
        var queryString = store('querystrings');

        if (isLastPage) {
            buttonID = 'funnel-button';
            buttonText = 'Get Results!';
            buttonLink = window.funnelURL;
        }

        $('.pagination-buttons')
            .append('<a href="' + buttonLink + '" id="' + buttonID + '" class="' + buttonClass + '">' + buttonText + '</a>');
        if (window.adSettingsForCountry.other_ads) {
            $('.pagination-buttons').append(`
                    <div class="ad-slot after-next-button">
                        <!-- after_next_button -->
                        <ins class="adsbygoogle"
                            style="display:block"
                            data-ad-client="ca-pub-4411421854869090"
                            data-ad-slot="3282675590"
                            data-ad-format="auto"
                            data-full-width-responsive="true"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                    </div>
                    
                    <div class="ad-slot after-all-quizzes">
                    <!-- after_all_related_quizzes -->
                    <ins class="adsbygoogle"
                        style="display:block"
                        data-ad-client="ca-pub-4411421854869090"
                        data-ad-slot="9591280683"
                        data-ad-format="auto"
                        data-full-width-responsive="true"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
                
                <div class="ad-slot after-first-related-quiz">
                    <!-- after_first_related_quiz -->
                    <ins class="adsbygoogle"
                        style="display:block"
                        data-ad-client="ca-pub-4411421854869090"
                        data-ad-slot="4316555663"
                        data-ad-format="auto"
                        data-full-width-responsive="true"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                    </div>`)

        }
        // setting up a goal in the Google Analytics that takes the click on the next page button on the Quiz page
        $('#advance-button').click(function (e) {
            ga('send', 'event', 'Quiz', 'Click', 'Next Page');
        });

        // setting up a goal if the user click on the Get Results button.
        $('#funnel-button').click(function (e) {
            ga('send', 'event', 'Quiz', 'Click', 'Get Results');
        });
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
            });
        });

        $('#funnel-button').click(function (e) {
            e.preventDefault();
            $('.alert-bg').show();
        });
    }

    function initAlert() {
        $('.cancel').click(function (e) {
            e.preventDefault();
            $('.alert-bg').hide();
            $('.alert-bg').removeClass('apple');
        });
        $('.back').click(function (e) {
            e.preventDefault();
            $('.pane-one').show();
            $('.pane-two').hide();
            $('.alert-bg').removeClass('apple');
        });
        $('.progress').click(function (e) {
            e.preventDefault();

            $('.pane-one').hide();
            $('.pane-two').show();
            $('.alert-bg').addClass('apple');
        });
        $('.navigate').click(function (e) {
            e.preventDefault();

            link = buildOutboundLink($(this));
            window.open($(this).data('webcal'), '_blank');

            setTimeout(function () {
                window.location.href = link;
            }, 3000);
        });
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
        var loaderText = btoa(window.loaderText);
        var newLink = link + "?l=" + loaderText + "&c=" + coinsVal;

        //adding a random possible answer to link
        var quizAnswer = possibleAnswers[Math.floor(Math.random() * possibleAnswers.length)]['result_text'];
        newLink = newLink + "&a=" + btoa(quizAnswer);

        //adding any other passthrough params
        var passthrough_strings = window.passthrough_strings;
        for (const querystring in passthrough_strings) {
            newLink += `& ${querystring}=${passthrough_strings[querystring]} `;
        }
        return newLink;
    }

    function loadAds() {
        country = window.country;
        window.adSettingsForCountry = { above_answers: true, after_questions: true, other_ads: true };
        if (window.adSettings && window.adSettings[window.country]) {
            window.adSettingsForCountry = window.adSettings[window.country];
        }
        var adscript = document.createElement("script");
        adscript.type = "text/javascript";
        adscript.src = "https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js";
        adscript.async;
        adscript.setAttribute('data-ad-client', 'ca-pub-4411421854869090');
        adscript.onload = function () {
        }
        document.body.appendChild(adscript);

    }

})(jQuery);
