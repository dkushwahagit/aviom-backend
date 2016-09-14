$(function(){
    var siteUrl = window.location.origin;
    $('a.payment-plan').on('click', function (event) {

        var ach = event.target;
        var tcfId = $(ach).data('tcfId');
        var clientId = $(ach).data('clientId');
        var parentTr = $(ach).parents('tr');
        if ((clientId != undefined) && (tcfId != undefined)) {
            var paymentScheduleTr = $(parentTr).next('tr.payment-schedule');
            //alert(paymentScheduleTr.length);
            if (paymentScheduleTr.length <= 0) {
                $.ajax({
                    async       : false,
                    url         : siteUrl+'/my-payment-schedule',
                    data        : {tcfId:tcfId,clientId:clientId},
                    beforeSend  : function () {},
                    success      : function (data,textStatus,jqXHR) { 
                                    if (textStatus == 'success') {
                                        $(data).insertAfter(parentTr);
                                    }
                    },
                    error       : function (jqXHR,textStatus,errorThrown) {
                                   alert (errorThrown);
                    }
                });
            }
        }
    });
    
    /**
     * @profile form submission block
     */
    $('#profile-pic-form').submit(function (e) {
        e.preventDefault();
        var formData = new FormData($(this)[0]);
        $.ajax({
            async        : false,
            type         : 'POST',
            contentType  : false,
            cache        : false,
            contentType  : false,
            processData  : false,
            url          : siteUrl+'/update-my-profile-pic',
            data         : formData,
            dataType     : 'json',
            beforeSend   : function () {
                
            },
            success     : function (data,textStatus,jqXHR) {
                            
                            
                            if (data.ERROR === true) {
                                        var errorMsg = data.RESPONSE_MSG.CImage[0];
                                        var modalBody = $('div#msg div.modal-body');
                                        var msg = '<div class="alert alert-danger">Profile pic must be a file of type: jpeg, bmp, png, gif and size must be between 100 KB To 2 MB.</div>';
                                        $(msg).appendTo(modalBody);
                                        //$('#msg').modal();
                                       
                            }else{
                                var imge =  data.RESPONSE_DATA.fileName;
                                if (imge != undefined) {
                                    var src = 'https://s3-ap-southeast-1.amazonaws.com/sqy/customer/profilepic/'+imge;
                                    $('.circul > figure > img').attr('src',src);
                                    $('.userPic > img').attr('src',src);
                                }
                           }
                           
            },
            error        : function (jqXHR,textStatus,errorThrown) {
                alert(errorThrown);
            }
        });    
    });
    
    $('a.profile-btn').click(function () {
                var postDataObj = {};
                var formObj = $('#profile-form').serializeArray();
                $(formObj).each(function (index,v) {
                         postDataObj[v.name] = v.value;
                });
                var modalBody = $('div#msg div.modal-body');
                $(modalBody).html('');
                if ($('#Profiling').val() != '') {
                    $('#profile-pic-form').submit().delay(1000);
                 }
                
                //return false;
                //console.log(postDataObj);
                
                $.ajax({
                    async      : false,
                    type       : 'PUT',
                    url        : siteUrl+'/update-my-profile',
                    data       : postDataObj,
                    beforeSend : function () {},
                    success    : function (data,statusText,jqXHR) { 
                                
                                
                                    if (data.ERROR === false) {
                                        var msg = '<div class="alert alert-success">Profile Updated Successfully. </div>';
                                        $(msg).appendTo(modalBody);
                                        $('#msg').modal();
                                       
                                    }else {
                                        var msg = '<div class="alert alert-danger">Profile Could Not Be Updated . </div>';
                                        $(msg).appendTo(modalBody);
                                        $('#msg').modal();
                                    }
                    },
                    error      : function (jqXHR,statusText,errorThrown) {alert(errorThrown);},
                });
             
    });
    
    $('div.cameraIcon').click(function (e) {
      $('#Profiling').click();
      
    });
   
});
