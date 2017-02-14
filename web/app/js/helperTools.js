/**
 * Created by dmitry on 25.03.16.
 */


$( document ).ready(function() {
    var base_url = window.location.origin;
    hideArchivePopUp();

    var actionArea =  $('.input-group-btn');
    var parentAgent = actionArea.parent().parent().find('input[name="entity"]').val();
    if(parentAgent == 'PopUp'){
        actionArea.append($('<button type="button" class="btn btn-primary add-separate-result-popup">Survey popups</button>').css({"border-radius":"5px", "margin-left":"10px", "background-color":"#3990FF"}));
        actionArea.find('a').css({"border-radius":"5px"});
        var headTitle = $('.col-sm-5');
        headTitle.append($('<button type="button" class="btn btn-warning go-to-archive">Archive</button>').css({"background" : "darkorange", "float" : "right"}));
        headTitle.find('.title').css({"float": "left"});
    }

    if($('button').is(".add-separate-result-popup")){
        $('.add-separate-result-popup').on('click', function(){
            window.location.href = '/view/resultPopup';
        })
    }

    $('.go-to-archive').on('click', function(){
        window.location.href = '/view/archiveContainer';
    });

    var that = this;
    $.ajax({
        url: window.location.origin+'/getActivePopUp', // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: [], // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData:false,        // To send DOMDocument or non processed data file it is set to false
        success: function(data)   // A function to be called if request succeeds
        {
           var newData =  jQuery.parseJSON(data);
            function addCheckbox(response){

                var table = $('.table').find('tbody').find('tr');
                var tableHead = $('.table').find('thead').find('tr').find('th');

                    if('PopUp' == $('.title').text().replace(/^\s+|\s+$/g, "")){
                        var pointer = 0;
                        $.each(tableHead, function(row, element){
                            if(pointer == 6){
                                $(element).after('<th data-property-name=" statusActivity" class="string">  <a href=""><i class="fa fa-sort"></i>Status activity</a></th>');
                            }
                            pointer++;
                        });

                        $.each(table, function(data, value){
                            var popupId = $(value).attr('data-id');
                            var i;
                            var isChecked = '';
                            for(i = 0; i <= response.length - 1; i++){
                                if(response[i].popUpId == popupId){
                                    if(response[i].enabled == true){
                                        isChecked = 'checked="checked"';
                                    }
                                    break;
                                }
                            }
                            $.each($(value).find('td'), function(index, element){
                                if(index == 6){
                                    $(element).after('<td data-label="Checkbox" class=" "><input class="original-selector" type="checkbox" '+isChecked+' value="'+popupId+'"/></td>');
                                }
                            });
                        });
                    } else return false;
            }
            addCheckbox(newData);
        }
    });


    function hideArchivePopUp(){
        var formData = [];
        var table = $('.table').find('tbody').find('tr');

        $.ajax({
            url: base_url+'/allPopUp',
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function(data)
            {
                $.each(data.popUp, function(index, element){
                    if(element.archive === true){
                        var id = parseInt(element.id);
                        $.each(table, function(index, element){
                            if(id == parseInt($(element).attr('data-id'))){
                                $(element).hide();
                                console.log('Hide element - '+id);
                            }
                        })
                    }
                });
            }
        });
    }

    $(document).on('click', '.original-selector', function(){
       var parentElementId = $(this).parent().parent().attr('data-id');
       var status;

        if($(this).prop('checked') == true){
            status = 1;
        } else {
            status = 0;
        }
        $.ajax({
            url: window.location.origin+'/setTheCheckbox', // Url to which the request is send
            type: "POST",             // Type of request to be send, called as method
            data: "parentElement="+parentElementId+"&status="+status, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            success: function(data)   // A function to be called if request succeeds
            {

            }
        });
    })
});
