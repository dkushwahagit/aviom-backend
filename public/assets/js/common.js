(function( $ ) {
$.fn.loader = function(action){
       if (action === 'open') {
           var content = '<div style="position:absolute;margin:-10px 0 0 -100px;left: 50%;top: 50%;width: 200px;height: 100px;z-index: 2;background:#000;color:#fff;font-weight:bolder;text-align:center"><img src="'+window.location.origin+'/images/1.gif" /></div>';
           $('<div id="myLoader">'+content+'</div>')
            .appendTo('body')
            .css({
                        width:"100%",
                        height:window.screen.availHeight+"px",
                        position:"fixed",
                        "z-index":1,
                        left:0,
                        top:0,
                        "background-color":"rgba(0,0,0, 0.9)",
                        "overflow-x":"hidden",
                        transition:"0.5s"
                    });
       }
       if (action === 'close') {
           $('#myLoader').detach();
           
       }
};
}(jQuery));

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
                                    $('.circul > figure > img').prop('src',src);
                                    $('.userPic > img').prop('src',src);
                                }
                           }
                           
            },
            error        : function (jqXHR,textStatus,errorThrown) {
                alert(errorThrown);
            }
        });    
    });
    
    $('a.profile-btn').click(function () {
                $.fn.loader('open');
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
                                
                                $.fn.loader('close');
                                    if (data.ERROR === false) {
                                        var msg = '<div class="alert alert-success">Profile Updated Successfully. </div>';
                                        $(msg).appendTo(modalBody);
                                        $('#msg').modal();
                                       setTimeout(function () { window.location.reload(); },2000);
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
    
    $('#reset-password-form').submit(function (e) {
        e.preventDefault();
        $.ajax({
            async      : false,
            url        : window.location.origin+'/reset-password',
            type       : 'POST',
            data       : $(this).serialize(),
            beforeSend : function () {$.fn.loader('open');},
            success    : function (data,statusText,jqXHR) {
                            if(data.ERROR == false) {
                                var msg = '<div class="alert alert-success">'+data.RESPONSE_MSG+'</div>';
                                $(msg).insertBefore('#reset-password-form');
                            }else{
                                var msg = '<ul class="alert alert-danger">';
                                $.each(data.RESPONSE_MSG,function (index,err) {
                                    msg = msg+'<li>'+err+'</li>';
                                });
                                $(msg).insertBefore('#reset-password-form');
                            }
                            $('#reset-form').trigger('click');
                            $.fn.loader('close');
                            setTimeout(function () {
                                    $('.alert').detach();
                                },2000);
                         },
            error      : function (jqXHR,statusText,errorThrown) {
                          alert(errorThrown);
            }
        });
    });
    
    $('.ticket-interaction').click(function (e) {
        e.preventDefault();
        var parentTr = $(this).parents('tr');
        var ciid = $(this).data('interactionId');
        $.ajax({
            async    : false,
            url      : window.location.origin+'/service-request-detail/'+ciid,
            beforeSend : function () { $.fn.loader('open');if($('.timelineBox').length > 0) { $('.timelineBox').detach(); }},
            success  : function (data,statusText,jqXHR) {
                           //alert(data);
                           $(data).insertAfter('.table-responsive');
                           $.fn.loader('close');
            },
            error    : function (jqXHR,statusText,errorThrown) {
                alert(errorThrown);
            }
        });
    });
   
});
