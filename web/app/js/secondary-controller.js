/**
 * Created by dmitry on 02.03.16.
 */

var mainTestController;

var TSLController = false;

function mainObjectReady(){
    mainTestController = document.mainObject;
}

$( document ).ready(function() {
    var checkbox = $('[name="switchName"]');
    $(function () {
        var switcherEl = $('.switch-pm').switcher({
            style: "default",
            selected: false,
            language: "en",
            disabled: false
        });
    });

    $(document).on('click','.clearfix', function(){
        var atLeastOneIsChecked = $('input[name="switchName"]:checked').length;
        if(atLeastOneIsChecked == 1){
            $('.rotate-element').show();

            if(TSLController == false){
                mainTestController.initialization();
                modeElements();
            } else {
                mainTestController.firstAction();
            }
            TSLController = true;
        } else {
            $('.button-answer').unbind( "click" );
            $('.rotate-element').hide();
            //$('#main-pop-up-container').remove();
            var element = document.getElementById("main-pop-up-container");
            element.remove();
        }
    });

    function modeElements(){
        console.log("Preview mode started work");
    }
});