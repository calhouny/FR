jQuery(document).ready(function ($) {


	$('.wpb_el_type_iconlists').each(function(){

		var $elem=$(this),$button=$('.toggle-iconlist',$elem);

		$elem.find('i.stand_icon').on('click',function(){
	        var clicked_icon = $(this).attr("data-icon-code");

	        $elem.find('.active').removeClass('active');
	        $(this).addClass('active').parent().addClass('active');
	        $elem.find('.icon-selection-value').val(clicked_icon);
	        $('.edit_form_icon',$elem).hide();
	        $button.attr('class','toggle-iconlist '+clicked_icon);

		});

		$button.on('click',function (el) {
			$('.edit_form_icon',$elem).toggle();
		});
	});

	$('.wpb_el_type_iconlists_multi').each(function(){

		var $elem=$(this),$button=$('.toggle-iconlist',$elem),$choices=$('.edit_form_icon_choice',$elem);

		$('li',$choices).click(function(){

			this.remove();

		});

		$elem.find('i.stand_icon').on('click',function(){
	        var clicked_icon = $(this).attr("data-icon-code");
	        var newicon=$('<li><i data-icon="'+clicked_icon+'" class="'+clicked_icon+'"></i></li>');
	        newicon.click(function(){
	        	this.remove();
	        });
	        $choices.append(newicon);
	        saveIcon();

		});

		var saveIcon=function(){

			var theicon=$('i',$choices);
			var select=[];


			theicon.each(function (index,el){

				select.push($(el).data('icon'));

			});

			$elem.find('.icon-selection-value').val(select.join(','));
		}

		$button.on('click',function (el) {

			$('.edit_form_icon',$elem).toggle();

			$(this).toggleClass('icon-minus-1');
		});



	});

	if($('.radio-options').length){
		$('.radio-options').each(function(){
			var $options=$(this),$option=$('.radio-option',$options),
			$input=$('input.wpb_vc_param_value',$options);
			$option.on('change',function(){
				$input.val($(this).val()).trigger('change');
			});
		});

	}

	if($('.option-select').length){
		$('.option-select').each(function(){

			var $elem=$(this),$input=$('input',$elem);


				$('.layout-option',$elem).click(function(){
					$input.val($(this).data('option'));
					$('.layout-option',$elem).removeClass('active');
					$(this).addClass('active');
					$input.trigger('change');
				});
					
		});
	}

	if($('.wpb-select-multiple').length){
		$('.wpb-select-multiple').each(function(){
			var $elem=$(this),$input=$('input',$elem.parents('.portcat'));

				$elem.change(function(){
					$input.val($(this).val());
					$input.trigger('change');
				});
					
		});
	}


	if($('.slider').length){
	    $('.slider').each(function(){
			$(this).on('change',function(){

				$(this).slider(
				{
	    			value:$(this).parent().find('input').val(),
	    			min:$(this).data('min'),
	    			max:$(this).data('max'),
	    			step:$(this).data('step'),
	    			slide:function(event,ui){
	    				$(this).parent().find('input').val(ui.value).trigger('change');
	    			}

	    		});


			});


			$(this).trigger('change');


		});
	}

	if($('.optin-select').length){
		$('.optin-select').each(function(){

			var $elem=$(this),$input=$('input',$elem);

				$('.optin-option',$elem).click(function(){
					$input.val($(this).data('optin'));
					$('.optin-option',$elem).removeClass('optin-active');
					$(this).addClass('optin-active');
					$input.trigger('change');
				});
		});

		var optinform=$('.optin-form');

		$('.select_optin_layout').on('change',function(){

			var formtype=$(this).val(),
			fieldname=$('.dt_name',optinform).parent(),
			container=$('[role=form]',optinform),
			element_margin=$('input[name=element_margin]').val();



			switch(formtype) {
			    case 'vertical_email':
				   		 $('input',optinform).css({'margin-bottom':element_margin+'px','margin-right':'0px'});
			    		container.removeClass('form-inline');
			    		fieldname.hide();
			        break;
			    case 'horizontal':
			    		container.addClass('form-inline');
			    		fieldname.show();
				   		 $('input',optinform).css({'margin-right':element_margin+'px','margin-bottom':'0px'});
			        break;
			    case 'horizontal_email':
			    		container.addClass('form-inline');
			    		fieldname.hide();
				   		 $('input',optinform).css({'margin-right':element_margin+'px','margin-bottom':'0px'});
			        break;
			    case 'vertical':
			    default:
				   		 $('input',optinform).css({'margin-bottom':element_margin+'px','margin-right':'0px'});
			    		container.removeClass('form-inline');
			    		fieldname.show();

			} 
		}).trigger('change');

		$('select[name=border_style]').on('change',function(){
			optinform.css('border-style',$(this).val());
		}).trigger('change');

		$('input[name=padding_top]').on('change',function(){
			
			if($('input[name=simply]').prop('checked')){
				optinform.css('padding',$(this).val().replace('px','')+'px');
			}else{
				optinform.css('padding-top',$(this).val().replace('px','')+'px');
			}
			
		}).trigger('change');

		$('input[name=padding_bottom]').on('change',function(){
			optinform.css('padding-bottom',$(this).val().replace('px','')+'px');
		}).trigger('change');

		$('input[name=padding_left]').on('change',function(){
			optinform.css('padding-left',$(this).val().replace('px','')+'px');
		}).trigger('change');

		$('input[name=padding_right]').on('change',function(){
			optinform.css('padding-right',$(this).val().replace('px','')+'px');
		}).trigger('change');


		$('input[name=border_top_width]').on('change',function(){
			
			if($('input[name=simply]').prop('checked')){
				optinform.css('border-width',$(this).val().replace('px','')+'px');
			}else{
				optinform.css('border-top-width',$(this).val().replace('px','')+'px');
			}
			
		}).trigger('change');

		$('input[name=border_bottom_width]').on('change',function(){
			optinform.css('border-bottom-width',$(this).val().replace('px','')+'px');
		}).trigger('change');

		$('input[name=border_left_width]').on('change',function(){
			optinform.css('border-left-width',$(this).val().replace('px','')+'px');
		}).trigger('change');

		$('input[name=border_right_width]').on('change',function(){
			optinform.css('border-right-width',$(this).val().replace('px','')+'px');
		}).trigger('change');


		$('input[name=simply]').on('click',function(){
			$(this).trigger('schange');
		})
		.on('schange',function(){

			if($(this).prop('checked')){
				$('input[name=padding_top]').trigger('change');
				$('input[name=border_top_width]').trigger('change');
			}
			else{
				$('input[name=padding_top]').trigger('change');
				$('input[name=padding_bottom]').trigger('change');
				$('input[name=padding_left]').trigger('change');
				$('input[name=padding_right]').trigger('change');
				$('input[name=border_top_width]').trigger('change');
				$('input[name=border_bottom_width]').trigger('change');
				$('input[name=border_left_width]').trigger('change');
				$('input[name=border_right_width]').trigger('change');
			}
		}).trigger('schange');
	}

    if($('.carousel-preview').length){

    	var carouselPreview=$('.carousel-preview');

   		$('input[name=pagination_size]').on('change',function(){
   			$('.owl-page span',carouselPreview).css({'width':$(this).val()+'px','height':$(this).val()+'px'});
   		}).trigger('change');

   		$('.vc-color-control[name=pagination_color]').wpColorPicker({
   			defaultColor:'#869791',
	        change:function(event,ui){
				$('.owl-page span',carouselPreview).css({'background-color':ui.color.toString()});
	        }
    	}).on('change',function(){
    		$('.owl-page span',carouselPreview).css({'background-color':$(this).val()});
    	}).trigger('change');

    	$('select[name=pagination_type]').live('change',function(){

    		if($(this).val()=='bullet'){
    			carouselPreview.parents('.vc-shortcode-param').show();
    		}
    		else{
    			carouselPreview.parents('.vc-shortcode-param').hide();
    		}

    	}).trigger('change');



	 }


		$('input[name=layout_type]').on('change',function(){

			var layout_type=$(this).val(),separator_position=$('select[name=separator_position]');

			if(layout_type=='section-heading-polkadot-two-bottom'
			|| layout_type=='section-heading-thick-border'
			|| layout_type=='section-heading-thin-border'
			|| layout_type=='section-heading-double-border-bottom'
			|| layout_type=='section-heading-thin-border-top-bottom'
			){
				separator_position.parents('.vc-shortcode-param').show();

			}
			else{
				separator_position.parents('.vc-shortcode-param').hide();
			}

		}).trigger('change');




    if($('.optin_button_preview').length){
    	$('.optin_button_preview').each(function(){
    		var $preview=$(this),$parent=$(this).parents('#vc-edit-form-tab-1');


    		$('input[name=expanded][type=checkbox]',$parent).on('change',function(){

				if($(this).prop('checked')){
					$preview.css('width','100%');
				}
				else{
					$preview.css('width','auto');
				}
			}).trigger('change');


    		$('input[name=gradient][type=checkbox]',$parent).on('change',function(){

				var gradient_color=$parent.find('.gradient_color_to').val();
				var colorbtn=$parent.find('.button_color').val();

				if($(this).prop('checked')){
					$preview.css({'background':'linear-gradient('+colorbtn+','+gradient_color+')'});
					$('.gradient-color-to.optin-preview',$parent).show();
				}
				else{
					$('.gradient-color-to.optin-preview',$parent).hide();
					$preview.css({'background':colorbtn});
				}

			}).trigger('change');

			$('input[name=element_margin]',$parent).on('change',function(){

				var formtype=$('.select_optin_layout').val();

				switch(formtype) {
				    case 'horizontal_email':
				    case 'horizontal':
				   		 $('.optin-form input',$parent).css('margin-right',$(this).val()+'px');
				        break;
				    case 'vertical_email':
				    case 'vertical':
				   		 $('.optin-form input',$parent).css('margin-bottom',$(this).val()+'px');
				    default:


				} 
			}).trigger('change');


			$('input[name=button_align]',$parent).on('change',function(){
				$preview.parents('.form-group').css('text-align',$(this).val());
			}).trigger('change');

			$('input[name=label_align]',$parent).on('change',function(){
				$('.optin-form input',$parent).css('text-align',$(this).val());
			}).trigger('change');


			$('select[name=button_font]',$parent).on('change',function(){
				$preview.css('font-family',$(this).val());
			}).trigger('change');

    		$('input[name=input_radius]',$parent).on('change',function(){
    			$('.optin-form input',$parent).css('border-radius',$(this).val()+'px');
    		}).trigger('change');


    		$('input[name=button_radius]',$parent).on('change',function(){
    			$preview.css('border-radius',$(this).val()+'px');
    		}).trigger('change');

    		$('input[name=font_size]',$parent).on('change',function(){
    			$preview.css('font-size',$(this).val()+'px');
    		}).trigger('change');

    		$('input[name=vertical_padding]',$parent).on('change',function(){

    			$('.optin-form input,.optin-form button',$parent).css({'height':$(this).val()+'px'});

    		}).trigger('change');

    		$('input[name=horizontal_padding]',$parent).on('change',function(){
    			$preview.css({'padding-left':$(this).val()+'px','padding-right':$(this).val()+'px'});
    		}).trigger('change');


    		$('input[name=input_horizontal_padding]',$parent).on('change',function(){
    			$('.optin-form input',$parent).css({'padding-left':$(this).val()+'px','padding-right':$(this).val()+'px'});
    		}).trigger('change');

    		$('.button_text',$parent).keyup(function(){
    			$preview.text($(this).val());
    		}).trigger('change');

    		$('.name_label',$parent).keyup(function(){
    			$preview.trigger('change');
    		});

    		$('.email_label',$parent).keyup(function(){
    			$preview.trigger('change');
    		});

			$('input[name=button_text_color]',$parent).on('change',function(){
				$preview.css({'color':$(this).val()});
			}).wpColorPicker({
				defaultColor:'#ffffff',
		        change:function(event,ui){
					$preview.css({'color':ui.color.toString()});
		        }
	    	}).trigger('change');

			$('input[name=button_color]',$parent).on('change',function(){
				if($('input[name=gradient][type=checkbox]',$parent).prop('checked')){

					var gradient_color=$parent.find('.gradient_color_to').val();
					$preview.css({'background':'linear-gradient('+$(this).val()+','+gradient_color+')'});
				}
				else{

					$preview.css({'background':$(this).val()});
				}
			})
			.wpColorPicker({
				defaultColor:'#444444',
		        change:function(event,ui){
		        	if($('input[name=gradient][type=checkbox]',$parent).prop('checked')){

						var gradient_color=$parent.find('.gradient_color_to').val();
						$preview.css({'background':'linear-gradient('+ui.color.toString()+','+gradient_color+')'});
					}
					else{

						$preview.css({'background':ui.color.toString()});
					}
		        }
	    	}).trigger('change');

    		$('input[name=gradient][type=checkbox]',$parent).on('change',function(){

				var gradient_color=$parent.find('.gradient_color_to').val();
				var colorbtn=$parent.find('.button_color').val();

				if($(this).prop('checked')){
					$preview.css({'background':'linear-gradient('+colorbtn+','+gradient_color+')'});
					$('.gradient-color-to.optin-preview',$parent).show();
				}
				else{
					$('.gradient-color-to.optin-preview',$parent).hide();
					$preview.css({'background':colorbtn});
				}

			}).trigger('change');

			$('input[name=gradient_color_to]',$parent).on('change',function(){

				if($('input[name=gradient][type=checkbox]',$parent).prop('checked')){

					var colorbtn=$parent.find('.button_color').val();
					$preview.css({'background':'linear-gradient('+colorbtn+','+$(this).val()+')'});
				}

    		}).
    		wpColorPicker({
				defaultColor:'#ffffff',
		        change:function(event,ui){
		        	if($('input[name=gradient][type=checkbox]',$parent).prop('checked')){

						var button_color=$parent.find('input[name=button_color]').val();
						$preview.css({'background':'linear-gradient('+button_color+','+ui.color.toString()+')'});
					}
		        }
	    	}).trigger('change');
	    	
	    	$('.vc-css-editor .vc-color-control[name=background_color]').wpColorPicker({
	    		change:function(event,ui){
	    			$('.optin-form').css('background',ui.color.toString());
		        }
	    	});

	    	$('.vc-css-editor .vc-color-control[name=background_color]').parents('.wp-picker-container').find('.wp-picker-clear').on('click',function(){
	    		$('.optin-form').css('background-color','inherit');
	    	});


	    	$('.vc-css-editor .vc-color-control[name=border_color]').wpColorPicker({
	    		change:function(event,ui){
	    			$('.optin-form').css('border-color',ui.color.toString());
		        }
	    	});

	    	$('.vc-css-editor .vc-color-control[name=border_color]').parents('.wp-picker-container').find('.wp-picker-clear').on('click',function(){
	    		$('.optin-form').css('border-color','inherit');
	    	});

    		$(this).on('change',function(){
    			var txtbtn=$parent.find('.button_text').val(),
	    			txtname=$parent.find('.name_label').val(),
	    			txtemail=$parent.find('.email_label').val(),
	    			vertical_padding=$parent.find('.vertical_padding').val(),
	    			horizontal_padding=$parent.find('.horizontal_padding').val(),
	    			button_radius=$parent.find('.button_radius').val(),
	    			font_size=$parent.find('.font_size').val(),
	    			bgcolor=$('.vc-css-editor .vc-color-control[name=background_color]').parents('.wp-picker-container').find('.wp-color-result').css('background-color'),
	    			bordercolor=$('.vc-css-editor .vc-color-control[name=border_color]').parents('.wp-picker-container').find('.wp-color-result').css('background-color'),
	    			borderstyle=$('select[name=border_style]').val();

	    		if(bgcolor.toString()=='rgb(247, 247, 247)'){
	    			bgcolor='inherit';
	    		}

	    		if(bordercolor.toString()=='rgb(247, 247, 247)'){
	    			bordercolor='inherit';
	    		}

    			$('.optin-form').css({'background-color':bgcolor,'border-color':bordercolor,'border-style':borderstyle});
    			$('input[name=dt_name]').val(txtname);
    			$('input[name=dt_email]').val(txtemail);
    			$(this).text(txtbtn);
    			$(this).css({
//    				'padding-top':vertical_padding+'px',
    				'height':vertical_padding+'px',
					'padding-left':horizontal_padding+'px',
					'padding-right':horizontal_padding+'px',
					'border-radius':button_radius+'px',
					'font-size':font_size+'px'
    			});
    		});

    		$preview.trigger('change');


    	});
    }
});
