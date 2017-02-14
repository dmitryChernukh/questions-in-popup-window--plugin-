/**
 * Created by dmitry on 01.04.16.
 */
var mainZicController;
function mainObjectSecondReady(){
    mainZicController = document.mainObject;
}

var mainTestController;
function mainObjectReady(){
    mainTestController = document.mainObject;
}

var TSPController = false;

$( document ).ready(function() {
    var base_url = window.location.origin;
    $("#create-result-popup-url").validate({
        rules: {
           url: "required"
        },
        messages: {
           url: "Please enter the URL"
        },
        submitHandler: function(form) {
            $('#modal-b').modal('show');
            var popUpId = $('#popup-id-select').val();
            var siteForm = $('#create-result-popup-url');
            var formData = new FormData(siteForm[0]);
            var that = $(this);
            $.ajax({
                url: base_url+'/addSiteToPopup?index='+popUpId,
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                success: function(data)
                {
                    if(data.error == 'Exist' ){
                        $('#modal-b').modal('hide');
                        var err = $('.show-error-site');
                        err.show();
                        setTimeout(function(){
                            $('.show-error-site').fadeOut(4000, function(){$(this).hide()})
                        }, 3000);
                        return false;
                    }

                    $('#modal-b').modal('hide');
                    var block = $('.alert-success-message');
                    block.empty().append('<strong>Success!</strong> The data has been successfully saved!');
                    block.fadeOut(1000, function(){$(this).show()});
                    setTimeout(function(){
                        $('.alert-success-message').fadeOut(2000, function(){$(this).hide()})
                    }, 3000);
                    var statusSelected = {
                        http: data.body['site-protocol'] == 'http://' ? 'selected="selected"' : ' ',
                        https: data.body['site-protocol'] == 'https://' ? 'selected="selected"' : ' ',
                        radio: data.body['enabled-status'] == 'on' ? 'checked="checked"' : ' '
                    };
                    var tableBody = $('#site-body-elements');

                    var sitePage = data.body['internal-link'];

                    var UP = data.elementStatus == 'UP' ? 'checked' : '';
                    var RP = data.elementStatus == 'RP' ? 'checked' : '';

                    var style1 = data.elementStatus == 'RP' ? 'display: block;' : 'display: none;';
                    var style2 = data.elementStatus == 'UP' ? 'display: block;' : 'display: none;';

                    var randomeString = randomString(4);

                    var insertBody = '<tr class="success">'+
                        '<th scope="row">'+data.siteId+'</th>'+
                        '<input type="hidden" value="'+data.siteId+'" name="site-id" id="site-id" class="listener-row">'+
                        '<td>'+
                        '<select name="protocol" class="form-control listener-row" id="">'+
                            '<option value="http://" '+statusSelected.http+'>http</option>'+
                            '<option value="https://" '+statusSelected.https+'>https</option>'+
                        '</select>'+
                        '</td>'+
                        '<td>'+
                        '<input name="site-url" value="'+data.body.url+'" class="form-control listener-row">'+
                            '</td>'+

                        '<td>'+
                        '<input name="site-page" value="'+sitePage+'" class="form-control listener-row">'+
                        '</td>'+

                            '<td class="switcher-container-deep">'+
                            '<select style="'+style1+'" class="form-control listener-row inner-select-result" name="popup-id" id="">';

                            $.each(data.popups, function( index, value ) {
                                var optionSelect = data.body['popup-id'] == value.id ? 'selected="selected"' : ' ';
                                insertBody += '<option value="'+value.id+'" '+optionSelect+'>'+value.name+'</option>';
                            });

                            insertBody += '</select> <select style="'+style2+'" class="form-control listener-row inner-select-user" name="user-popup-id">';

                            $.each(data.usersPopup, function( index, value ) {
                                var optionSelect = data.body['user-popup-id'] == value.id ? 'selected="selected"' : ' ';
                                insertBody += '<option value="'+value.id+'" '+optionSelect+'>'+value.popupName+'</option>';
                            });

                    insertBody += '</select></td>'+
                        '<td>'+
                            '<div class="radio next-radio">'+
                                '<label><input class="radio-position listener-row radio-status-indent" '+UP+' type="radio" value="UP" name="'+randomeString+'-element">User\'s popup</label>'+
                            '</div>'+
                            '<div class="radio next-radio">'+
                                '<label><input class="radio-position listener-row radio-status-indent" '+RP+' type="radio" value="RP" name="'+randomeString+'-element">Result popup</label>'+
                            '</div>'+
                        '</td>'+
                        '<td>'+
                        '<input name="enabled-status" class="form-control status-enabled-site listener-row" type="checkbox" '+statusSelected.radio+' >'+
                            '</td>'+
                        '<td>'+
                            '<span class="label label-danger button-action-element remove-current-site">Remove</span><br>'+
                            '<span class="label label-primary button-action-element hidden-button" >Save</span>'+
                        '</td>'+
                        '</tr>';

                    tableBody.append(insertBody);

                    var row = $('.listener-row');
                    row.unbind('change');
                    var hidden = $('.hidden-button');
                    var radioSelect = $('.radio-status-indent');
                    var deleteElement = $('.remove-current-site');
                    var radBlock = $('.radio-position');
                    hidden.unbind('click');
                    radBlock.unbind('click');
                    radioSelect.unbind('click');
                    deleteElement.unbind('click');
                    hidden.on('click',function(){
                        updateSiteAction($(this));
                    });
                    radBlock.on('click', function(){
                        radioPosition($(this));
                    });
                    radioSelect.on('click', function(){
                        displayFromRadio($(this));
                    });
                    row.on('change', function(){
                        displayButton($(this));
                    });
                    deleteElement.on('click',function(){
                        deleteCurrentSite($(this));
                    });

                }
            });
        },
        invalidHandler: function(event, validator) {
            return false;
        }
    });

    var randomString = function(length) {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for(var i = 0; i < length; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        return text;
    };

    function displayButton(that){
        that.parent().parent().find('.hidden-button').css({'display':'inline-block'});
    }

    function displayFromRadio(that){
        that.parent().parent().parent().parent().find('.hidden-button').css({'display':'inline-block'});
    }

    function updateSiteAction(that){
        $('#modal-b').modal('show');
        var data = that.parent().parent().find('.listener-row');
        var Object = {};
        $.each(data, function( index, value ) {
            var name = ($(value).attr('name'));
            if($(value).attr('type') == 'checkbox') {
                Object[name] = $(value).prop('checked');
            } else if($(value).attr('type') == 'radio'){
                if($(value).prop('checked') == true){
                    Object['radioStatus'] = $(value).val();
                }
            } else {
                Object[name] = $(value).val();
            }
        });

        $.ajax({
            url: base_url+'/saveSiteChanges',
            type: "POST",
            data: JSON.stringify(Object),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            cache: false,
            processData:false,
            success: function(data)
            {
                if(data.error == 'Exist' ){
                    $('#modal-b').modal('hide');
                    var err = $('.show-error-site');
                    err.show();
                    setTimeout(function(){
                        $('.show-error-site').fadeOut(4000, function(){$(this).hide()})
                    }, 3000);
                    return false;
                }
                that.hide();
                $('#modal-b').modal('hide');
                var block = $('.alert-success-message');
                block.empty().append('<strong>Success!</strong> The data has been successfully updated!');
                block.fadeOut(1000, function(){$(this).show()});
                setTimeout(function(){
                    $('.alert-success-message').fadeOut(2000, function(){$(this).hide()})
                }, 3000);
            }
        });
    }

    function deleteCurrentSite(that){
        var siteId = that.parent().parent().find('#site-id').val();

        var body = $('#site-body-elements-prim');
        var children = body.find('.success');
        $.each(children, function(element, object){
            var intermediate = $(object).find('.listener-row').val();
            if(siteId == intermediate){
                $(object).remove();
            }
        });

        $('#modal-b').modal('show');
        $.ajax({
            url: base_url+'/removeCurrentSite',
            type: "POST",
            data: 'dateId='+siteId,
            cache: false,
            processData:false,
            success: function(data)
            {
                that.parent().parent().remove();
                $('#modal-b').modal('hide');
                var block = $('.alert-success-message');
                block.empty().append('<strong>Status!</strong> The data has been successfully removed!');
                block.fadeOut(1000, function(){$(this).show()});
                setTimeout(function(){
                    $('.alert-success-message').fadeOut(2000, function(){$(this).hide()})
                }, 3000);
            }
        });
    }

    var device = [
        'Mobile',
        'Tablet',
        'Mobile & Tablet',
        'Desktop',
        'iOS',
        'iPad',
        'iPhone',
        'iPod',
        'Android',
        'Android Phone',
        'Android Tablet',
        'BlackBerry',
        'Windows'
    ];

    $('.save-result-form-action').on('click', function(){
        var blind = $('#modal-b');
        blind.modal('show');
        var siteId = $('.select-popup-name').val();
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
                var body = $('#site-body-elements-prim');
                var children = body.find('.success');
                $.each(children, function(element, object){
                    var intermediate = $(object).find('.listener-row').val();
                    if(data.siteId == intermediate){
                        $(object).remove();
                    }
                });

                var appValue;
                data.site.appValue === null ? appValue = '' : appValue = data.site.appValue;
                var stripString = '<tr class="success">'+
                '<input type="hidden" value="'+data.siteId+'" name="site-id" id="site-id" class="listener-row">'+
                '<td>'+
                    '<input value="'+data.siteId+' : '+data.site.siteUrl+' - page: '+data.site.subsite+'" class="form-control listener-row" name="site-url" readonly>'+
                '</td>'+
            '<td class="condition-style">'+
                '<select class="form-control role-element change-appearance" name="display-conditions" id="" >'+
                    '<option value="SI" '; if(data.site.appearance == 'SI'){ stripString += 'selected="selected"'; } stripString += ' >Show immediately on page entry</option>'+
                    '<option value="SA" '; if(data.site.appearance == 'SA'){ stripString += 'selected="selected"'; } stripString += ' > Show after a set number of seconds</option>'+
                    '<option value="SV" '; if(data.site.appearance == 'SV'){ stripString += 'selected="selected"'; } stripString += '>Show if the user visits a set number of pages</option>'+
                    '<option value="SC" '; if(data.site.appearance == 'SC'){ stripString += 'selected="selected"'; } stripString += ' >Show only on certain URLS</option>'+
                    '<option value="SE" '; if(data.site.appearance == 'SE'){ stripString += 'selected="selected"'; } stripString += ' >Show if the user is about to exit the site</option>'+
                    '<option value="SD" '; if(data.site.appearance == 'SD'){ stripString += 'selected="selected"'; } stripString += ' >Show only if the user is on a certain device</option>'+
                    '<option value="SO" '; if(data.site.appearance == 'SO'){ stripString += 'selected="selected"'; } stripString += ' >Show only once on a single page (not showing on repeat visits)</option>'+
                '</select>'+
            '</td>'+
            '<td class="value-col">'+
            '<input value="'+appValue+'" class="form-control att-dop-value role-element" name="dop-value"';

                if(data.site.appearance != 'SD') {
                    stripString += 'style="display: block"';
                }

                stripString += '><select name="device-block" class="form-control att-device-block role-element"';
                if(data.site.appearance == "SD"){
                    stripString += 'style="display: block"';
                }
                stripString += '>'+
                '<option value="0">None</option>';

                var selected = '';
                for (var i = 0; i <= device.length; i++){
                    selected = '';
                    if(data.site.appValue == device[i]){
                        selected = 'selected="selected"';
                    }
                    if(device[i] === undefined)
                        continue;
                    stripString += '<option value="'+device[i]+'" '+selected+'>'+device[i]+'</option>';
                }

                stripString += '</select>'+
            '</td>'+
            '<td class="position-id">'+
                '<select name="popup-position" class="form-control role-element">';
                var selectedNumber = '';
                for (var n = 0; n <= data.position.length - 1; n++){
                    selectedNumber = '';
                    if(data.position[n]['name'] == data.site.positionName){
                        selectedNumber = 'selected="selected"';
                    }
                    stripString += '<option value="'+data.position[n]["id"]+'" '+selectedNumber+'>'+data.position[n]['name']+'</option>';
                }
                stripString += '</select>'+
            '</td>'+
                    '<td>' +
                    '<button type="button" class="btn btn-primary update-site-conditions">Save/Update</button>' +
                    '</td>'+
            '</tr>';

                $.each($(stripString).find('.role-element'), function(data, element){
                    $(element).on('change keyup', function(){
                        showButton($(this));
                    })
                });

                var changeElement = $(stripString).find('.change-appearance');
                $(changeElement).on('change', function(){
                    changeAppearance($(this));
                });
                body.append(stripString);

                blind.modal('hide');
                $('.message-top-sv').fadeOut(2000, function(){$(this).show()});
                setTimeout(function(){
                    $('.message-top-sv').fadeOut(2000, function(){$(this).hide(); })
                }, 2000);
            }
        });
    });

    $(document).on('change', '.change-appearance', function(){
        changeAppearance($(this));
    });

    function changeAppearance(that){
        var currentValue = that.val();
        var dopValue = that.parent().next().find('.att-dop-value');
        var deviceValue = that.parent().next().find('.att-device-block');
        if(currentValue == 'SD'){
            deviceValue.show();
            dopValue.hide();
        } else {
            deviceValue.hide();
            dopValue.show();
        }
    }

    $(document).on('change keyup', '.role-element', function(){
        showButton($(this));
    });

    function showButton(that){
        var element = that.parent().parent().find('td').last().find('.update-site-conditions');
        element.show();
    }

    $(document).on('click', '.update-site-conditions', function(){
        var blind = $('#modal-b');
        blind.modal('show');
        var id = $(this).parent().parent().find('input').first().val();
        var condition = $(this).parent().parent().find('.condition-style').children('select').val();
        var dopValue = $(this).parent().parent().find('.value-col').find('.att-dop-value').val();
        var dopDevice = $(this).parent().parent().find('.value-col').find('.att-device-block').val();
        var insertValue;

        switch (condition) {
            case 'SI':
                insertValue = null;
                break;
            case 'SA':
                insertValue = parseInt(dopValue);
                if(isNaN(insertValue)){
                    insertValue = 2;
                }
                break;
            case 'SV':
                insertValue = parseInt(dopValue);
                if(isNaN(insertValue)){
                    insertValue = 2;
                }
                break;
            case 'SC':
                insertValue = dopValue;
                break;
            case 'SE':
                insertValue = null;
                break;
            case 'SD':
                insertValue = dopDevice;
                break;
            case 'SO':
                insertValue = null;
                break;
            default:
                insertValue = null;
        }
        var displayPosition = $(this).parent().parent().find('.position-id').find('.role-element').val();
        var that = $(this);
        $.ajax({
            url: base_url+'/saveInRow',
            type: "POST",
            data: 'siteId='+id+'&condition='+condition+'&value='+insertValue+'&position='+displayPosition,
            success: function(data)
            {
                blind.modal('hide');
                that.hide();
            }
        });
    });


    $('.radio-position').on('click', function(){
        radioPosition($(this));
    });

    function radioPosition(that){
        var index = that.val();

        if(index == 'RP') {
            that.parents([3]).children('.switcher-container-deep').children('.inner-select-user').hide();
            that.parents([3]).children('.switcher-container-deep').children('.inner-select-result').show();
        } else if (index == 'UP'){
            that.parents([3]).children('.switcher-container-deep').children('.inner-select-result').hide();
            that.parents([3]).children('.switcher-container-deep').children('.inner-select-user').show();
        }
    }

    $('#userpopup-action-hidden').on('click', function(){
        $('.user-block-master').show();
        $('.result-block-master').hide();
    });

    $('#result-action-hidden').on('click', function(){
        $('.result-block-master').show();
        $('.user-block-master').hide();
    });


    $('#userpopup-action-stl').on('click', function(){
        $('.user-block-master-stl').show();
        $('.result-block-master-stl').hide();
    });

    $('#result-action-stl').on('click', function(){
        $('.result-block-master-stl').show();
        $('.user-block-master-stl').hide();
    });




    $('.update-site-area').on('click', function(){
        var blind = $('#modal-b');
        blind.modal('show');

        var selectArea = $(this).siblings('.col-lg-3').children('.select-popup-name');

        $.ajax({
            url: base_url+'/getListOfSites',
            type: "POST",
            data: null,
            contentType: false,
            cache: false,
            processData:false,
            success: function(data)
            {
              selectArea.empty();
              blind.modal('hide');
                $.each(data.list, function( index, value) {
                    selectArea.append('<option value="'+value.id+'">Node number - '+value.id+'; '+value.siteUrl+' - page: '+value.subsite+'</option>')
                });
            }
        });
    });

    $('.listener-row').on('change', function(){
        displayButton($(this));
    });

    $('.radio-status-indent').on('click', function(){
        displayFromRadio($(this));
    });

    $('.hidden-button').on('click', function(){
        updateSiteAction($(this));
    });

    $('.remove-current-site').on('click', function(){
        deleteCurrentSite($(this));
    });

    $('#preview-result-action').on('change', function(){
        var elements = $(this).val().split('//');

        $('.input-group-addon-protocol').val(elements[0]+'//');
        $('#basic-url').val(elements[1]);
    });

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

                var dataForm = $("#show-prew-form").serializeArray();
                var data = {};
                $(dataForm).each(function(index, obj){
                    data[obj.name] = obj.value;
                });

                if(data.attachedElement == 'UP'){
                    mainZicController.getSeparatePopUp(data['user-popup-id'], data.attachedElement);
                } else if (data.attachedElement == 'RP'){
                    mainZicController.getSeparatePopUp(data['popup-id'], data.attachedElement);
                }
            } else {
                $('.button-answer').unbind( "click" );
                $('.rotate-element').hide();
                var elem = document.getElementById("main-pop-up-container");
                elem.remove();
            }
        });
});