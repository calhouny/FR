jQuery(document).ready(function ($) {
    'use strict';

    if($('.optin-form').length){

        $('.optin-form').each(function(){
            var $optin=$(this),$form=$('.optin-content form',$optin);

            $form.submit(function(event) {
                event.preventDefault();
            });

            $('.form_connector_submit',$form).click(function(){

                var name = $('.dt_name',$form).val(),email = $('.dt_email',$form).val(),mailListCode=$(".optin_code",$optin);

                var datas = {
                    action: 'dt_frontend_submit_connector',
                    name: name,
                    email: email,
                    url: $(".optin_code",$optin).find("form").attr("action")
                };

/*
                var datas = {
                    action: 'dt_frontend_submit_connector',
                    inf_form_name: name,
                    inf_field_Email: email,
                    url: $(".optin_code",$optin).find("form").attr("action")
                };
*/
                //alert($(".optin_code",$optin).find("form").attr("action"));
                $.ajax({ 
                    data: datas,
                    type: 'post',
                    url: ajaxurl,
                    success: function(data) {

//                       var findName = $(mailListCode).find('input[name*="name"], input[name*="NAME"], input[name*="Name"]').not("input[type=hidden]").val(name);
                       var findName = $(mailListCode).find("input[name*=name], input[name*=NAME], input[name*=Name]").not("input[type=hidden]").val(name);

        /*
                        if (!($(findName).exists())) {
                          $(mailListCode).find("input[type=text], input[type=email]").not("input[type=hidden]").first().val(name);
                        }
        */
                        var findEmail = $(mailListCode).find("input[name*=email], input[name*=EMAIL], input[type=email], input[name=eMail], input[name=inf_field_Email]").not("input[type=hidden]").val(email);

        /*
                        if (!($(findEmail).exists())) {
                          $(mailListCode).find("input[type=text], input[type=email]").not("input[type=hidden]").last().val(email);
                        }
        */
                        //alert(name);
                        //alert($(mailListCode).find("input[name*=name], input[name*=NAME], input[name*=Name]").not("input[type=hidden]").val());
                        $(mailListCode).find("input[type=submit], button, input[name=submit]").trigger('click');

                    }
                });


            });

        });



    }
});