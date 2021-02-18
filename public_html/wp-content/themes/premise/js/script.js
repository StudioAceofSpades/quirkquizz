(function ($) {
    $(document).ready(function () {
        initUI();

        if ($("#quiz").length > 0) {
            setupCoins();
        }

        // All quiz layouts are dependent on the location
        // so we have to wait for that before we build the
        // settings. initSettings fires off the function
        // that builds the quiz.
        initLocation(initSettings);
    });

    function initLocation(callback) {
        $.ajax({
            async       : true,
            method      : "GET",
            url         : "https://api.ipgeolocation.io/ipgeo?fields=country_code2",
            contentType : "application/json",
            dataType    : "json",
            success     : function (location) {
                callback(location.country_code2);
            },
            error: function () {
                callback('default');
            }
        });
    }

    function initSettings(userLocation) {
        var currentSettings = store('settings');
        if(currentSettings == null || currentSettings.id != window.quiz) {
            var offerID     = 'unknown';
            var affSub      = 'unknown';
            var affID       = '1';
            var page        = 1;
            var parameters  = getQueryParameters();

            if(parameters && currentSettings == null) {
                if('offer_id' in parameters) {
                    offerID = parameters.offer_id;
                }
                if('aff_sub' in parameters) {
                    affSub  = parameters.aff_sub;
                }
                if('aff_id' in parameters) {
                    affID   = parameters.aff_id;
                }
                if('page-id' in parameters) {
                    page = parameters['page-id'];
                }
            }
            
            var maxQuestions    = 0;
            var cms             = {};
            var offset          = 0;
            var pages           = {};

            var settings = {
                'id'            : window.quiz,
                'location'      : userLocation,
                'tracking'      : {
                    'offer_id'      : offerID,
                    'aff_sub'       : affSub,
                    'aff_id'        : affID,
                },
                'questions'     : window.questions,
                'pagination'    : {
                    'page'              : page,
                    'pages'             : window.pages,
                },
                'funnel'        : {
                    'url'       : window.funnelURL,
                    'text'      : window.loaderText,
                    'results'   : window.results,
                },
                'ads'           : window.ads,
                'flows'         : window.flows
            };

            if(typeof settings.pagination.pages[userLocation][page] == 'undefined') {
                settings.pagination['last'] = true;
            } else {
                settings.pagination['last'] = false;
            }
        } else {
            var settings    = store('settings');
            var parameters  = getQueryParameters();

            if(parameters && 'page-id' in parameters) {
                settings.pagination.page = parameters['page-id'];
            } else {
                settings.pagination.page = 1;
            }
            
            if(typeof settings.pagination.pages[settings.location][settings.pagination.page] == 'undefined') {
                settings.pagination['last'] = true;
            } else {
                settings.pagination['last'] = false;
            }
        }
        console.log(settings);
        store('settings', settings);
        initQuiz();
    }
    
    function initQuiz() {
        initFlow();
        bindTracking();
    }

    function initFlow() {
        var settings = store('settings');
        var location = settings.location;
        if(location == 'default') {
            location = 'US';
        }

        if(settings.flows[location] == 'default') {
            renderDefaultQuestions();
            renderDefaultPagination();
            initAlert();
            initDefaultAds();
        }
        if(settings.flows[location] == 'scroll') {
            lockHeader();
            renderScrollQuestions();
            initAlert();
            initScrollAds();
        }
        if(settings.flows[location] == 'ajax') {
            renderNoReloadQuestions();
            initAlert();
            refreshNoReloadAds();
        }
    }

    function lockHeader() {
        var $header = $('.header');
        $header.css({
            'position'  : 'relative'
        });
    }

    function renderScrollQuestions() {
        $('a[href="#start-quiz"]').click(function() {
            loadQuestion(1, function() {
                $('.question-title').fadeOut(200);
            });
        });
    }

    function loadQuestion(loadQuestion, callback) {
        scrollToDiv($('#quiz-top'), callback);

        var arena           = $('.questions-container');
        var settings        = store('settings');
        var index           = loadQuestion - 1;
        var location        = settings.location;
        var coin            = window.coin;
        var output          = '';
        var questionNumber  = loadQuestion;
        var question        = settings.questions[index];
        
        settings.pagination.page = settings.pagination.page + 1;

        store('settings', settings);

        output +=   '<div class="ad-slot before-question"></div>';

        if (question.type === "text") {
            var classes = "card question";
            if('response' in question) {
                classes += " answered";
            }
            output +=   '<div class="' + classes + '" data-question="' + questionNumber + '">' +
                            '<h2>Question ' + questionNumber + '</h2>' +
                            '<img src="' + question.image  + '">' +
                            '<h3>' + question.question + '</h3>' +
                            '<div class="ad-slot above-answers-ad">' +
                            '</div>' +
                            '<div class="ad-slot after-question"></div>' +
                            '<div class="answers">';

            for(i in question.answers) {
                var answers = question.answers;
                var classes = "button ib offwhite";
                if('response' in question && i == question.response) {
                    classes += " selected";
                }
                output += 
                '<a class="' + classes + '" href="#">' +
                    answers[i] +
                    '<div class="coins-get">' +
                        '<i class="fas fa-plus"></i>' + 
                        '<img src="' + coin + '">' +
                        '<img src="' + coin + '">' +
                        '<img src="' + coin + '">' +
                    '</div>' +
                '</a>';
            }

            output +=       '</div>' +
                        '</div>' +
                        '<div class="ad-slot after-answer"></div>';
        } 
        else {
            var classes = "card question";
            if('response' in question) {
                classes += " answered";
            }
            output +=   '<div class="'+classes+'" data-question="'+questionNumber+'">' +
                            '<h2>Question ' + questionNumber + '</h2>' +
                            '<h3>' + question.question + '</h3>' +
                            '<div class="ad-slot above-answers-ad">' +
                            '</div>' +
                            '<div class="answers">';

            for(x in question.answers) {
                answers = question.answers;
                output += 
                '<div class="button ib image offwhite" href="#">' +
                    '<div class="coins-get">' +
                        '<i class="fas fa-plus"></i>' +
                        '<img src="' + coin + '">' +
                        '<img src="' + coin + '">' +
                        '<img src="' + coin + '">' +
                    '</div>' +
                    '<div class="image-container" style="background-image: url(' + answers[x].image + ');">' +
                        '<span class="title">' + answers[x].title + '</span>' +
                    '</div>' +
                '</div>';
            }

            output +=       '</div>' +
                        '</div>' +
                        '<div class="ad-slot after-answer"></div>';
        }
        $output = $(output);
        arena.html($output);
        $output.hide().fadeIn(1000);
        bindScrollButtons();
        initScrollAds();
    }

    function bindScrollButtons() {
        $('.answers .button').click(function() {
            var settings            = store('settings');
            var next                = settings.pagination.page;
            var hasMoreQuestions    = false;
            var totalQuestions      = 0;
            for(page in settings.pagination.pages[settings.location]) {
                totalQuestions += settings.pagination.pages[settings.location][page];
            }
            if(next <= totalQuestions) {
                hasMoreQuestions    = true;
            } 
            if(hasMoreQuestions) {
                loadQuestion(next, function(){
                    var settings = store('settings');
                });
            } else {
                $('.alert-bg').show();
            }
        });
    }

    function scrollToDiv(el, callback) {
        $('html, body').animate({
            scrollTop: el.offset().top
        }, 500, 'swing', function() {
            if(typeof callback !== 'undefined') {
                callback();
            }
        });
    }

    function initScrollAds() {
        var settings = store('settings');
        var location = settings.location;

        if(location == 'default') {
            location = 'US';
        }

        var docked          = settings.ads[location]['docked_banner'];
        var afterQuestion   = settings.ads[location]['after_question'];
        var beforeQuestion  = settings.ads[location]['before_question'];
        var afterAnswer     = settings.ads[location]['after_answer'];

        if(docked) {
            $('.docked').html(getAdTag('3635852863'));
        }
        if(afterQuestion) {
            $('.after-question').html(getAdTag('2322771196'));
        }
        if(beforeQuestion) {
            $('.before-question').html(getAdTag('2322771196'));
        }
        if(afterAnswer) {
            $('.after-answer').html(getAdTag('7862038337'));
        }
    }
    function renderNoReloadQuestions() {
        var arena       = $('.questions-container');
        var settings    = store('settings');
        var page        = settings.pagination.page;
        var location    = settings.location;
        var pages       = settings.pagination.pages[location];
        var coin        = window.coin;
        var output      = '';

        var startQuestion   = 0;
        if(page > 1) {
            for(y = 0; y < page-1; y++) {
                startQuestion += pages[y];
            }
        }
        var endQuestion     = pages[page-1];
        
        // Display each question for this page
        for(n in settings.questions) {
            var questionNumber  = parseInt(parseInt(n) + 1);
            var question        = settings.questions[n];
            
            if(n >= startQuestion && n < endQuestion) {
                if (question.type === "text") {
                    var classes = "card question hidden";
                    output +=   '<div class="' + classes + '" data-question="' + questionNumber + '">' +
                                    '<h2>Question ' + questionNumber + '</h2>' +
                                    '<img src="' + question.image  + '">' +
                                    '<h3>' + question.question + '</h3>' +
                                    '<div class="answers">';

                    for(i in question.answers) {
                        var answers = question.answers;
                        var classes = "button ib offwhite";
                        if('response' in question && i == question.response) {
                            classes += " selected";
                        }
                        output += 
                        '<a class="' + classes + '" href="#">' +
                            answers[i] +
                            '<div class="coins-get">' +
                                '<i class="fas fa-plus"></i>' + 
                                '<img src="' + coin + '">' +
                                '<img src="' + coin + '">' +
                                '<img src="' + coin + '">' +
                            '</div>' +
                        '</a>';
                    }

                    output +=       '</div>' +
                                '</div>';
                } else {
                    var classes = "card question hidden";
                    output +=   '<div class="'+classes+'" data-question="'+questionNumber+'">' +
                                    '<h2>Question ' + questionNumber + '</h2>' +
                                    '<h3>' + question.question + '</h3>' +
                                    '<div class="answers">';

                    for(x in question.answers) {
                        answers = question.answers;
                        output += 
                        '<div class="button ib image offwhite" href="#">' +
                            '<div class="coins-get">' +
                                '<i class="fas fa-plus"></i>' +
                                '<img src="' + coin + '">' +
                                '<img src="' + coin + '">' +
                                '<img src="' + coin + '">' +
                            '</div>' +
                            '<div class="image-container" style="background-image: url(' + answers[x].image + ');">' +
                                '<span class="title">' + answers[x].title + '</span>' +
                            '</div>' +
                        '</div>';
                    }

                    output +=       '</div>' +
                                '</div>';
                }
            }
        }
        arena.html(output);
        initNoReloadQuizControls();
    }
    function initNoReloadQuizControls() {
        $('a[href="#start-quiz"]').click(function(e) {
            e.preventDefault();
            $('.question-title').hide();
            $('[data-question="1"]').removeClass('hidden');
        });
        $('.answers .button').click(function(e) {
            e.preventDefault();
            var $question = $(this).parents('.question');
            var $next = $question.next();
            if($next.length) {
                $question.hide();
                $next.show();
                refreshNoReloadAds();
            } else {
                $('.alert-bg').show();
            }
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
            link = buildOutboundLink($(this).attr('href'));
            window.open($(this).data('webcal'), '_blank');
            setTimeout(function () {
                window.location.href = link;
            }, 3000);
        });
    }

    function renderDefaultPagination() {
        var settings    = store('settings');
        var url         = window.location.href.split('?')[0];
        var button      = '<a href="' + url  + '?page-id=' + (settings.pagination.page + 1) + '" class="button large ib purple">Next Page</a>';

        if(settings.pagination.last) {
            button = '<a id="funnel-button" href="#" class="button large ib purple">Get Results!</a>';   
        }
        $('.pagination-buttons').append(button);
        $('#funnel-button').click(function (e) {
            e.preventDefault();
            $('.alert-bg').show();
        });
    }

    function initDefaultAds() {
        var settings = store('settings');
        var location = settings.location;

        if(location == 'default') {
            location = 'US';
        }

        var aboveAnswers    = settings.ads[location]['above_answers'];
        var afterQuestions  = settings.ads[location]['after_questions'];
        var other           = settings.ads[location]['other'];

        if(aboveAnswers) {
            $('.above-answers-ad').html(getAdTag('9359346398'));
        }
        if(afterQuestions) {
            $('.after-questions').html(getAdTag('9848083946'));
        }
        if(other) {
            $('.after-next-button').html(getAdTag("3282675590"));
            $('.after-first-related-quiz').html(getAdTag("4316555663"));
            $('.after-all-quizzes').html(getAdTag("9591280683"));
        }
    }

    function getAdTag(slot) {
        if(slot) {
            return '<ins class="adsbygoogle"' +
                         'style="display:block"' +
                         'data-ad-client="ca-pub-4411421854869090"' +
                         'data-ad-slot="' + slot + '"' +
                         'data-ad-format="auto"' +
                         'data-full-width-responsive="true"></ins>' +
                         '<script>' +
                         '(adsbygoogle = window.adsbygoogle || []).push({});' +
                         '</script>';
        }
        return "";
    }

    function renderDefaultQuestions() {
        var arena       = $('.questions-container');
        var settings    = store('settings');
        var page        = settings.pagination.page;
        var location    = settings.location;
        var pages       = settings.pagination.pages[location];
        var coin        = window.coin;
        var output      = '';

        var startQuestion   = 0;
        if(page > 1) {
            for(y = 0; y < page-1; y++) {
                startQuestion += pages[y];
            }
        }
        var endQuestion     = pages[page-1];
        
        // Display each question for this page
        for(n in settings.questions) {
            var questionNumber  = parseInt(parseInt(n) + 1);
            var question        = settings.questions[n];
            
            if(n >= startQuestion && n < endQuestion) {
                if (question.type === "text") {
                    var classes = "card question";
                    if('response' in question) {
                        classes += " answered";
                    }
                    output +=   '<div class="' + classes + '" data-question="' + questionNumber + '">' +
                                    '<h2>Question ' + questionNumber + '</h2>' +
                                    '<img src="' + question.image  + '">' +
                                    '<h3>' + question.question + '</h3>' +
                                    '<div class="ad-slot above-answers-ad">' +
                                    '</div>' +
                                    '<div class="answers">';

                    for(i in question.answers) {
                        var answers = question.answers;
                        var classes = "button ib offwhite";
                        if('response' in question && i == question.response) {
                            classes += " selected";
                        }
                        output += 
                        '<a class="' + classes + '" href="#">' +
                            answers[i] +
                            '<div class="coins-get">' +
                                '<i class="fas fa-plus"></i>' + 
                                '<img src="' + coin + '">' +
                                '<img src="' + coin + '">' +
                                '<img src="' + coin + '">' +
                            '</div>' +
                        '</a>';
                    }

                    output +=       '</div>' +
                                '</div>';
                } else {
                    var classes = "card question";
                    if('response' in question) {
                        classes += " answered";
                    }
                    output +=   '<div class="'+classes+'" data-question="'+questionNumber+'">' +
                                    '<h2>Question ' + questionNumber + '</h2>' +
                                    '<h3>' + question.question + '</h3>' +
                                    '<div class="ad-slot above-answers-ad">' +
                                    '</div>' +
                                    '<div class="answers">';

                    for(x in question.answers) {
                        answers = question.answers;
                        output += 
                        '<div class="button ib image offwhite" href="#">' +
                            '<div class="coins-get">' +
                                '<i class="fas fa-plus"></i>' +
                                '<img src="' + coin + '">' +
                                '<img src="' + coin + '">' +
                                '<img src="' + coin + '">' +
                            '</div>' +
                            '<div class="image-container" style="background-image: url(' + answers[x].image + ');">' +
                                '<span class="title">' + answers[x].title + '</span>' +
                            '</div>' +
                        '</div>';
                    }

                    output +=       '</div>' +
                                '</div>' +
                                '<div class="ad-slot after-questions"></div>';
                }
            }
        }
        arena.html(output);
        initQuizControls();
    }

    function initQuizControls() {
        bindAnswers();
    }

    function bindAnswers() {
        $('.answers .button').click(function (e) {
            e.preventDefault();

            addCoins(3, $(this));

            var settings        = store('settings');
            var $question       = $(this).parents('.question').addClass('answered');
            var questionIndex   = parseInt($question.data('question')) - 1;
            var answerIndex     = $(this).index();

            $question.find('.selected').removeClass('selected');
            $(this).addClass('selected');

            settings.questions[questionIndex]['response'] = answerIndex;
            store('settings', settings);

        });
    }
    
    function getCoins() {
        var coinsID     = "coins-" + window.quizRef;
        var coinTotal   = store(coinsID);
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
        console.log(question);
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

    function bindTracking() {
        // setting up a goal in the Google Analytics that takes the click on the next page button on the Quiz page
        $('#advance-button').click(function (e) {
            ga('send', 'event', 'Quiz', 'Click', 'Next Page');
        });

        // setting up a goal if the user click on the Get Results button.
        $('#funnel-button').click(function (e) {
            ga('send', 'event', 'Quiz', 'Click', 'Get Results');
        });
    }

    function getQueryParameters() {
        var inputs      = window.location.search.replace("?", "").split('&');
        var queryObj    = {};

        Object.keys(inputs).forEach(function (index) {
            var currentInput    = decodeURIComponent(inputs[index]).split('=');
            var inputName       = currentInput[0];
            var inputVal        = currentInput[1];

            if ((inputName != "") && (inputVal != "")) {
                queryObj[inputName] = inputVal;
            }
        });

        if (Object.keys(queryObj).length > 0) {
            return queryObj;
        } else {
            return false;
        }
    }

    function initUI() {
        bindNavigation();
        bindCards();
        bindFooter();
    }

    function bindFooter() {
        var inputs = window.location.search.replace("?", "").split('&');
        Object.keys(inputs).forEach(function (index) {
            var currentInput = decodeURIComponent(inputs[index]).split('=');
            var inputName = currentInput[0];
            var inputVal = currentInput[1];
            if ($('form#_form_12_').length) {
                $('form#_form_12_').prepend("<input type='hidden' name='" + inputName + "' value='" + inputVal + "' type='" + inputName + "'>");
            }
        });
    }

    function bindNavigation() {
        var $trigger = $('.nav-trigger');
        var $nav = $('.site-nav nav');
        $trigger.click(function (e) {
            e.preventDefault();
            $trigger.toggleClass('active');
            $nav.stop().slideToggle(250, 'swing');
        });
    }

    function bindCards() {
        $('.card.panel').click(function (e) {
            var $link = $(this).find('a');
            if ($link.length > 0) {
                window.location.href = $link.attr('href');
            }
        });
    }

    function toObject(arr) {
        var rv = {};
        for (var i = 0; i < arr.length; ++i)
            rv[i] = arr[i];
        return rv;
    }

    function buildOutboundLink(link) {
        var settings    = store('settings');
        var results     = settings.funnel.results;
        var result      = results[Math.floor(Math.random() * results.length)]['result_text'];

        return link + 
                "?l=" + btoa(settings.funnel.text) +
                "&c=" + btoa(getCoins()) +
                "&a=" + btoa(result) +
                "&aff_id=" + settings.tracking.aff_id +
                "&aff_sub=" + settings.tracking.aff_sub +
                "&offer_id=" + settings.tracking.offer_id;
    }

})(jQuery)
