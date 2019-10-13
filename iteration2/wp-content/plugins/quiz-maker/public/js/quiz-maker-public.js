(function ($) {
    'use strict';
    (function (d, s, id) {
        let js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0&appId=1204514392893219&autoLogAppEvents=1';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    $(document).ready(function () {
        $.fn.goTo = function() {
            $('html, body').animate({
                scrollTop: $(this).offset().top - 100 + 'px'
            }, 'slow');
            return this; // for chaining...
        }
        if (!String.prototype.trim) {
            (function() {
                String.prototype.trim = function() {
                    return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
                };
            })();
        }
        $(document).find('.for_quiz_rate_avg.ui.rating').rating('disable');
        
        var ays_quiz_container, ays_quiz_container_id; //flag to prevent quick multi-click glitches
        let myOptions, myQuizOptions;
        
        function time_limit(e) {
            let quizId = $(e.target).parents('.ays-quiz-container').find('input[name="ays_quiz_id"]').val();
            myOptions = JSON.parse(atob(options[quizId]));
            if(typeof myOptions.answers_rw_texts == 'undefined'){
                myOptions.answers_rw_texts = 'on_passing';
            }
            let quizOptionsName = 'quizOptions_'+quizId;
            myQuizOptions = [];
            if(typeof window[quizOptionsName] !== 'undefined'){
                for(let i = 0; i < window[quizOptionsName].length; i++ ){
                    myQuizOptions.push(JSON.parse(atob(window[quizOptionsName][i])));
                }
            }
            if ($(this).parents('.step').next().find('.information_form').length === 0 ){
                let quizMusic = $(e.target).parents('.ays-quiz-container').find('.ays_quiz_music');
                if(quizMusic.length !== 0){                
                    let soundEls = $(document).find('.ays_music_sound');
                    $(e.target).parents('.ays-quiz-container').find('.ays_music_sound').removeClass('ays_display_none');                
                    if(!isPlaying(quizMusic.get(0))){
                        $(e.target).parents('.ays-quiz-container').find('.ays_quiz_music')[0].play();
                        audioVolumeIn($(e.target).parents('.ays-quiz-container').find('.ays_quiz_music')[0]);
                    }
                }
                $(e.target).parents('.ays-quiz-container').find('.ays-live-bar-wrap').css({'display': 'block'});
                $(e.target).parents('.ays-quiz-container').find('.ays-live-bar-percent').css({'display': 'inline-block'});
                $(e.target).parents().eq(1).parent().find('input.ays-start-date').val(GetFullDateTime());
            }
            if ($(this).parents('.step').next().find('.information_form').length === 0 && myOptions.enable_timer == 'on') {
                $(e.target).parents().eq(1).parent().find('div.ays-quiz-timer').hide(800);
                let timer = parseInt($(e.target).parents().eq(1).parent().find('div.ays-quiz-timer').attr('data-timer'));
                let pageTitle = $(document).find('title');
                let pageTitleText = $(document).find('title').html();
                let timeForShow = "";
                if (!isNaN(timer) && myOptions.timer !== undefined) {
                    if (myOptions.timer === timer && timer !== 0) {
                        timer += 2;
                        if (timer !== undefined) {
                            let countDownDate = new Date().getTime() + (timer * 1000);
                            let x = setInterval(function () {
                                let now = new Date().getTime();
                                let distance = countDownDate - Math.ceil(now/1000)*1000;
                                let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                if(hours <= 0){
                                    hours = null;
                                }else if (hours < 10) {
                                    hours = '0' + hours;
                                }
                                if (minutes < 10) {
                                    minutes = '0' + minutes;
                                }
                                if (seconds < 10) {
                                    seconds = '0' + seconds;
                                }
                                timeForShow =  ((hours==null)? "" : (hours + ":")) + minutes + ":" + seconds;
                                if(distance <=1000){
                                    timeForShow =  ((hours==null) ? "" : "00:") + "00:00";
                                    $(e.target).parents().eq(1).parent().find('div.ays-quiz-timer').html(timeForShow);
                                    if(myOptions.quiz_timer_in_title == 'on'){
                                        pageTitle.html( timeForShow + " - " + pageTitleText );
                                    }
                                }else{
                                    $(e.target).parents().eq(1).parent().find('div.ays-quiz-timer').html(timeForShow);
                                    if(myOptions.quiz_timer_in_title == 'on'){
                                        pageTitle.html( timeForShow + " - " + pageTitleText );
                                    }
                                }
                                
                                $(e.target).parents().eq(1).parent().find('.ays_quiz_timer_container').show();
                                $(e.target).parents().eq(1).parent().find('div.ays-quiz-timer').show(500);
                                if($(e.target).parents('.ays-quiz-container').find('.ays-quiz-timer').length === 0){
                                    clearInterval(x);
                                    if(myOptions.quiz_timer_in_title == 'on'){
                                        pageTitle.html( pageTitleText );
                                    }
                                }
                                if($(e.target).parents('.ays-quiz-container').find('.ays_finish.action-button').hasClass("ays_timer_end")){
                                    clearInterval(x);
                                    if(myOptions.quiz_timer_in_title == 'on'){
                                        pageTitle.html( pageTitleText );
                                    }
                                }
                                if (distance <= 1) {
                                    clearInterval(x);
                                    if(myOptions.quiz_timer_in_title == 'on'){
                                        pageTitle.html( pageTitleText );
                                    }
                                    let totalSteps = $(e.target).parents().eq(1).parent().find('div.step').length;
                                    let currentStep = $(e.target).parents().eq(1).parent().find('div.step.active-step');
                                    let thankYouStep = $(e.target).parents().eq(1).parent().find('div.step.ays_thank_you_fs');
                                    let infoFormLast = thankYouStep.prev().find('div.information_form');
                                    if(infoFormLast.length == 0){
                                        if (currentStep.hasClass('ays_thank_you_fs') === false) {
                                            let steps = totalSteps - 3;
                                            $(e.target).parents().eq(1).parent().find('div.step').each(function (index) {
                                                if ($(this).hasClass('ays_thank_you_fs')) {
                                                    $(this).addClass('active-step')
                                                }else{
                                                    $(this).css('display', 'none');                                                
                                                }
                                            });
                                            $(e.target).parents().eq(1).parent().find('input.ays_finish').trigger('click');
                                        }
                                    }else{
                                        currentStep.parents('.ays-quiz-container').find('.ays-quiz-timer').parent().remove();
                                        $(e.target).parents('.ays-quiz-container').find('.ays-live-bar-wrap').removeClass('rubberBand').addClass('bounceOut');
                                        $(e.target).parents('.ays-quiz-container').find('.ays-live-bar-percent').removeClass('rubberBand').addClass('bounceOut');
                                        setTimeout(function () {
                                            $(e.target).parents('.ays-quiz-container').find('.ays-live-bar-wrap').css('display','none');
                                            $(e.target).parents('.ays-quiz-container').find('.ays-live-bar-percent').css('display','none');
                                        },300);
                                        
                                        $(e.target).parents().eq(1).parent().find('div.step').each(function (index) {
                                            $(this).css('display', 'none');
                                            $(this).removeClass('active-step')
                                        });
                                        aysAnimateStep(ays_quiz_container.data('questEffect'), currentStep, infoFormLast.parent());
//                                        infoFormLast.parent().css('display', 'flex');
                                        infoFormLast.parent().addClass('active-step'); 
                                    }
                                }
                            }, 1000);
                        }
                    } else {
                        alert('Wanna cheat??');
                        window.location.reload();
                    }
                }

            }else{
                $(this).parents('.step').next().find('.information_form').find('.ays_next.action-button').on('click', function () {
                    if($(this).parents('.step').find('.information_form').find('.ays_next.action-button').hasClass('ays_start_allow')){
                        time_limit(e);
                    }
                });
            }
        }
        
        $(document).find('.ays_next.start_button').on('click', time_limit);
        
        $(document).find('.ays_next.start_button').on('click', function(){
            ays_quiz_container_id = $(this).parents(".ays-quiz-container").attr("id");
            ays_quiz_container = $('#'+ays_quiz_container_id);
            $(this).parents('div.step').removeClass('active-step');
            $(this).parents('div.step').next().addClass('active-step');
            ays_quiz_container.css('padding-bottom', '0px');
            let ancnoxneriQanak = $(this).parents('.ays-questions-container').find('.ays_quizn_ancnoxneri_qanak');
            let aysQuizReteAvg = $(this).parents('.ays-questions-container').find('.ays_quiz_rete_avg');
            
            setTimeout(function(){
                ays_quiz_container.css('border-radius', myOptions.quiz_border_radius + 'px');
                ays_quiz_container.find('.step').css('border-radius', myOptions.quiz_border_radius + 'px');
            }, 400);

            aysAnimateStep(ays_quiz_container.data('questEffect'), aysQuizReteAvg);
            aysAnimateStep(ays_quiz_container.data('questEffect'), ancnoxneriQanak);
            
            if ($(this).parents('.step').next().find('.information_form').length === 0 ){
                var questions_count = $(this).parents('form').find('div[data-question-id]').length;
                var curent_number = $(this).parents('form').find('div[data-question-id]').index($(this).parents('div[data-question-id]')) + 1;
                var next_sibilings_count = $(this).parents('form').find('.ays_question_count_per_page').val();
                if(parseInt(next_sibilings_count) > 0 &&
                   ($(this).parents('.step').attr('data-question-id') || 
                    $(this).parents('.step').next().attr('data-question-id'))){
                    var final_width = ((parseInt(next_sibilings_count)) / questions_count * 100) + "%";
                    if($(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').hasClass('ays-live-bar-count')){
                        $(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(parseInt(next_sibilings_count)));
                    }else{                
                        $(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(final_width));
                    }
                    $(this).parents('.ays-quiz-container').find('.ays-live-bar-fill').animate({'width': final_width}, 1000);
                }else{
                    var final_width = ((curent_number+1) / questions_count * 100) + "%";
                    if($(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').hasClass('ays-live-bar-count')){
                        $(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(curent_number+1));
                    }else{                
                        $(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(final_width));
                    }
                    $(this).parents('.ays-quiz-container').find('.ays-live-bar-fill').animate({'width': final_width}, 1000);
                }
            }
        });

        $(document).find('.ays-quiz-container input').on('focus', function () {
            $(window).on('keydown', function (event) {
                if (event.keyCode === 13) {
                    return false;
                }
            });
        });

        $(document).find('.ays-quiz-container input').on('blur', function () {
            $(window).off('keydown');
        });
        
        $.each($(document).find('.ays_block_content'), function () {
            if ($(document).find('.ays_block_content').length != 0) {
                var ays_block_element = $(this).parents().eq(2);
                ays_block_element.find('input.ays-start-date').val(GetFullDateTime());
                ays_block_element.find('div.ays-quiz-timer').slideUp(500);
                let timer = parseInt(ays_block_element.find('div.ays-quiz-timer').attr('data-timer'));
                let timerInTitle = ays_block_element.find('div.ays-quiz-timer').data('showInTitle');
                let tabTitle = document.title;
                setTimeout(function(){
                if (timer !== NaN) {
                    timer += 2;
                    if (timer !== undefined) {
                        let countDownDate = new Date().getTime() + (timer * 1000);
                        var x = setInterval(function () {
                            let now = new Date().getTime();
                            let distance = countDownDate - Math.ceil(now/1000)*1000;
                            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            let timeForShow = "";
                            if(hours <= 0){
                                hours = null;
                            }else if (hours < 10) {
                                hours = '0' + hours;
                            }
                            if (minutes < 10) {
                                minutes = '0' + minutes;
                            }
                            if (seconds < 10) {
                                seconds = '0' + seconds;
                            }
                            timeForShow =  ((hours==null)? "" : (hours + ":")) + minutes + ":" + seconds;
                            if(distance <=1000){
                                timeForShow = ((hours==null) ? "" : "00:") + "00:00";
                                ays_block_element.find('div.ays-quiz-timer').html(timeForShow);
                                if(timerInTitle){
                                    document.title = timeForShow + " - " + tabTitle ;
                                }
                            }else{
                                ays_block_element.find('div.ays-quiz-timer').html(timeForShow);
                                if(timerInTitle){
                                    document.title = timeForShow + " - " + tabTitle ;
                                }
                            }
                            ays_block_element.find('div.ays-quiz-timer').slideDown(500);
                            var ays_block_element_redirect_url = ays_block_element.find('.ays_redirect_url').text();
                            if (distance <= 1) {
                                clearInterval(x);
                                let totalSteps = ays_block_element.find('div.step').length;
                                let currentStep = ays_block_element.eq(2).find('div.step.active-step');
                                let currentStepIndex = ays_block_element.eq(2).find('div.step.active-step').index();
                                if (currentStep.hasClass('ays_thank_you_fs') === false) {
                                    let steps = totalSteps - 3;
                                    ays_block_element.find('div.step').each(function (index) {
                                        if (index >= (currentStepIndex - 1) && index <= steps) {
                                            $(this).remove();
                                        }
                                    });
                                    window.location = ays_block_element_redirect_url;
                                }
                            }
                        }, 1000);
                    }
                }
                }, 1000);
            }
        });
        
        $(document).find('button.ays_check_answer').on('click', function (e) {
            let thisAnswerOptions;
            thisAnswerOptions = myQuizOptions[$(e.target).parents('.ays-quiz-container').find('.ays-text-input').index($(e.target).prev().prev())];
            if($(this).parent().find('.ays-text-input').val() !== ""){
                if ($(e.target).parents('form[id^="ays_finish_quiz"]').hasClass('enable_correction')) {
                    if($(e.target).parents('.step').hasClass('not_influence_to_score')){
                        return false;
                    }
                    $(this).css({
                        animation: "bounceOut .5s",
                    });
                    setTimeout(function(){
                        $(e.target).parent().find('.ays-text-input').css('width', '100%');
                        $(e.target).css("display", "none");
                    },480);
                    $(e.target).parent().find('.ays-text-input').css('background-color', '#eee');
                    $(this).parent().find('.ays-text-input').attr('disabled', 'disabled');
                    $(this).attr('disabled', 'disabled');
                    $(this).off('change');
                    $(this).off('click');
                    let userAnsweredText = $(this).parent().find('.ays-text-input').val().trim();
                    if(userAnsweredText.toLowerCase() == thisAnswerOptions.question_answer.toLowerCase()){
                        $(this).parent().find('.ays-text-input').css('background-color', 'rgba(39,174,96,0.5)');
                        $(this).parent().find('input[name="ays_answer_correct[]"]').val(1);
                    }else{
                        $(this).parent().find('.ays-text-input').css('background-color', 'rgba(243,134,129,0.8)');
                        $(this).parent().find('input[name="ays_answer_correct[]"]').val(0);
                        let rightAnswerText = '<div class="ays-text-right-answer">'+
                            thisAnswerOptions.question_answer+
                            '</div>';
                        $(this).parents('.ays-quiz-answers').append(rightAnswerText);
                        $(this).parents('.ays-quiz-answers').find('.ays-text-right-answer').slideDown(500);
                    }
                }
            }
        });
        
        $(document).on('change', 'input[name^="ays_questions"]', function (e) {
            if($(e.target).parents('.step').hasClass('not_influence_to_score')){
                if($(e.target).attr('type') === 'radio') {
                    $(e.target).parents('.ays-quiz-answers').find('.checked_answer_div').removeClass('checked_answer_div');
                    $(e.target).parents('.ays-field').addClass('checked_answer_div');
                }
                if($(e.target).attr('type') === 'checkbox') {
                    if(!$(e.target).parents('.ays-field').hasClass('checked_answer_div')){
                        $(e.target).parents('.ays-field').addClass('checked_answer_div');
                    }else{
                        $(e.target).parents('.ays-field').removeClass('checked_answer_div');
                    }
                } 
                var checked_inputs = $(e.target).parents().eq(1).find('input:checked');
                if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') && checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                    if (checked_inputs.attr('type') === 'radio') {
                        checked_inputs.parents('div[data-question-id]').find('.ays_next').trigger('click');                    
                    }
                }
                return false;
            }
            if ($(e.target).parents().eq(4).hasClass('enable_correction')) {                
                if ($(e.target).parents().eq(1).find('input[name="ays_answer_correct[]"]').length !== 0) {
                    var checked_inputs = $(e.target).parents().eq(1).find('input:checked');
                    
                    if (checked_inputs.attr('type') === "radio") {
                        checked_inputs.next().addClass('answered');
                        (checked_inputs.prev().val() == 1) ? checked_inputs.next().addClass('correct') : checked_inputs.next().addClass('wrong');
                        if (checked_inputs.prev().val() == 1) {
                            if(myOptions.answers_rw_texts && (myOptions.answers_rw_texts == 'on_passing' || myOptions.answers_rw_texts == 'on_both')){
                                if(! $(e.target).parents('.step').hasClass('not_influence_to_score')){
                                    $(e.target).parents().eq(3).find('.right_answer_text').slideDown(250);
                                }
                                if($(e.target).parents().eq(3).find('.right_answer_text').hasClass('ays_do_not_show')){
                                    if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') && checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                        checked_inputs.parents('div[data-question-id]').find('.ays_next').trigger('click');
                                    }
                                }else{
                                    setTimeout(function(){
                                        if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') && checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                            checked_inputs.parents('div[data-question-id]').find('.ays_next').trigger('click');
                                        }
                                    }, 4000);
                                }
                            }else{
                                if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') && checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                    checked_inputs.parents('div[data-question-id]').find('.ays_next').trigger('click');
                                }
                            }
                        }
                        else {
                            $(e.target).parents('.ays-quiz-answers').find('input[name="ays_answer_correct[]"][value="1"]').parent().addClass('correct_div').addClass('checked_answer_div');
                            $(e.target).parents('.ays-quiz-answers').find('input[name="ays_answer_correct[]"][value="1"]').next().next().addClass('correct answered');
                            if(myOptions.answers_rw_texts && (myOptions.answers_rw_texts == 'on_passing' || myOptions.answers_rw_texts == 'on_both')){
                                if(! $(e.target).parents('.step').hasClass('not_influence_to_score')){
                                    $(e.target).parents().eq(3).find('.wrong_answer_text').slideDown(250);
                                }
                                if($(e.target).parents().eq(3).find('.wrong_answer_text').hasClass('ays_do_not_show')){
                                    if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') && checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                        checked_inputs.parents('div[data-question-id]').find('.ays_next').trigger('click');
                                    }
                                }else{
                                    setTimeout(function(){
                                        if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') && checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                            checked_inputs.parents('div[data-question-id]').find('.ays_next').trigger('click');
                                        }
                                    }, 4000);
                                }
                            }else{
                                if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') && checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                    checked_inputs.parents('div[data-question-id]').find('.ays_next').trigger('click');
                                }
                            }
                        }
                        $(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]').attr('disabled', true);
                        $(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]').off('change');
                    }else if(checked_inputs.attr('type') === "checkbox"){
                        if (checked_inputs.length === 1) {
                            (checked_inputs.prev().val() == 1) ? checked_inputs.next().addClass('correct answered') : checked_inputs.next().addClass('wrong answered');
                        }else{
                            var added_text = false;
                            for (var i = 0; i < checked_inputs.length; i++) {
                                (checked_inputs.eq(i).prev().val() == 1) ? checked_inputs.eq(i).next().addClass('correct answered') : checked_inputs.eq(i).next().addClass('wrong answered');
                            }
                        }
                        $(e.target).attr('disabled', true);
                        $(e.target).off('change');
                    }
                }
            }else{                
                if($(e.target).attr('type') === 'radio') {
                    $(e.target).parents('.ays-quiz-answers').find('.checked_answer_div').removeClass('checked_answer_div');
                    $(e.target).parents('.ays-field').addClass('checked_answer_div');
                }
                if($(e.target).attr('type') === 'checkbox') {
                    if(!$(e.target).parents('.ays-field').hasClass('checked_answer_div')){
                        $(e.target).parents('.ays-field').addClass('checked_answer_div');
                    }else{
                        $(e.target).parents('.ays-field').removeClass('checked_answer_div');
                    }
                } 
                var checked_inputs = $(e.target).parents().eq(1).find('input:checked');
                if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') && checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                    if (checked_inputs.attr('type') === 'radio') {
                        checked_inputs.parents('div[data-question-id]').find('.ays_next').trigger('click');                    
                    }
                }
            }
        });

        $(document).on('input', '.information_form input[name="ays_user_phone"]', function(){
            if ($(this).attr('type') !== 'hidden') {
                if($(this).val() != ''){
                    if (!(/^\d+$/.test($(this).val()))) {
                        $(this).addClass('ays_red_border');
                    }else{
                        $(this).addClass('ays_green_border');
                    }
                }
            }
        });
        
        $(document).on('input', '.information_form input[name="ays_user_email"]', function(){
            if ($(this).attr('type') !== 'hidden') {
                if($(this).val() != ''){
                    if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($(this).val()))) {
                        $(this).addClass('ays_red_border');
                    }else{
                        $(this).addClass('ays_green_border');
                    }
                }
            }
        });

        $(document).find('.ays_next').on('click', function(e){
            e.preventDefault();
            ays_quiz_container = $(this).parents(".ays-quiz-container");
            if (!($(this).hasClass('start_button'))) {
                if ($(this).parents('.step').find('input[required]').length !== 0) {
                    var empty_inputs = 0;
                    var required_inputs = $(this).parents('.step').find('input[required]');
                    $(this).parents('.step').find('.ays_red_border').removeClass('ays_red_border');
                    for (var i = 0; i < required_inputs.length; i++) {
                        switch(required_inputs.eq(i).attr('name')){
                            case "ays_user_phone": {
                                if (!(/^\d+$/.test(required_inputs.eq(i).val()))) {
                                    required_inputs.eq(i).addClass('ays_red_border');
                                    required_inputs.eq(i).addClass('shake');
                                    empty_inputs++;
                                }
                                break;
                            }
                            case "ays_user_email": {
                                if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(required_inputs.eq(i).val()))) {
                                    required_inputs.eq(i).addClass('ays_red_border');
                                    required_inputs.eq(i).addClass('shake');
                                    empty_inputs++;
                                }
                                break;
                            }
                            default:{
                                if (required_inputs.eq(i).val() === '' &&
                                    required_inputs.eq(i).attr('type') !== 'hidden') {
                                    required_inputs.eq(i).addClass('ays_red_border');
                                    required_inputs.eq(i).addClass('shake');
                                    empty_inputs++;
                                }
                                break;
                            }
                        }
                    }
                    var empty_inputs2 = 0;
                    let phoneInput = $(this).parents('.step').find('input[name="ays_user_phone"]');
                    let emailInput = $(this).parents('.step').find('input[name="ays_user_email"]');
                    if(phoneInput.val() != ''){
                        if (!(/^\d+$/.test(phoneInput.val()))) {
                            if (phoneInput.attr('type') !== 'hidden') {
                                phoneInput.addClass('ays_red_border');
                                phoneInput.addClass('shake');
                                empty_inputs2++;
                            }
                        }else{
                            phoneInput.addClass('ays_green_border');
                        }
                    }
                    if(emailInput.val() != ''){
                        if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(emailInput.val()))) {
                            if (emailInput.attr('type') !== 'hidden') {
                                emailInput.addClass('ays_red_border');
                                emailInput.addClass('shake');
                                empty_inputs2++;
                            }
                        }else{
                            emailInput.addClass('ays_green_border');
                        }
                    }
                    let errorFields = $(this).parents('.step').find('.ays_red_border');
                    if (empty_inputs2 !== 0 || empty_inputs !== 0) {
                        setTimeout(function(){
                            errorFields.each(function(){
                                $(this).removeClass('shake');
                            });
                        }, 500);
                        setTimeout(function(){
                            required_inputs.each(function(){
                                $(this).removeClass('shake');
                            });
                        }, 500);
                        return false;
                    }else{
                        $(this).addClass('ays_start_allow');
                    }
                }else{
                    if ($(this).parents('.step').find('.information_form').length !== 0 ){
                        var empty_inputs = 0;
                        let phoneInput = $(this).parents('.step').find('input[name="ays_user_phone"]');
                        let emailInput = $(this).parents('.step').find('input[name="ays_user_email"]');
                        if(phoneInput.val() != ''){
                            if (!(/^\d+$/.test(phoneInput.val()))) {
                                if (phoneInput.attr('type') !== 'hidden') {
                                    phoneInput.addClass('ays_red_border');
                                    phoneInput.addClass('shake');
                                    empty_inputs++;
                                }
                            }else{
                                phoneInput.addClass('ays_green_border');
                            }
                        }
                        if(emailInput.val() != ''){
                            if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(emailInput.val()))) {
                                if (emailInput.attr('type') !== 'hidden') {
                                    emailInput.addClass('ays_red_border');
                                    emailInput.addClass('shake');
                                    empty_inputs++;
                                }
                            }else{
                                emailInput.addClass('ays_green_border');
                            }
                        }
                        let errorFields = $(this).parents('.step').find('.ays_red_border');
                        if (empty_inputs !== 0) {
                            setTimeout(function(){
                                errorFields.each(function(){
                                    $(this).removeClass('shake');
                                });
                            }, 500);
                            return false;
                        }
                        $(this).addClass('ays_start_allow');
                    }
                }
            }else{
                if ($(this).parents('.step').next().find('input[required]').length === 0) {
                    $(this).parents('.step').next().find('.information_form').find('.ays_next.action-button').addClass('ays_start_allow');
                }
            }
            
            if (animating) return false;
            animating = true;
            current_fs = $(this).parent().parent();
            next_fs = $(this).parent().parent().next();
            var questions_count = $(this).parents('form').find('div[data-question-id]').length;
            var curent_number = $(this).parents('form').find('div[data-question-id]').index($(this).parents('div[data-question-id]')) + 1;
            var next_sibilings_count = $(this).parents('form').find('.ays_question_count_per_page').val();
            if(parseInt(next_sibilings_count)>0 && ($(this).parents('.step').attr('data-question-id') || $(this).parents('.step').next().attr('data-question-id'))){

                var current_fs_index = $(this).parents('form').find('.step').index($(this).parents('.step'));
                if($(this).parents('.step').attr('data-question-id')){
                    current_fs = $(this).parents('form').find('.step').slice(current_fs_index-parseInt(next_sibilings_count),current_fs_index+1);
                }else{
                    current_fs = $(this).parent();
                }
                if(questions_count === curent_number){
                    if(current_fs.hasClass('.information_form').length !== 0){
                        current_fs.parents('.ays-quiz-container').find('.ays-quiz-timer').parent().fadeOut(500);
                        setTimeout(function () {
                            current_fs.parents('.ays-quiz-container').find('.ays-quiz-timer').parent().remove();
                        },500);
                    }
                }
                
                if(curent_number != questions_count){
                    if(($(this).hasClass('ays_finish')) == false){
                        if (!($(this).hasClass('start_button'))) {
                            let count_per_page = Math.floor(questions_count/parseInt(next_sibilings_count));
                            let nextCountQuestionsPerPage = questions_count-curent_number;
                            var current_width = $(this).parents('.ays-quiz-container').find('.ays-live-bar-fill').width();
                            var final_width = ((curent_number+parseInt(next_sibilings_count)) / questions_count * 100) + "%";
                            if(nextCountQuestionsPerPage < parseInt(next_sibilings_count)){
                                final_width = ((curent_number+nextCountQuestionsPerPage) / questions_count * 100) + "%";
                            }
                            if($(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').hasClass('ays-live-bar-count')){
                                if(nextCountQuestionsPerPage < parseInt(next_sibilings_count)){
                                    $(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(curent_number+parseInt(nextCountQuestionsPerPage)));
                                }else{
                                    $(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(curent_number+parseInt(next_sibilings_count)));
                                }
                            }else{
                                $(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(final_width));
                            }
                            $(this).parents('.ays-quiz-container').find('.ays-live-bar-fill').animate({'width': final_width}, 1000);
                        }
                    }
                }else{
                    $(this).parents('.ays-quiz-container').find('.ays-live-bar-wrap').removeClass('rubberBand').addClass('bounceOut');
                    setTimeout(function () {
                        $(e.target).parents('.ays-quiz-container').find('.ays-live-bar-wrap').css('display','none');
                    },300)
                }
                var next_siblings = $(this).parents('.step').nextAll('.step').slice(0,parseInt(next_sibilings_count));

                if($(this).parents('form').find('div[data-question-id]').index($(this).parents('.step'))+1 !== $(this).parents('form').find('div[data-question-id]').length) {
                    for (var z = 0; z < next_siblings.length; z++) {
                        if (next_siblings.eq(z).attr('data-question-id') === undefined) {
                            next_siblings.splice(z);
                        }
                    }
                }else{
                    if(next_siblings.length !== 1) {
                        next_siblings.splice(next_siblings.length - 1);
                    }
                }
                $(e.target).parents().eq(3).find('input[name^="ays_questions"]').attr('disabled', false);
                for(var i=0 ;i<next_siblings.length-1;i++){
                    next_siblings.eq(i).find('.ays_previous').remove();
                    if(i===next_siblings.length-1 && next_siblings.eq(i).find('input[name^="ays_questions"]').attr('type') === 'checkbox'){
                        next_siblings.eq(i).find('.ays_buttons_div').find('input.ays_next').removeClass('ays_display_none');
                        continue;
                    }
                    if(i===next_siblings.length-1 && next_siblings.eq(i).find('textarea[name^="ays_questions"]').attr('type') === 'text'){
                        next_siblings.eq(i).find('.ays_buttons_div').find('input.ays_next').removeClass('ays_display_none');
                        continue;
                    }
                    if(i === next_siblings.length-1 && next_siblings.eq(i).find('input[name^="ays_questions"]').attr('type') === 'number'){
                        next_siblings.eq(i).find('.ays_buttons_div').find('input.ays_next').removeClass('ays_display_none');
                        continue;
                    }
                    next_siblings.eq(i).find('.ays_next').remove();
                }

                if(current_fs.hasClass('ays-abs-fs')){
                    current_fs = $(this).parent().parent();
                    next_fs = $(this).parent().parent().next();
                    current_fs.removeClass('active-step');
                    let counterClass = "";
                    switch(ays_quiz_container.data('questEffect')){
                        case "shake":
                            counterClass = ays_quiz_container.data('questEffect');
                        break;
                        case "fade":
                            counterClass = "fadeIn";
                        break;
                        case "none":
                            counterClass = "";
                        break;
                        default:
                            counterClass = ays_quiz_container.data('questEffect');
                        break;
                    }
                    next_fs.find('.ays-question-counter').addClass(counterClass);
//                    next_fs.find('.ays-question-counter').addClass('shake');
                }

//                next_siblings.css('display', 'flex');
                if(next_siblings.eq(next_siblings.length-1).find('input[name^="ays_questions"]').attr('type')==='checkbox' && 
                   next_siblings.eq(next_siblings.length-1).find('.ays_buttons_div').find('.ays_fa_arrow_right').hasClass('ays_display_none') && 
                   next_siblings.eq(next_siblings.length-1).find('.ays_buttons_div').find('.ays_next').hasClass('ays_display_none')){
                   next_siblings.eq(next_siblings.length-1).find('.ays_buttons_div').find('input.ays_next').removeClass('ays_display_none');
                }
                if(next_siblings.eq(next_siblings.length-1).find('input[name^="ays_questions"]').attr('type')==='checkbox' && 
                         $(document).find(".ays-questions-container .ays_qm_enable_arrows").val() == 'true'){
                   next_siblings.eq(next_siblings.length-1).find('.ays_buttons_div').find('input.ays_next').addClass('ays_display_none');
                   next_siblings.eq(next_siblings.length-1).find('.ays_buttons_div').find('.ays_fa_arrow_right').removeClass('ays_display_none');
                }
                if(next_siblings.eq(next_siblings.length-1).find('textarea[name^="ays_questions"]').attr('type')==='text' && 
                   next_siblings.eq(next_siblings.length-1).find('.ays_buttons_div').find('.ays_fa_arrow_right').hasClass('ays_display_none') && 
                   next_siblings.eq(next_siblings.length-1).find('.ays_buttons_div').find('.ays_next').hasClass('ays_display_none')){
                   next_siblings.eq(next_siblings.length-1).find('.ays_buttons_div').find('input.ays_next').removeClass('ays_display_none');
                }
                if(next_siblings.eq(next_siblings.length-1).find('textarea[name^="ays_questions"]').attr('type')==='text' && 
                         $(document).find(".ays-questions-container .ays_qm_enable_arrows").val() == 'true'){
                   next_siblings.eq(next_siblings.length-1).find('.ays_buttons_div').find('input.ays_next').addClass('ays_display_none');
                   next_siblings.eq(next_siblings.length-1).find('.ays_buttons_div').find('.ays_fa_arrow_right').removeClass('ays_display_none');
                }
                if(next_siblings.eq(next_siblings.length-1).find('input[name^="ays_questions"]').attr('type')==='number' && 
                   next_siblings.eq(next_siblings.length-1).find('.ays_buttons_div').find('.ays_fa_arrow_right').hasClass('ays_display_none') && 
                   next_siblings.eq(next_siblings.length-1).find('.ays_buttons_div').find('.ays_next').hasClass('ays_display_none')){
                   next_siblings.eq(next_siblings.length-1).find('.ays_buttons_div').find('input.ays_next').removeClass('ays_display_none');
                }
                if(next_siblings.eq(next_siblings.length-1).find('input[name^="ays_questions"]').attr('type')==='number' && 
                         $(document).find(".ays-questions-container .ays_qm_enable_arrows").val() == 'true'){
                   next_siblings.eq(next_siblings.length-1).find('.ays_buttons_div').find('input.ays_next').addClass('ays_display_none');
                   next_siblings.eq(next_siblings.length-1).find('.ays_buttons_div').find('.ays_fa_arrow_right').removeClass('ays_display_none');
                }
                $('.active-step').removeClass('active-step');
                next_siblings.eq(0).addClass('active-step');
                
                aysAnimateStep(ays_quiz_container.data('questEffect'), current_fs, next_siblings);
                
                setTimeout(function(){
                    if(next_siblings.find('.ays-text-field').length > 0){
                        if(next_siblings.find('.ays-text-field').width() < 250){
                            next_siblings.find('.ays-text-field').css({
                                'flex-wrap': 'wrap',
                                'justify-content': 'center',
                                'padding': '5px'
                            });
                            next_siblings.find('.ays-text-field').find('input.ays-text-input').css('margin-bottom', '5px');
                        }
                    }
                },2000);
            }else{
                current_fs = $(this).parent().parent();
                next_fs = $(this).parent().parent().next();
                var questions_count = $(this).parents('form').find('div[data-question-id]').length;
                var curent_number = $(this).parents('form').find('div[data-question-id]').index($(this).parents('div[data-question-id]'))+1;
                if(questions_count === curent_number){
                    if(current_fs.hasClass('.information_form').length !== 0){
                        current_fs.parents('.ays-quiz-container').find('.ays-quiz-timer').parent().fadeOut(500);
                        setTimeout(function () {
                            current_fs.parents('.ays-quiz-container').find('.ays-quiz-timer').parent().remove();
                        },500);
                    }
                }
                if(curent_number != questions_count){
                    if(($(this).hasClass('ays_finish')) == false){
                        if (!($(this).hasClass('start_button'))) {
                            var current_width = $(this).parents('.ays-quiz-container').find('.ays-live-bar-fill').width();
                            var final_width = ((curent_number+1) / questions_count * 100) + "%";
                            if($(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').hasClass('ays-live-bar-count')){
                                $(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(curent_number+1));
                            }else{
                                $(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(final_width));
                            }
                            $(this).parents('.ays-quiz-container').find('.ays-live-bar-fill').animate({'width': final_width}, 1000);
                        }
                    }
                }else{
                    $(this).parents('.ays-quiz-container').find('.ays-live-bar-wrap').removeClass('rubberBand').addClass('bounceOut');
                    setTimeout(function () {
                        $(e.target).parents('.ays-quiz-container').find('.ays-live-bar-wrap').css('display','none');
                    },300)
                }
                $(e.target).parents().eq(3).find('input[name^="ays_questions"]').attr('disabled', false);
                if (current_fs.hasClass('ays-abs-fs')) {
                    current_fs = $(this).parent().parent().parent();
                    next_fs = $(this).parent().parent().parent().next();
                    let counterClass = "";
                    switch(ays_quiz_container.data('questEffect')){
                        case "shake":
                            counterClass = ays_quiz_container.data('questEffect');
                        break;
                        case "fade":
                            counterClass = "fadeIn";
                        break;
                        case "none":
                            counterClass = "";
                        break;
                        default:
                            counterClass = ays_quiz_container.data('questEffect');
                        break;
                    }
                    next_fs.find('.ays-question-counter').addClass(counterClass);
//                    next_fs.find('.ays-question-counter').addClass('shake');
                }
                current_fs.removeClass('active-step');
                next_fs.addClass('active-step');
                
                if(next_fs.find('input[name^="ays_questions"]').attr('type')==='checkbox' && 
                   next_fs.find('.ays_buttons_div').find('.ays_fa_arrow_right').hasClass('ays_display_none') && 
                   next_fs.find('.ays_buttons_div').find('.ays_next').hasClass('ays_display_none')){
                   next_fs.find('.ays_buttons_div').find('input.ays_next').removeClass('ays_display_none');
                }
                if(next_fs.find('input[name^="ays_questions"]').attr('type')==='checkbox' && 
                         $(document).find(".ays-questions-container .ays_qm_enable_arrows").val() == 'true'){
                   next_fs.find('.ays_buttons_div').find('input.ays_next').addClass('ays_display_none');
                   next_fs.find('.ays_buttons_div').find('.ays_fa_arrow_right').removeClass('ays_display_none');
                }
                if(next_fs.find('textarea[name^="ays_questions"]').attr('type')==='text' && 
                   next_fs.find('.ays_buttons_div').find('.ays_fa_arrow_right').hasClass('ays_display_none') && 
                   next_fs.find('.ays_buttons_div').find('.ays_next').hasClass('ays_display_none')){
                   next_fs.find('.ays_buttons_div').find('input.ays_next').removeClass('ays_display_none');
                }
                if(next_fs.find('textarea[name^="ays_questions"]').attr('type')==='text' && 
                         $(document).find(".ays-questions-container .ays_qm_enable_arrows").val() == 'true'){
                   next_fs.find('.ays_buttons_div').find('input.ays_next').addClass('ays_display_none');
                   next_fs.find('.ays_buttons_div').find('.ays_fa_arrow_right').removeClass('ays_display_none');
                }
                if(next_fs.find('input[name^="ays_questions"]').attr('type')==='number' && 
                   next_fs.find('.ays_buttons_div').find('.ays_fa_arrow_right').hasClass('ays_display_none') && 
                   next_fs.find('.ays_buttons_div').find('.ays_next').hasClass('ays_display_none')){
                   next_fs.find('.ays_buttons_div').find('input.ays_next').removeClass('ays_display_none');
                }
                if(next_fs.find('input[name^="ays_questions"]').attr('type')==='number' && 
                         $(document).find(".ays-questions-container .ays_qm_enable_arrows").val() == 'true'){
                   next_fs.find('.ays_buttons_div').find('input.ays_next').addClass('ays_display_none');
                   next_fs.find('.ays_buttons_div').find('.ays_fa_arrow_right').removeClass('ays_display_none');
                }
                
                aysAnimateStep(ays_quiz_container.data('questEffect'), current_fs, next_fs);
                setTimeout(function(){
                    if(next_fs.find('.ays-text-field').length > 0){
                        if(next_fs.find('.ays-text-field').width() < 250){
                            next_fs.find('.ays-text-field').css({
                                'flex-wrap': 'wrap',
                                'justify-content': 'center',
                                'padding': '5px'
                            });
                            next_fs.find('.ays-text-field').find('input.ays-text-input').css('margin-bottom', '5px');
                        }
                    }
                },2000);
            }
            if($(document).scrollTop() >= $(this).parents('.ays-questions-container').offset().top){
                ays_quiz_container.goTo();
            }
        });
         
        $(document).find('.ays_previous').on("click", function(e){            
            ays_quiz_container = $(this).parents(".ays-quiz-container");
            if(animating) return false;
            animating = true;
            var next_sibilings_count = $(this).parents('form').find('.ays_question_count_per_page').val();
            if(parseInt(next_sibilings_count)>0 && ($(this).parents('.step').attr('data-question-id') || $(this).parents('.step').next().attr('data-question-id'))){
                var questions_count = $(this).parents('form').find('div[data-question-id]').length;
                var curent_number_of_this = $(this).parents('form').find('div[data-question-id]').index($(this).parents('div[data-question-id]')) + 1;
                var curent_number = $(this).parents('form').find('div[data-question-id]').index($(this).parents('div[data-question-id]')) - parseInt(next_sibilings_count) + 1;
                let count_per_page = questions_count%parseInt(next_sibilings_count);
                let nextCountQuestionsPerPage = questions_count-curent_number;
                if(count_per_page > 0 && curent_number_of_this == questions_count){
                    curent_number = $(this).parents('form').find('div[data-question-id]').index($(this).parents('div[data-question-id]')) - count_per_page + 1;
                }
                if (!($(this).hasClass('start_button'))) {
                    var current_width = $(this).parents('.ays-quiz-container').find('.ays-live-bar-fill').width();
                    var final_width = ((curent_number) / questions_count * 100) + "%";
                    if($(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').hasClass('ays-live-bar-count')){
                        $(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(curent_number));
                    }else{
                        $(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(final_width));
                    }
                    $(this).parents('.ays-quiz-container').find('.ays-live-bar-fill').animate({'width': final_width}, 1000);
                }
                var current_fs_index = $(this).parents('form').find('div[data-question-id]').index($(this).parents('form').find('.active-step').eq(0));
                if($(this).parents('.step').attr('data-question-id')){
                    current_fs = $(this).parents('form').find('div[data-question-id]').slice(current_fs_index,current_fs_index+parseInt(next_sibilings_count));
                }else{
                    current_fs = $(this).parent();
                }

                var current_first_fs_index = $(this).parents('form').find('div[data-question-id]').index($(this).parents('form').find('.active-step').eq(0));
                var next_fs = $('div[data-question-id]').slice((current_first_fs_index - parseInt(next_sibilings_count)), current_first_fs_index);
                
                $(this).parents('form').find('div[data-question-id]').eq(current_fs_index).removeClass('active-step');
                next_fs.eq(0).addClass('active-step')
                if ($(this).parents('form').hasClass('enable_correction')) {
                    if (next_fs.find('.correct').length !== 0 || $(this).parents('div[data-question-id]').prev().find('.wrong').length !== 0) {
                        next_fs.find('input[name^="ays_questions"]').on('click',function () {
                            return false;
                        });
                    }
                }

                $(e.target).parents().eq(3).find('input[name^="ays_questions"]').attr('disabled', false);
//                next_fs.css({'display':'flex'});
                
                aysAnimateStep(ays_quiz_container.data('questEffect'), current_fs, next_fs);

            }else{
                if ($(this).parents('form').hasClass('enable_correction')) {
                    if ($(this).parents('div[data-question-id]').prev().find('.correct').length !== 0 || $(this).parents('div[data-question-id]').prev().find('.wrong').length !== 0) {
                        $(this).parents('div[data-question-id]').prev().find('input[name^="ays_questions"]').on('click',function () {
                            return false;
                        });
                    }
                }
                current_fs = $(this).parent().parent();
                next_fs = $(this).parent().parent().prev();
                $(e.target).parents().eq(3).find('input[name^="ays_questions"]').attr('disabled', false);
                if (current_fs.hasClass('ays-abs-fs')) {
                    current_fs = $(this).parent().parent().parent();
                    next_fs = $(this).parent().parent().parent().prev();
                    let counterClass = "";
                    switch(ays_quiz_container.data('questEffect')){
                        case "shake":
                            counterClass = ays_quiz_container.data('questEffect');
                        break;
                        case "fade":
                            counterClass = "fadeIn";
                        break;
                        case "none":
                            counterClass = "";
                        break;
                        default:
                            counterClass = ays_quiz_container.data('questEffect');
                        break;
                    }
                    next_fs.find('.ays-question-counter').addClass(counterClass);
//                    next_fs.find('.ays-question-counter').addClass('shake');
                }
                current_fs.removeClass('active-step');
                next_fs.addClass('active-step');

                var questions_count = $(this).parents('form').find('div[data-question-id]').length;
                var curent_number = $(this).parents('form').find('div[data-question-id]').index($(this).parents('div[data-question-id]'))-1;
                if(curent_number != questions_count){
                    if(($(this).hasClass('ays_finish')) == false){
                        if (!($(this).hasClass('start_button'))) {
                            var current_width = $(this).parents('.ays-quiz-container').find('.ays-live-bar-fill').width();
                            var final_width = ((curent_number+1) / questions_count * 100) + "%";
                            if($(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').hasClass('ays-live-bar-count')){
                                $(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(curent_number+1));
                            }else{
                                $(this).parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(final_width));
                            }
                            $(this).parents('.ays-quiz-container').find('.ays-live-bar-fill').animate({'width': final_width}, 1000);
                        }
                    }
                }else{
                    $(this).parents('.ays-quiz-container').find('.ays-live-bar-wrap').removeClass('rubberBand').addClass('bounceOut');
                    setTimeout(function () {
                        $(e.target).parents('.ays-quiz-container').find('.ays-live-bar-wrap').css('display','none');
                    },300)
                }
                
                aysAnimateStep(ays_quiz_container.data('questEffect'), current_fs, next_fs);
                
//                aysAnimateStep('swing', current_fs, next_fs);
            }
            if($(document).scrollTop() >= $(this).parents('.ays-questions-container').offset().top){
                ays_quiz_container.goTo();
            }
        });

        $(document).find('.ays-quiz-container input').on('focus',function () {
            $(window).on('keydown',function (event) {
                if(event.keyCode === 13){
                    return false;
                }
            });
        });

        $(document).find('.ays-quiz-container input').on('blur',function () {
            $(window).off('keydown');
        });
        
        $(document).on('click', '.ays-quiz-container .ays_question_hint', function (e) {
            $(e.target).parents('.ays-quiz-container').find('.ays_music_sound').toggleClass('z_index_0');
            $(e.target).parent().find('.ays_question_hint_text').toggleClass('show_hint');
            if($(e.target).parent().find('.ays_question_hint_text').hasClass('show_hint')){
                $(window).on('click', function(ev){
                    if( ! ( $(ev.target).hasClass('ays_question_hint_text') || $(ev.target).hasClass('ays_question_hint') ) ){
                        $(e.target).parent().find('.ays_question_hint_text').removeClass('show_hint')
                        $(e.target).parents('.ays-quiz-container').find('.ays_music_sound').removeClass('z_index_0');
                    }
                });
            }
        });

        $(document).on('click', '.ays-field', function() {
            if ($(this).find(".select2").hasClass('select2-container--open')) {
                $(this).find('b[role="presentation"]').removeClass('ays_fa ays_fa_chevron_down');
                $(this).find('b[role="presentation"]').addClass('ays_fa ays_fa_chevron_up');
            } else {
                $(this).find('b[role="presentation"]').removeClass('ays_fa ays_fa_chevron_up');
                $(this).find('b[role="presentation"]').addClass('ays_fa ays_fa_chevron_down');
            }
        });

        $(document).find('select.ays-select').on("select2:selecting", function(e){
            $(this).parents('.ays-quiz-container').find('b[role="presentation"]').addClass('ays_fa ays_fa_chevron_down');
        });
        
        $(document).find('select.ays-select').on("select2:closing", function(e){
            $(this).parents('.ays-quiz-container').find('b[role="presentation"]').addClass('ays_fa ays_fa_chevron_down');
        });
        
        $(document).find('select.ays-select').on("select2:select", function(e){
            $(this).parent().find('.ays-select-field-value').attr("value", $(this).val());
            if ($(this).parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') && $(this).parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                $(this).parents('div[data-question-id]').find('.ays_next').trigger('click');
            }
            if($(this).parents(".ays-questions-container").find('form[id^="ays_finish_quiz"]').hasClass('enable_correction')) {
                if ($(this).find('option:selected').data("chisht") == 1) {
                    $(this).parents('.ays-field').addClass('correct correct_div');
                    $(this).parents('.ays-field').find('.select2-selection.select2-selection--single').css("border-bottom-color", "green");
                } else {
                    $(this).parents('.ays-field').addClass('wrong wrong_div');
                    $(this).parents('.ays-field').find('.select2-selection.select2-selection--single').css("border-bottom-color", "red");
                    let rightAnswerText = '<div class="ays-text-right-answer">'+
                        $(this).find('option[data-chisht="1"]').html()+
                        '</div>';
                    $(this).parents('.ays-quiz-answers').append(rightAnswerText);
                    $(this).parents('.ays-quiz-answers').find('.ays-text-right-answer').css("text-align", "left");
                    $(this).parents('.ays-quiz-answers').find('.ays-text-right-answer').slideDown(500);
                }
                if(myOptions.answers_rw_texts && (myOptions.answers_rw_texts == 'on_passing' || myOptions.answers_rw_texts == 'on_both')){
                    if ($(this).find('option:selected').data("chisht") == 1) {
                        $(e.target).parents().eq(3).find('.right_answer_text').slideDown(500);
                    }
                    else {
                        $(e.target).parents().eq(3).find('.wrong_answer_text').slideDown(500);
                    }
                }
                $(this).attr("disabled", true);
                $(e.target).next().css("background-color", "#777");
                $(e.target).next().find('.selection').css("background-color", "#777");
                $(e.target).next().find('.select2-selection').css("background-color", "#777");
            }
            let this_select_value = $(this).val();
            $(this).find("option").removeAttr("selected");
            $(this).find("option[value='"+this_select_value+"']").attr("selected", true);
        });

        let shareButtons = document.querySelectorAll(".ays-share-btn.ays-to-share");

        if (shareButtons) {
            [].forEach.call(shareButtons, function(button) {
                button.addEventListener("click", function(event) {
                    let width = 650,
                        height = 450;

                    event.preventDefault();

                    window.open(this.href, langObj.shareDialog, 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,width='+width+',height='+height+',top='+(screen.height/2-height/2)+',left='+(screen.width/2-width/2));
                });
            });
        }

        $(document).find('.ays-quiz-container').map(function () {
            $(this).find('div[data-question-id]').eq(0).find('.ays_previous').css({'display':'none'});
            var next_sibilings_count = parseInt($(this).find('.ays_question_count_per_page').val());
            if(next_sibilings_count>0){
                $(this).find('div[data-question-id]').eq(next_sibilings_count-1).find('.ays_previous').css({'display':'none'});
            }
        });

        $(document).find('.ays_finish.action-button.ays_arrow').on('click', function () {
            $(this).next().trigger('click');
        });

        $(document).find('div[data-question-id]').map(function () {
            $(this).find('.ays-quiz-answers .ays-field').map(function () {
                if($(this).find('label[for^="ays-answer"]').eq(1).find('img').length !== 0){
                    $(this).find('label[for^="ays-answer"]').eq(0).addClass('ays_empty_before_content');
                    if($(this).find('label[for^="ays-answer"]').eq(0).text().length === 0){
                        $(this).find('label[for^="ays-answer"]').eq(0).css('background','transparent');
                    }
                }
            });
        });
        
        $(document).find('.ays_finish.action-button').on('click', function () {
            $(this).addClass("ays_timer_end");
        });        
        
        $(document).on('click', '.ays_early_finish.action-button', function (e) {
            e.preventDefault();
            
            let totalSteps = $(e.target).parents().eq(3).find('div.step').length;
            let currentStep = $(e.target).parents().eq(3).find('div.step.active-step');
            let thankYouStep = $(e.target).parents().eq(3).find('div.step.ays_thank_you_fs');
            let infoFormLast = thankYouStep.prev().find('div.information_form');
            let questions_count = $(e.target).parents('form').find('div[data-question-id]').length;
            if($(e.target).parents('.ays-quiz-container').find('.ays-live-bar-percent').hasClass('ays-live-bar-count')){
                $(e.target).parents('.ays-quiz-container').find('.ays-live-bar-percent').text(questions_count);
            }else{
                $(e.target).parents('.ays-quiz-container').find('.ays-live-bar-fill').animate({
                    width: '100%'
                });
                $(e.target).parents('.ays-quiz-container').find('.ays-live-bar-percent').text(100);
            }
            currentStep.parents('.ays-quiz-container').find('.ays-quiz-timer').slideUp();
            setTimeout(function () {                                        
                currentStep.parents('.ays-quiz-container').find('.ays-quiz-timer').parent().remove();
            },300);
            if(infoFormLast.length == 0){
                if (currentStep.hasClass('ays_thank_you_fs') === false) {
                    let steps = totalSteps - 3;
                    $(e.target).parents().eq(3).find('div.step').each(function (index) {
                        if ($(this).hasClass('ays_thank_you_fs')) {
                            $(this).addClass('active-step')
                        }else{
                            $(this).css('display', 'none');                                                
                        }
                    });
                    $(e.target).parents().eq(3).find('input.ays_finish').trigger('click');
                }
            }else{
                currentStep.parents('.ays-quiz-container').find('.ays-quiz-timer').parent().remove();
                $(e.target).parents('.ays-quiz-container').find('.ays-live-bar-wrap').removeClass('rubberBand').addClass('bounceOut');
                setTimeout(function () {
                    $(e.target).parents('.ays-quiz-container').find('.ays-live-bar-wrap').css('display','none');
                },300);
                aysAnimateStep($(e.target).parents('.ays-quiz-container').data('quest-effect'), currentStep, infoFormLast.parent());
                $(e.target).parents().eq(3).find('div.step').each(function (index) {
                    $(this).css('display', 'none');
                    $(this).removeClass('active-step')
                });
                infoFormLast.parent().css('display', 'flex');
                infoFormLast.parent().addClass('active-step'); 
            }
        });
        
        $(document).find('.action-button.ays_restart_button').on('click', function () {
            window.location.reload();
        });
        
        $(document).on('click', '.ays_music_sound', function() {
            let $this = $(this);
            let quizCoutainer = $this.parents('.ays-quiz-container');
            let audioEls = $(document).find('.ays_quiz_music');
            let soundEls = $(document).find('.ays_music_sound');
            let audioEl = quizCoutainer.find('.ays_quiz_music').get(0);
            if($this.hasClass('ays_sound_active')){
                audioEl.volume = 0;
                $this.find('.ays_fa').addClass('ays_fa_volume_off').removeClass('ays_fa_volume_up');
                $this.removeClass('ays_sound_active');
            } else {
                audioEl.volume = 1;
                $this.find('.ays_fa').addClass('ays_fa_volume_up').removeClass('ays_fa_volume_off');
                $this.addClass('ays_sound_active');
            }
        });
    });

})( jQuery );