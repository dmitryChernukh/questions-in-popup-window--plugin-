var mainZicController;
function mainObjectSecondReady(){
    mainZicController = document.mainObject;
}

$( document ).ready(function() {
    $('.collapse').collapse();
    var base_url = window.location.origin;
    $('#main').turbotabs({
        animation : 'ScrollDown',
        mode  : 'horizontal',
        //backgroundColor : 'rgb(230, 230, 230)',
        navAlign : 'left',
        width : '100%',
        padding : '10%',
        //hoverBackground : 'rgba(70, 139, 123, 0.9)'
        navBackground : 'rgb(181, 181, 181)',
        //headingTextColor : 'rgba(0, 0, 0, 0.8)',
        //textColor : 'rgba(0, 0, 0, 0.8)',
        navTextShadow : 'off'
    });

    $('#control-current-page').val(window.location.href);

    $('.back').on('click', function(){
        if(confirm("Are you sure you want to navigate away from this page?"))
        {
                window.location.href = base_url+'/admin?entity=PopUp';
        }
        return false;
    });

    $('.button-0').on('click', function(e){
        e.preventDefault();
    });

    $('.rgb-select').colorPicker({
        color: '#B700FF',
        customBG: '#B700FF',
        animationSpeed: 150,
        GPU: true,
        doRender: true | 'selector',
        opacity: true,
        renderCallback: function($elm, toggled) {},
        buildCallback: function($elm) {},
        css: '',
        cssAddon: '',
        margin: '',
        scrollResize: true,
        gap: 4,
        preventFocus: false,
        body: document.body // the element where the events are attached to (touchstart, mousedown, pointerdown, focus, click, change)
    });

    $('#link').on('click', function(){
        $('.qp-block, .rp-block').hide();
        $('.links-block').fadeIn("slow");
    });
    $('#qp').on('click', function(){
        $('.links-block, .rp-block').hide();
        $('.qp-block').fadeIn("slow");
    });
    $('#rp').on('click', function(){
        $('.links-block, .qp-block').hide();
        $('.rp-block').fadeIn("slow");
    });

    $( "#test2" ).submit(function( event ) {
        var atLeastOneIsChecked = $('input[name="chk"]:checked').length;
        if( atLeastOneIsChecked == 1 ){
            var questionName = $('#question-name-field').val();
            if(parseInt(questionName.length) <= 2){
                var error = 'Please, enter the "Name", more than 2 symbols.';
                var errBody = $('#error-body');
                errBody.empty();
                errBody.text(error);
                $('#exampleModal').modal('show');
                event.preventDefault();
            }
        } else {
                event.preventDefault();
        }
    });

    binder();

    $(document).on('click', '.rename-question-button', function(){
        var renameText = $(this).siblings('.form-group').children('.rename-placeholder').val();
        if(parseInt(renameText.length) > 2){
            var questionId = $($(this).parents()[2]).attr('data-question-id');
            var that = $(this);
            $('#modal-b').modal('show');
            $.ajax({
                type: 'POST',
                url: base_url+'/renameQuestion',
                data: 'questionId='+questionId+'&questionText='+renameText,
                success: function(data){
                    that.parent().siblings('.panel-title').find('.question-name').text(renameText);
                    that.parent().slideToggle();
                    that.siblings('.form-group').children('.rename-placeholder').val(' ');
                    $('#modal-b').modal('hide');
                }
            });
        } else {
            showPopUp('Please, enter the name. More than 2 symbols.');
            return false;
        }

    });

    $('#rating-count').on('change', function(){
        var counter = parseInt($(this).val());
        if(counter == 1){
            $("#RB-2").hide();
            $("#RB-3").hide();
        }else if(counter == 2){
            $("#RB-2").show();
            $("#RB-3").hide();
        }else if(counter == 3){
            $("#RB-2").show();
            $("#RB-3").show();
        }
    });

    $('.add-question-button').on('click', function(){
        var atLeastOneIsChecked = $('input[name="chk"]:checked').length;
            var popUp = $('#id').val();
            var questionName = $('#question-name-field').val();
            if(parseInt(questionName.length) >= 2){
                var button = $('.add-question-button');
                button.removeClass( 'btn-primary' );
                button.toggleClass( 'btn-warning' );
                button.empty().text('Processing ...');
                $('#modal-b').modal('show');
                $.ajax({
                    type: 'POST',
                    url: base_url+'/addQuestion',
                    data: 'popUpId='+popUp+'&questionName='+questionName,
                    success: function(data){

                        var container = $("#question-main-element").clone();
                        container.attr('id','');
                        container.attr("data-question-id", data.questionId);
                        container.find(".question-name").text(data.questionName);
                        container.find(".rename-placeholder").attr('placeholder',data.questionName);

                        $('#accordion').append(container).show();
                        $('#question-name-field').val('');
                        $('#modal-b').modal('hide');
                        button.removeClass( 'btn-warning' );
                        button.toggleClass( 'btn-primary' );
                        button.empty().text('+ Add a question');
                        binder();
                    }
                });
            } else {
                var error = 'Please, enter the "Name", more than 1 symbols.';
                var errBody = $('#error-body');
                errBody.empty();
                errBody.text(error);
                $('#exampleModal').modal('show');
            }
      });

    function binder(){
        var block = $('.question-name');
        var answer = $('.add-a-answer');
        $('div.panel-collapse').css('height', 'auto');
        $('div.collapse-new-answer').css('height', 'auto');
        block.unbind('click');
        answer.unbind('click');
        block.bind('click', function(){
            $('div.panel-collapse' , $(this).parents()[2]).slideToggle();
        });
        answer.bind('click', function(){
            $(".collapse-new-answer", $(this).parents()[2]).slideToggle();
        })
    }

    function showPopUp(message){
        var errBody = $('#error-body');
        errBody.empty();
        errBody.text(message);
        $('#exampleModal').modal('show');
    }

    $(document).on('click', '.delete-current-answer', function(){
        var answerId = $(this).parent().find('.answer-id').val();
        var popUp = $('#confirmModal');
        var errBody = $('#confirm-module-title');
        errBody.empty();
        errBody.text('Are you sure to delete?');
        popUp.find("input[name~='element-id']").val(answerId);
        popUp.find("input[name~='element-identifier']").val('AC');
        popUp.modal('show');
        var that = $(this);
        $('.delete-from-modal').on('click', function(){
            $(this).unbind('click');
            var id = $(this).siblings('.element-to-delete').val();
            var identifier = $(this).siblings('.identifier-to-delete').val();
            $.ajax({
                type: 'POST',
                url: base_url+'/deleteAnswer',
                data: 'answerId='+id+'&identifier='+identifier,
                success: function(data){
                    $('#confirmModal').modal('hide');
                    that.parent().fadeOut(2000, function(){$(this).remove()})
                }
            });
        })
    });

    $(document).on('click', '.edit-question-button', function(){
       $(this).parent().siblings('.rename-question').slideToggle();
    });

    $(document).on('click', '.add-answer-button', function(){
        var questionId = $(this).parents()[5].getAttribute('data-question-id');
        var formData = JSON.parse(JSON.stringify($(this).parent().serializeArray()));
        var data = [];
        formData.forEach(function(item) {
            data[item.name] = item.value;
        });
        if(data['answer-name'].length >= 2 ){
            if(data['identifier'] == 'L'){
                if(data['action-value'].length <=10 ){
                    showPopUp('Please, enter the URL address. More than 10 symbols.');
                    return false;
                }
                if((parseInt(data['action-value'].indexOf("http://")) == -1) && (parseInt(data['action-value'].indexOf("https://")) == -1 ) ){
                    showPopUp('The url address must start from "http://" or "https://".');
                    return false;
                }
            }
            if (data['identifier'] == 'RP' || data['identifier'] == 'QP'){
                if(!('action-value' in data)){
                    showPopUp('Please, create another question / result-popup, before set the action. ');
                    return false;
                }
            }
            var that = $(this);
            $('#modal-b').modal('show');
            $.ajax({
                type: 'POST',
                url: base_url+'/addAnswer',
                data: 'answer='+data['answer-name']+'&question='+questionId+'&identifire='+data['identifier']+'&identifireStepID='+data['action-value'],
                success: function(data){
                    var answer = that.parent().clone();
                    answer.find('.answer-id').val(data.answerId);
                    answer.removeClass("form-adding");
                    $(answer.find('.change-action').children()).each(function(id, element){
                        if($(this).val() == data.identifire){
                            $(this).attr('selected','selected');
                        }
                    });
                    $(answer.find('.input-parameters').children()).each(function(id, element){
                        if($(this).val() == data.identifireStepID){
                            $(this).attr('selected','selected');
                        }
                    });
                    $(answer.find('.form-control')).each(function(id, element){
                        $(this).addClass("listener");
                        $(this).removeClass("without-class");

                    });

                    answer.find('.add-answer-button').remove();
                    answer.append('<button type="button" class="btn btn-danger delete-current-answer">Delete</button>');
                    answer.append('<button type="button" class="btn btn-info save-answer-button save-answer-input">Save</button>');
                    var answersContainer = $(that.parents()[3]).find( ".answers-container" );
                    answersContainer.append(answer).animate({height: 'show'}, 500);

                    $('#modal-b').modal('hide');
                }
            });
        } else {
            showPopUp('Please, enter the answer name, more than 2 symbols.');
            return false;
        }
    });

    $(document).on('keydown change', '.listener', function(event){
        $(this).closest('.add-answer-line').find('.save-answer-input').show();
    });

    $( document ).on('submit', '.add-answer-line',function( event ) {
        event.preventDefault();
        var answerId = $(this).find('.answer-id').val();
        var formData = JSON.parse(JSON.stringify($(this).serializeArray()));

        var data = [];
        formData.forEach(function(item) {
            data[item.name] = item.value;
        });
        var that = $(this);
        if(data['answer-name'].length >= 2 ){
            if (data['identifier'] == 'RP' || data['identifier'] == 'QP'){
                if(!('action-value' in data)){
                    showPopUp('Please, create another question / result-popup, before set the action. ');
                    return false;
                }
            }
            $('#modal-b').modal('show');
            $.ajax({
                type: 'POST',
                url: base_url+'/updateAnswer',
                data: 'answer='+data['answer-name']+'&answerId='+answerId+'&identifire='+data['identifier']+'&identifireStepID='+data['action-value'],
                success: function(data){
                    $('#modal-b').modal('hide');
                    that.find('.save-answer-button').hide();
                }
            });
        } else {
            showPopUp('Please, enter the answer name, more than 2 symbols.');
            return false;
        }
        return false;
    });

    $(document).on('click', '.remove-question-button', function(){
        var QuestionId = $($(this).parents()[2]).attr('data-question-id');
        var popUp = $('#confirmModal');
        var errBody = $('#confirm-module-title');
        errBody.empty();
        errBody.text('Are you sure you want to delete the current question?');
        popUp.find("input[name~='element-id']").val(QuestionId);
        popUp.find("input[name~='element-identifier']").val('Q');
        popUp.modal('show');
        var that = $(this);
        $('.delete-from-modal').on('click', function(){
            $(this).unbind('click');
            var id = $(this).siblings('.element-to-delete').val();
            var identifier = $(this).siblings('.identifier-to-delete').val();
            $.ajax({
                type: 'POST',
                url: base_url+'/deleteQuestion',
                data: 'questionId='+id+'&identifier='+identifier,
                success: function(data){
                    if(data.haveError == 'CannotRemove'){
                        $('#confirmModal').modal('hide');
                        alert("Probably, this question has relation with some URL (site). " +
                            "In this way, remove this question from site, and try again!");
                        return false;
                    }
                    $('#confirmModal').modal('hide');
                    $(that.parents()[2]).fadeOut(2000, function(){$(this).remove()})
                }
            });
        });
    });

    function saveAnswerForm(that){
        var questionId = that.parents()[4].getAttribute('data-question-id');
        var answerId = that.parent().find('.answer-id').val();

        var formData = JSON.parse(JSON.stringify(that.parent().serializeArray()));
        var data = [];
        formData.forEach(function(item) {
            data[item.name] = item.value;
        });
        if(data['answer-name'].length >= 2 ){
            if (data['identifier'] == 'RP' || data['identifier'] == 'QP'){
                if(!('action-value' in data)){
                    showPopUp('Please, create another question / result-popup, before set the action. ');
                    return false;
                }
            }
            $('#modal-b').modal('show');
            $.ajax({
                type: 'POST',
                url: base_url+'/updateAnswer',
                data: 'answer='+data['answer-name']+'&answerId='+answerId+'&identifire='+data['identifier']+'&identifireStepID='+data['action-value'],
                success: function(data){
                    $('#modal-b').modal('hide');
                    that.hide();
                }
            });
        } else {
            showPopUp('Please, enter the answer name, more than 2 symbols.');
            return false;
        }
    }

    $(document).on('click','.save-answer-button', function(){
        saveAnswerForm($(this));
    });

    $(document).on('change','.change-action', function(){
        var classValue;
        if($(this).hasClass('without-class')){
            classValue = null;
        } else {
            classValue = 'listener';
        }
        var that = $(this);
        var value = $(this).val();
        var popUpId = $('#page-popup-id').val();
        var questionId = $(this).parents()[6].getAttribute('data-question-id');
            if(questionId == null){
                questionId = $(this).parents()[5].getAttribute('data-question-id');
            }
        if (value == 'L'){
            that.parent().parent().find('.action-string').empty();
            that.parent().parent().find('.action-string').append('<input type="text" name="action-value" placeholder="input the link ..." value="" class="'+classValue+' form-control input-parameters" id="add-2-line">');
        } else if (value == 'QP'){
            getAjaxData('QP', that, classValue, popUpId, questionId);
        } else if(value == 'RP'){
            getAjaxData('RP', that, classValue, popUpId, questionId);
        } else if (value == 'UP'){
            getAjaxData('UP', that, classValue, popUpId, questionId);
        }
    });

    function getAjaxData(rest, that, condition, popUpId, questionId){
        var string;
        if(condition == null){
             string = ' ';
        } else {
             string = condition;
        }
        $.ajax({
            type: 'POST',
            url: base_url+'/getQuestions',
            data: 'popUpId='+popUpId+'&questionId='+questionId,
            success: function(data){

                that.parent().parent().find('.action-string').empty();

                if(rest == 'QP'){
                    var randome = Math.random().toString(14).substring(7);
                    data.questions.selector = '<select class="form-control input-parameters '+string+'" name="action-value" id="add-2-line">';
                    var warning = '<input value="Nothing not found" readonly class="form-control">';
                    var selector = data.questions.selector;

                    if(data.questions.length != 0){
                        $.each(data.questions, function( index, value) {
                            selector += '<option value="'+value.id+'">'+value.question+'</option>';
                        });
                        selector += '</select>';
                        that.parent().parent().find('.action-string').append(selector);
                    } else {
                        that.parent().parent().find('.action-string').append(warning);
                    }

                } else if(rest == 'RP'){
                    var warning = '<input value="Nothing not found" readonly class="form-control">';
                    data.questions.selector = '<select class="form-control input-parameters '+string+'" name="action-value" id="add-2-line">';
                    var selector = data.questions.selector;
                    if(data.resultPopUp.length != 0){
                        $.each(data.resultPopUp, function( index, value) {
                            selector += '<option value="'+value.id+'">'+value.name+'</option>';
                        });
                        selector += '</select>';
                        that.parent().parent().find('.action-string').append(selector);
                    } else {
                        that.parent().parent().find('.action-string').append(warning);
                    }
                } else if(rest == 'L'){
                    that.parent().parent().find('.action-string').append('<input type="text" name="action-value" value="" class="form-control input-parameters '+string+'" id="add-2-line">');
                } else if (rest == 'UP'){
                    var warning = '<input value="Nothing not found" readonly class="form-control">';
                    data.questions.selector = '<select class="form-control input-parameters '+string+'" name="action-value" id="add-2-line">';
                    var selector = data.questions.selector;
                    if(data.userPopUp.length != 0){
                        $.each(data.userPopUp, function( index, value) {
                            selector += '<option value="'+value.id+'">'+value.popupName+'</option>';
                        });
                        selector += '</select>';
                        that.parent().parent().find('.action-string').append(selector);
                    } else {
                        that.parent().parent().find('.action-string').append(warning);
                    }
                }
            }
        });
    }

    $("#result-pop-up-form").validate({
        rules: {
            name: "required",
            separateStatus: "required",
            mainTitle: "required",
            height: {
                required: true,
                number: true
            },
            width: {
                required: true,
                number: true
            },
            imageSizeHead: {
                required: true,
                number: true
            },
            imageSizeBody: {
                required: true,
                number: true
            },
            bgColor :{
                required: true
            },
            buttonColor:{
                required: true
            },
            textColor: {
                required: true
            },
            borderRadius:{
                required: true,
                number: true
            },
            ratingTextOne:{
                required: true
            },
            ratingTextTwo:{
                required: true
            },
            ratingTextSize:{
                required: true,
                number: true
            },
            url:{
                required: true
            },
            buttonText:{
                required: true
            },
            buttonWidth:{
                required: true,
                number: true
            },
            buttonHeight:{
                required: true,
                number: true
            },
            borderColour:{
                required: true
            },
            borderWidth:{
                required: true,
                number: true
            },
            mainQuestionTextSize:{
                required: true,
                number: true
            }
        },
        messages: {
            name: "Please enter the name",
            mainTitle: "Please enter the main title",
            height: {
                required: "Please provide the height.",
                number: "The value must be a number."
            },
            width:{
                required: "Please provide the width.",
                number: "The value must be a number."
            },
            imageSizeHead:{
                required: 'Please provide the size of image (Head Image)',
                number: "The value must be a number."
            },
            imageSizeBody: {
                required: 'Please provide the size of image (Body Image)',
                number: "The value must be a number."
            },
            bgColor :{
                required: 'Please provide the background colour.'
            },
            buttonColor:{
                required: 'Please provide the button colour.'
            },
            textColor: {
                required: 'Please provide the text colour.'
            },
            borderRadius:{
                required: 'Please provide the border radius.',
                number: "The value must be a number."
            },
            ratingTextOne:{
                required: 'Please provide the rating text.'
            },
            ratingTextTwo:{
                required: 'Please provide the rating text (2).'
            },
            ratingTextSize:{
                required: 'Please provide the rating text size.',
                number: "The value must be a number."
            },
            url:{
                required: 'Please provide the URL.'
            },
            buttonText:{
                required: 'Please provide the button text.'
            },
            buttonWidth:{
                required: 'Please provide the button width.',
                number: "The value must be a number."
            },
            buttonHeight:{
                required: 'Please provide the button height.',
                number: "The value must be a number."
            },
            borderColour:{
                required: 'Please provide the border colour.'
            },
            borderWidth:{
                required: 'Please provide the border width.',
                number: "The value must be a number."
            },
            mainQuestionTextSize:{
                required: 'Please provide the main question text size.',
                number: "The value must be a number."
            }
        },
        submitHandler: function(form) {
            $("div.error").hide();
            $('#modal-b').modal('show');
            var resultPopUp = $('#result-pop-up-form');
            var formData = new FormData(resultPopUp[0]);
            var that = $(this);
            $.ajax({
                url: base_url+'/saveResult', // Url to which the request is send
                type: "POST",             // Type of request to be send, called as method
                data: formData, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false,       // The content type used when sending data to the server.
                cache: false,             // To unable request pages to be cached
                processData:false,        // To send DOMDocument or non processed data file it is set to false
                success: function(data)   // A function to be called if request succeeds
                {
                  $('#do-accordion').append(data.popUpContainer);
                    $('#modal-b').modal('hide');
                }
            });
        },
        invalidHandler: function(event, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                var message = errors == 1
                    ? 'You missed 1 field. It has been highlighted'
                    : 'You missed ' + errors + ' fields. Please, enter theirs.';
                $("div.error span").html(message);
                $("div.error").show();
            } else {
                $("div.error").hide();
            }
            return false;
        }
    });

    $('.cloned-as-result').on('click', function(){
        var formData = [];
        var popUpId = $('#id').val();
        $('#modal-b').modal('show');
        $.ajax({
            url: base_url+'/cloneAsResult?index='+popUpId,
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function(data)
            {
                window.location.reload();
            }
        });
    });

    $('#name-protocol').on('change', function(){
            $($(this).parents()[2]).find('.input-group-addon').empty().text($(this).val()+'://');
            $($(this).parents()[2]).find('.input-group-addon-protocol').val($(this).val()+'://');
    });

    var counter = 0;

    $("#create-new-site-form").validate({
        rules: {
            name: "required",
            availableQuestions: "required",
            url: "required"
        },
        messages: {
            name: "Please enter the name of the site",
            availableQuestions: "Please select the first question. If there - is no question - you need create it.",
            url: "Please enter the URL"
        },
        submitHandler: function(form) {
            counter = 0;
            $('#modal-b').modal('show');
            var popUpId = $('#id').val();
            var siteForm = $('#create-new-site-form');
            var formData = new FormData(siteForm[0]);
            var that = $(this);
            $.ajax({
                url: base_url+'/addNewSite?index='+popUpId,
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                success: function(data)
                {
                    if(data.error == 'Exist' ){
                        var err = $('.show-error-site');
                        err.find('.sleep-area').empty();
                        $.each( data.sites, function( key, value ) {
                            if(value.isSleep == false){
                                counter++;
                                err.find('.sleep-area').append('<form class="form-inline" style="margin: 10px;">'+
                                    '<div class="form-group">'+
                                    '<label for="solaris1" style="margin-left: 15px; ">PopUp name</label>'+
                                    '<input type="text" style="background-color: rgba(239, 172, 77, 0.67); margin-left: 5px;" class="form-control" readonly id="" value="'+value.popUpName+'">'+
                                    '<input type="hidden" class="form-control site-id" value="'+value.id+'">'+
                                    '<input type="hidden" class="form-control site-popupId" value="'+value.popupId+'">'+
                                    '</div>'+
                                    '<div class="form-group">'+
                                    '<label for="solaris2" style="margin-left: 15px; ">Site URL</label>'+
                                    '<input type="text" style="background-color: rgba(239, 172, 77, 0.67); margin-left: 5px;" class="form-control" readonly id="" value="'+value.siteUrl+'">'+
                                    '</div>'+
                                    '<button type="button" style="margin-left: 20px;" class="btn btn-warning send-to-sleep">Send to sleep</button>'+
                                    '</form>');
                            }
                        });

                        err.show();
                        $('#modal-b').modal('hide');
                        return false;
                    }
                    $('#unique-site-id').val(data.siteId);
                    $('#modal-b').modal('hide');
                    var block = $('.alert-success-message');
                    $('.show-error-site').hide();
                    block.empty().append('<strong>Success!</strong> The data has been successfully saved!');
                    block.fadeOut(1000, function(){$(this).show()});
                    setTimeout(function(){
                        $('.alert-success-message').fadeOut(2000, function(){$(this).hide()})
                    }, 3000);
                }
            });
        },
        invalidHandler: function(event, validator) {
            return false;
        }
    });

    $('.move-to-archive').on('click', function(){
        $('#modal-b').modal('show');
        var formData = [];
        var popUpId = $('#id').val();
        $.ajax({
            url: base_url+'/moveToArchive?index='+popUpId,
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function(data)
            {
                window.location.href = base_url+'/admin?entity=PopUp';
            }
        });


    });

    $(document).on('click', '.send-to-sleep', function(){
        counter --;
        var siteId = $(this).parent().find('.site-id').val();
        $('#modal-b').modal('show');
        var that = $(this);
        $.ajax({
            url: base_url+'/sendToSleep?siteId='+siteId,
            type: "POST",
            data: 'siteId='+siteId,
            contentType: false,
            cache: false,
            processData:false,
            success: function(data)
            {
               $('#modal-b').modal('hide');
                setTimeout(function(){
                    that.parent().fadeOut(1000, function(){$(this).hide()})

                }, 1000);

                setTimeout(function(){
                    if(counter == 0){
                        that.parent().parent().parent().fadeOut(1000, function(){$(this).hide()});
                        setTimeout(function(){
                            $('#create-new-site-form').trigger('submit');
                        }, 1000);
                    }
                }, 2000);
            }
        });
    });

    $('.save-condition-action').on('click', function(){
        var blind = $('#modal-b');
        blind.modal('show');
        var siteId = $('#unique-site-id').val();
        if(siteId != ''){
        var siteForm = $('#save-condition-set-value');
        var formData = new FormData(siteForm[0]);

            $.ajax({
                url: base_url+'/saveCondition?index='+siteId,
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                success: function(data)
                {
                    if(data.errorT == 'NoWrite'){
                        blind.modal('hide');
                        $('.message-top-st').fadeOut(2000, function(){$(this).show()});
                        setTimeout(function(){
                            $('.message-top-st').fadeOut(2000, function(){$(this).hide()})
                        }, 10000);
                        return false;
                    }
                    blind.modal('hide');
                    $('.message-top-sv').fadeOut(2000, function(){$(this).show()});
                    setTimeout(function(){
                        $('.message-top-sv').fadeOut(2000, function(){$(this).hide()})
                    }, 5000);
                }
            });
        } else {
            blind.modal('hide');
            $('.message-top-sl').fadeOut(2000, function(){$(this).show()});
            setTimeout(function(){
                $('.message-top-sl').fadeOut(2000, function(){$(this).hide()})
            }, 10000);
        }
    });

    $(document).on('click', '.glyphicon-refresh', function(){
        $('#modal-b').modal('show');
        var popUpId = $('#id').val();
        var formData = [];
        var that = $(this);
        $.ajax({
            url: base_url+'/refreshQuestions?index='+popUpId,
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function(data)
            {
                that.parent().parent().find('#available-questions').empty();
                $('#modal-b').modal('hide');
                $.each(data.questions, function( index, value) {
                    that.parent().parent().find('#available-questions').append('<option value="'+value.id+'">'+value.question+'</option>')
                });
            }
        });
    });

    $('.save-popup-action').on('click', function(){
        document.myPopupForm.submit();
    });

    $('.edit-popup-action').on('click', function(){
        var popUpId = $('#id').val();
        var current = encodeURIComponent(window.location.href);
        window.location.href = base_url+'/previewPopUp?id='+popUpId+'&currentPage='+current;
        return false;
    });

    $('.reload-popup-action').on('click', function(){
        location.reload();
    });

    $(document).on('click', '.remove-current-pop-up-result', function(){
       var popUp = $('#confirmModal');
       var errBody = $('#confirm-module-title');
       var popUpId = $(this).siblings('.res-popup-id').val();
       errBody.empty();
       errBody.text('Are you sure you want to delete the current popup?');
       popUp.find("input[name~='element-id']").val(popUpId);
       popUp.find("input[name~='element-identifier']").val('RP');
       popUp.modal('show');
       var that = $(this);
       $('.delete-from-modal').on('click', function(){
            $(this).unbind('click');
            var id = $(this).siblings('.element-to-delete').val();
            var identifier = $(this).siblings('.identifier-to-delete').val();
            $.ajax({
                type: 'POST',
                url: base_url+'/deleteCurrentPopUp',
                data: 'id='+popUpId,
                success: function(data){
                    $(that.parents()[2]).fadeOut(2000, function(){$(this).remove()});
                    $('#confirmModal').modal('hide');
                },
                statusCode: {
                    500: function() {
                        alert( "Something went wrong on the server side!" );
                    }
                }
            });
        });
    });

    $(document).on('click', '.strim-s', function(){
        var blind = $('#modal-b');
        blind.modal('show');
        var resultPopUp = $('#block-code-popup');
        var formData = new FormData(resultPopUp[0]);
        $.ajax({
            type: 'POST',
            url: base_url+'/saveUserPopUp',
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function(data){
                blind.modal('hide');
                $('.well-done-popup').show();
                $('.er-8, .er-7, .pm-stl').val('');
                   function timer(){
                        var obj=document.getElementById('back-timer');
                        obj.innerHTML--;

                        if(obj.innerHTML==0){
                            document.location.reload (true);
                            setTimeout(function(){},1000);
                        }
                        else{
                            setTimeout(timer,1000);
                        }
                    }
                setTimeout(timer,1000);
            },
            statusCode: {
                500: function() {
                    alert( "Something went wrong on the server side!" );
                }
            }
        });
    });

    $(document).on('click', '.remove-user-popup', function(){
        var id = $(this).siblings('.user-popup-id').val();
        var formData = [];
        var event = $( ".delete-from-modal");
        var popUp = $('#confirmModal');
        var errBody = $('#confirm-module-title');
        errBody.empty();
        errBody.text('Are you sure you want to delete this user popup?');
        popUp.find("input[name~='element-id']").val(id);
        popUp.find("input[name~='element-identifier']").val('UP');
        popUp.modal('show');
        var that = $(this);
        event.unbind( "click" );
        event.on('click', function() {
            $(this).unbind('click');
            var id = $(this).siblings('.element-to-delete').val();
            var identifier = $(this).siblings('.identifier-to-delete').val();

            $.ajax({
                type: 'POST',
                url: base_url+'/deleteCurrentElement?dataID='+id+'&identifier='+identifier,
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                success: function(data){
                    $(that.parents()[2]).fadeOut(2000, function(){$(this).remove()});
                    $('#confirmModal').modal('hide');
                },
                statusCode: {
                    500: function() {
                        alert( "Something went wrong on the server side!" );
                    }
                }
            });
        });
    });

    $(document).on('click', '.str-edit-popup', function(){
        var id =  $(this).siblings('.user-popup-id').val();
        var current = encodeURIComponent(window.location.href);
        window.location.href = base_url+'/stl-edit-user?id='+id+'&current='+current;
    });

    $(document).on('click', '.edit-result-pop-up', function(){
        var popUpId = $(this).siblings('.res-popup-id').val();
        var current = encodeURIComponent(window.location.href);
        window.location.href = base_url+'/editResultPopUp?index='+popUpId+'&currentPage='+current;
    });

    $('.show-block-with-code').on('click',function(){
        if($(this).attr('data-position') == 0){
            $('.create-source-code').fadeIn(2000);
            $(this).attr('data-position',1);
        } else if($(this).attr('data-position') == 1){
            $('.create-source-code').hide();
            $(this).attr('data-position',0);
        }
    });

    $('.remove-current-pop-up').on('click', function(){
        var formData = [];
        var id = $('#id').val();
        var popUp = $('#confirmModal');
        var event = $( ".delete-from-modal");
        var errBody = $('#confirm-module-title');
        errBody.empty();
        errBody.text('Are you sure you want to delete this popup?');
        popUp.find("input[name~='element-id']").val(id);
        popUp.find("input[name~='element-identifier']").val('DP');
        popUp.modal('show');

        var that = $(this);
        event.unbind( "click" );
        event.on('click', function() {
            $(this).unbind('click');
            var id = $(this).siblings('.element-to-delete').val();
            var identifier = $(this).siblings('.identifier-to-delete').val();

            $.ajax({
                type: 'POST',
                url: base_url+'/deleteUserPopUp?dataID='+id+'&identifier='+identifier,
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                success: function(data){
                    $('#confirmModal').modal('hide');
                    window.location.href = base_url+'/admin?entity=PopUp';
                },
                statusCode: {
                    500: function() {
                        alert( "Something went wrong on the server side!" );
                    }
                }
            });
        });
    });

    $('.clone-popup').on('click', function(){
        var formData = [];
        var id = $('#id').val();
        var popUp = $('#cloneModal');
        var event = $( ".clone-from-modal");
        var errBody = $('#clone-module-title');
        errBody.empty();
        errBody.text(' This action will lead to cloning of the current popup. All resulting popups will also be cloned. Questions will not be cloned. Do you agree?');
        popUp.find("input[name~='element-id']").val(id);
        popUp.find("input[name~='element-identifier']").val('CLONE');
        popUp.modal('show');

        var that = $(this);
        event.unbind( "click" );

        event.on('click', function() {
            $(this).unbind('click');
            var id = $(this).siblings('.element-to-delete').val();
            var identifier = $(this).siblings('.identifier-to-delete').val();

            $.ajax({
                type: 'POST',
                url: base_url+'/cloneCurrentPopup?dataID='+id+'&identifier='+identifier,
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                success: function(data){
                    $('#confirmModal').modal('hide');
                    window.location.href = base_url+'/admin?entity=PopUp';
                },
                statusCode: {
                    500: function() {
                        alert( "Something went wrong on the server side!" );
                    }
                }
            });
        });
    });

});