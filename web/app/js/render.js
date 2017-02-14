$( document ).ready(function() {

    var container = $('.pop-up');
    var prevPage = $('#prev-page');
    var form = $('#set-parameter-form');
    var formResult = $('#set-parameter-form-result');

    //form.attr('action', window.location.origin+'/form/save');
    //formResult.attr('action', window.location.origin+'/formResult/saveResult');
    //prevPage.val(document.referrer);

    var style = {
        1:'dotted',
        2:'hidden',
        3:'none',
        4:'dashed',
        5:'solid',
        6:'double',
        7:'groove',
        8:'ridge',
        9:'inset',
        10:'outset'
    };

    var MouseCoords = {

        // X-coordinate
        getX: function(e)
        {
            if (e.pageX)
            {
                return e.pageX;
            }
            else if (e.clientX)
            {
                return e.clientX+(document.documentElement.scrollLeft || document.body.scrollLeft) - document.documentElement.clientLeft;
            }

            return 0;
        },

        // Y-coordinate
        getY: function(e)
        {
            if (e.pageY)
            {
                return e.pageY;
            }
            else if (e.clientY)
            {
                return e.clientY+(document.documentElement.scrollTop || document.body.scrollTop) - document.documentElement.clientTop;
            }

            return 0;
        }
    };

    document.onmousemove = function(e)
    {
        if (!e) e = window.event;

        var mouseCoordsLayer = document.getElementById('mouse_coords_on_move');
        mouseCoordsLayer.innerHTML = '<span class="coordinate"><b>Coordinates during movement:</b></span>';
        mouseCoordsLayer.innerHTML += '<p>X: '+MouseCoords.getX(e)+'</p>';
        mouseCoordsLayer.innerHTML += '<p>Y: '+MouseCoords.getY(e)+'</p>';
    };

    document.onclick = function(e)
    {
        if (!e) e = window.event;

        var mouseCoordsLayer = document.getElementById('mouse_coords_on_click');
        mouseCoordsLayer.innerHTML = '<span class="coordinate"><b>Coordinates when clicked:</b></span>';
        mouseCoordsLayer.innerHTML += '<p>X: '+MouseCoords.getX(e)+'</p>';
        mouseCoordsLayer.innerHTML += '<p>Y: '+MouseCoords.getY(e)+'</p>';
    };


    $('#height').on('change wheel', function(){
        container.css('height', $(this).val()+'px');
        $(this).attr('value',$(this).val());
    });
    $('#width').on('change wheel', function(){
        container.css('width', $(this).val()+'px');
        $('.image-container').width($(this).val());
        $('.answer-container').width($(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#bgColor').on('focusout', function(){
        container.css('background-color', $(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#buttonColor').on('focusout', function(){
        $('.button-answer').css('background-color', $(this).val());
        $('.button-0').css('background-color', $(this).val());
        $(this).attr('value',$(this).val());
    });

    //ButtonBorderColor
    $('#ButtonBorderColor').on('focusout', function(){
        $('.button-answer').css('border', '1px solid '+$(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#borderStyle').on('change', function(){
        var index = $(this).val();
        container.css('border-style', style[index]);
        $(this).attr('value',index);
    });

    $('#borderWidth').on('change wheel', function(){
        container.css('border-width', $(this).val()+'px');
        $(this).attr('value',$(this).val());
    });

    //ButtonBorderRadius
    $('#ButtonBorderRadius').on('change wheel', function(){
        $('.button-answer').css('border-radius', $(this).val()+'px');
        $(this).attr('value',$(this).val());
    });

    //ButtonTextAlign
    $('#ButtonTextAlign').on('change', function(){
        $('.button-answer').css('text-align', $(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#textColor').on('focusout', function(){
        container.css('color', $(this).val());
        $('.text-fluid-str').css('color', $(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#borderRadius').on('change wheel', function(){
        container.css('border-radius', $(this).val()+'px');
        $(this).attr('value',$(this).val());
    });

    $('#buttonTopMargin').on('change wheel', function(){
        //container.css('border-radius', $(this).val()+'px');
        $('.button-0').css('margin-top', $(this).val()+'px');
        $(this).attr('value',$(this).val());
    });

    $('#buttonTextColour').on('focusout', function(){
        $('.button-0').css('color', $(this).val());
        $(this).attr('value',$(this).val());
    });

    //textBlockMessage
    $('#textBlockMessage').on('keyup', function(){
        $('.text-container-fluid').find('pre').text($(this).val());
        $(this).attr('value',$(this).val());
    });

    //textBlockMessageSize
    $('#textBlockMessageSize').on('change wheel', function(){
        $('.text-container-fluid').css('font-size', $(this).val()+'pt');
        $(this).attr('value',$(this).val());
    });

    //SpaceBetweenButtons
    $('#SpaceBetweenButtons').on('change wheel', function(){
        $('.button-answer').css('margin', $(this).val()+'px auto');
        $(this).attr('value',$(this).val());
    });

    $('#buttonTextSize').on('change wheel', function(){
        $('.inner-span-button').css('font-size', $(this).val()+'pt');
        $(this).attr('value',$(this).val());
    });

    //imageMarginBottom
    $('#imageMarginBottom').on('change wheel', function(){
        $('.container-for-image').css('margin-bottom', $(this).val()+'px');
        $(this).attr('value',$(this).val());
    });

    //imageTopMargin
    $('#imageTopMargin').on('change wheel', function(){
        $('.container-for-image').css('margin-top', $(this).val()+'px');
        $(this).attr('value',$(this).val());
    });

    $('#borderColour').on('focusout', function(){
        container.css('border-color', $(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#buttonTextColor').on('focusout', function(){
        $('.button-answer').css('color', $(this).val());
        $(this).attr('value',$(this).val());
    });

    $('.radio-selector').on('click', function(){
        if($(this).val() == 'RB'){
            $('.answer-container').show();
            $('.text-container-fluid').hide();
        } else if ( $(this).val() == 'TB' ){
            $('.answer-container').hide();
            $('.text-container-fluid').show();
        } else if ($(this).val() == 'ZO'){
            $('.answer-container').hide();
            $('.text-container-fluid').hide();
        }
    });

    $('#mainQuestionTextSize').on('change wheel', function(){
        $('.main-question').css('font-size', $(this).val()+'pt');
        $(this).attr('value',$(this).val());
    });

    $('#answersTextSize').on('change wheel', function(){
        $('.button-answer').css('font-size', $(this).val()+'pt');
        $(this).attr('value',$(this).val());
    });

    $('#mainTitle').on('keyup', function(){
        $('.main-question').text($(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#imageSizeHead').on('change wheel', function(){
        $('.header-image').css({'max-width' : $(this).val(), 'max-height': $(this).val()});
        $(this).attr('value',$(this).val());
    });

    $('#imageSizeBody').on('change wheel', function(){
        $('.body-image').css({'max-width' : $(this).val(), 'max-height': $(this).val()});
        $(this).attr('value',$(this).val());
    });

    $('.button-0').on('click', function(e){
        e.preventDefault();
    });

    $('#ratingOne').on('keyup', function(){
        $('#ratingTextOne').text($(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#ratingThree').on('keyup', function(){
        $('#ratingTextThree').text($(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#pictureWidth').on('change wheel', function(){
        $('.header-image').css('width', $(this).val()+'px');
        $(this).attr('value',$(this).val());
    });

    $('#pictureTopMargin').on('change wheel', function(){
        $('.header-image').css('margin-top', $(this).val()+'px');
        $(this).attr('value',$(this).val());
    });

    $('#pictureBorderRadius').on('change wheel', function(){
        $('.header-image').css('border-radius', $(this).val()+'px');
        $(this).attr('value',$(this).val());
    });

    $('#pictureOpacity').on('change', function(){
        $('.header-image').css('opacity', $(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#shadowColor').on('focusout', function(){
        $('.button-0').css('box-shadow', '0 5px '+ $(this).val());
        $(this).attr('value',$(this).val());
    });

    //-----------

    $('#lineHeight').on('change wheel', function(){
        $('.main-question').css('line-height', $(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#textContainerFluidMargin').on('change wheel', function(){
        $('.text-container-fluid').css('margin-top', $(this).val()+'px');
        $(this).attr('value',$(this).val());
    });

    $('#mainPopupPadding').on('change wheel', function(){
        container.css('padding', $(this).val()+'px');
        $(this).attr('value',$(this).val());
    });

    $('#textTopMargin').on('change wheel', function(){
        $('.main-question').css('margin-top', $(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#letterSpacing').on('change wheel', function(){
        $('.main-question').css('letter-spacing', $(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#fontStyle').on('change', function(){
        $('.main-question').css('font-style', $(this).val());
        $(this).attr('value',$(this).val());
    });

    //fontWeight
    $('#fontWeight').on('change', function(){
        $('.main-question').css('font-weight', $(this).val());
        $(this).attr('value',$(this).val());
    });

    //answersContainerMarginTop
    $('#answersContainerMarginTop').on('change wheel', function(){
        $('.answer-container').css('margin-top', $(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#ratingTwo').on('keyup', function(){
        $('#ratingTextTwo').text($(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#text-one-width').on('change wheel', function(){
        $('#ratingTextOne').css('width', $(this).val()+'px');
        $(this).attr('value',$(this).val());
    });

    $('#text-two-width').on('change wheel', function(){
        $('#ratingTextTwo').css('width', $(this).val()+'px');
        $(this).attr('value',$(this).val());
    });

    $('#text-three-width').on('change wheel', function(){
        $('#ratingTextThree').css('width', $(this).val()+'px');
        $(this).attr('value',$(this).val());
    });


    $('#ratingTextSize').on('change wheel', function(){
        $('#ratingTextOne, #ratingTextTwo, #ratingTextThree').css('font-size', $(this).val()+'pt');
        $(this).attr('value',$(this).val());
    });

    $('#buttonText').on('keyup', function(){
        $('.inner-span-button').text($(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#buttonWidth').on('change wheel', function(){
        $('.button-0').css('width',$(this).val());
        $(this).attr('value',$(this).val());
    });

    $('#buttonHeight').on('change wheel', function(){
        $('.button-0').css('height',$(this).val());
        $(this).attr('value',$(this).val());
    });

});