/**
 * Created by dmitry on 02.03.16.
 */
$( document ).ready(function() {
    var base_url = window.location.origin;
    $('.back-button-popup').on('click', function(){
        window.location.href = $('#prev-page').val();
    });

    $('.btn-back-user-action').on('click', function(){
        window.history.back();
    });
    $('.reload-page').on('click', function(){
        event.preventDefault();
        location.reload();
    });

    $('.rebuild-the-page, .save-popup').on('click', function(){
        event.preventDefault();
        var blind = $('#modal-b');
        blind.modal('show');
        var stat = $(this).attr('data-stl');
        var resultPopUp = $('#user-popup-container');
        var formData = new FormData(resultPopUp[0]);
        $.ajax({
            type: 'POST',
            url: base_url+'/rebuildForm?data-stl='+stat,
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function(data){
                blind.modal('hide');
                if(stat == 'SV') {
                    window.location.href = $('#prev-page').val();
                } else {
                    location.reload();
                }
            },
            statusCode: {
                500: function() {
                    alert( "Something went wrong on the server side!" );
                }
            }
        });
    });

    $('.extract').on('click', function(){
       var data_id = $(this).siblings('.popup-id').val();
       var formData = [];
       var that = $(this);
        $.ajax({
            type: 'POST',
            url: base_url+'/extractFromArchive?data_id='+data_id,
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function(data){
                that.parent().parent().fadeOut(2000, function(){$(this).remove()});
            },
            statusCode: {
                500: function() {
                    alert( "Something went wrong on the server side!" );
                }
            }
        });


    });




});