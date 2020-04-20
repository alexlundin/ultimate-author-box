jQuery(document).ready(function($){



//Profile Custom Template hide/show
$('.uab-select-template').on('change',function(){
	selected_template = $('option:selected',this).val();
	if(selected_template=='uab-custom-template'){
		$(this).closest('.uab-profile-content-wrapper').find('.uab-personal-template-select').show();
		$(this).closest('.uab-profile-content-wrapper').children('.uab-template-image-preview').hide();

	}else{
		$(this).closest('.uab-profile-content-wrapper').find('.uab-personal-template-select').hide();
		$(this).closest('.uab-profile-content-wrapper').find('.uab-template-image-preview').show();
		var image_path = uab_variable.plugin_image_path + '/uab-template-screenshorts/'+selected_template+'.PNG';
		$(this).closest('.uab-profile-content-wrapper').find('.uab-template-image-preview img').attr('src',image_path);
	}
});





	$('.uab-custom-settings-content-wrapper').on('change','.uab-custom-template',function(){
		var post_option = $('option:selected',this).val();
		chooseTemplateOptions(post_option);

	});

	$('.uab-profile-content-wrapper').on('change','.uab-custom-template',function(){
		var post_option = $('option:selected',this).val();
		var image_path = uab_variable.plugin_image_path + '/uab-template-screenshorts/'+post_option+'.PNG';
		$(this).closest('.uab-personal-template-select').find('.uab-template-image-preview img').attr('src',image_path);
		chooseTemplateOptions(post_option);
	});




/**
 * Media Uploader for Custom Profile Image
 *
 * @since 1.0.0
 */
 if($('.uab_upload_image_url').val() != ''){
 	$('.image-preview').show();
 }else{
 	$('.image-preview').hide();
 }

 $('.uab_upload_image_button').click(function(e) {
 	e.preventDefault();

 	var image = wp.media({
 		title: 'Upload Image',
 		multiple: false
 	}).open()

 	.on('select', function(e){
 		var uploaded_image = image.state().get('selection').first();
 		console.log(uploaded_image);
 		var image_url = uploaded_image.toJSON().url;
 		$('.uab_upload_image_url').val(image_url);
 		$('.current-image').find('img').attr('src', image_url);
 		if($('.uab_upload_image_url').val(image_url) != ''){
 			$('.image-preview').show();
 		}else{
 			$('.image-preview').hide();
 		}
 	});
 });

 /**
 * Profile Social Icons Sortable js
 *
 * @since 1.0.0
 */
$('.uap-social-outlets-fields').sortable({containment: "parent"	});


/**
 * Get a random integer between `min` and `max`.
 *
 * @param {number} min - min number
 * @param {number} max - max number
 * @return {int} a random integer
 */
 function eg_getRandomInt(min, max) {
 	return Math.floor(Math.random() * (max - min + 1) + min);
 }

/**
 * Generates random string
 *
 * @param {int} length
 * @returns {string}
 *
 * @since 1.0.0
 */
 function eg_generate_random_string(length) {
 	var string = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
 	var random_string = '';
 	for (var i = 1; i <= length; i++) {
 		random_string += string[eg_getRandomInt(0, 61)];
 	}
 	return random_string;

 }



 function choosePostSelectionType(post_option, currentTabID){
 	switch(post_option) {
 		case 'uab_selective_posts':
 		currentTabID.find('.uab-selective-posts').show();
 		break;
 		default:
 		currentTabID.find('.uab-selective-posts').hide();
 	}
 }

 $('.uab-backend-tabs').on('change',' select.uab_post_select',function(){
 	var currentTabID = $('#'+$(this).closest('.uab-recent-post-wrapper').parent().attr('id'));
 	var post_option = $('option:selected',this).val();
 	choosePostSelectionType(post_option, currentTabID);
 });

/**
 * Profile Image Selector
 *
 * @since 1.0.0
 */

function chooseImageType(){
	var image_option = $('.uab_image_select :selected').val();
	switch(image_option) {
		case 'uab_upload_image':
		$('.uab-image-upload-option-wrapper').hide();
		$('.uab-custom-image-upload-wrapper').show();
		break;
		default:
		$('.uab-image-upload-option-wrapper').hide();
	}
}

$('.uab_image_select').on('change',function(){
	chooseImageType();
});

 /**
 * Code Mirror for Custom CSS
 *
 * @since 1.0.0
 */
 $('.uab-layout-setting-tab').on('click', function() {
 	codeMirrorDisplay();

 });

 function codeMirrorDisplay() {
 	var $codeMirrorEditors = $('.uab-codemirror-textarea');
 	$codeMirrorEditors.each(function(i, el) {
 		var $active_element = $(el);
 		if ($active_element.data('cm')) {
 			$active_element.data('cm').doc.cm.toTextArea();
 		}
 		var codeMirrorEditor = CodeMirror.fromTextArea(el, {
 			lineNumbers: true,
 			lineWrapping: true
 		});
 		$active_element.data('cm', codeMirrorEditor);
 	});
 }

//Widget JS
	var AjaxUrl                   = uab_variable.ajax_url;
	var admin_nonce               = uab_variable._wpnonce;
	var selected_widget_limits    = uab_variable.selected_widget_limits;
	var saving_data               = uab_variable.saving_msg;
	var saved_data                = uab_variable.saved_data;

	$('.lists_widgets').hide();

	$('.uab-backend-tabs').on('click','.uab_add_widgets',function(){
		widgetList = $(this).closest('.uab-select-widget-wrapper').find('.lists_widgets');
		widgetList.show();
	});

	$('.uab-profile-backend-wrapper').on('click', '.all_wp_widgets', function(){
		var count = $(this).parents('.uab-widget-wrapper').find('.uab_widget_area').length;
		var listed_widget = $(this).parents('.uab-widget-wrapper').find('.uab_listed_widgets');
		var append_listed_widget = $(this).parents('.uab-widget-wrapper').find('.listed_selected_widgets');
		var widget_id =$(this).attr('data-value');
		var widget_title =$(this).attr('data-text');
		var tab_key = $(this).closest('.uab-widget-wrapper').find('#uab_tab_key').attr('name');


		if(count == 1){
			alert(selected_widget_limits);
			$(this).parents('.lists_widgets').hide();
		}else{

			var widgets = {
				action: "add_selected_widget",
				widget_id: widget_id,
				title: widget_title,
				widget_key: tab_key,
				nonce : admin_nonce
			};

			$.post(AjaxUrl, widgets, function (response) {
				var widget = $(response.data);
				listed_widget.show();
				append_listed_widget.append(widget);
				add_events_widget(widget);
			});

			$(this).parents('.lists_widgets').hide();
		}

	});
	function add_events_widget(widget) {
		var widget_instance = $(this);
		var widget_title = widget.find(".widget_title span.wptitle");
		var widget_edit = widget.find(".widget-action");
		var widget_inner = widget.find(".widget_inner");
		var widget_id = widget.attr("data-id");

		widget_edit.on('click',function(){
			widget_inner.toggle();
			if (! widget.hasClass("uab_open") && ! widget.data("uab_loaded")) {
				widget_title.addClass('uab_loading');
				$.post(AjaxUrl, {
					action: "edit_widget_data",
					widget_id: widget_id,
					_wpnonce: admin_nonce,
					dataType : 'html'
				}, function (response) {

					var $response = $(response);
					var $form = $response.find('form');


					$(".uab_delete", $form).on("click", function (e) {
						e.preventDefault();

						var data = {
							action: "uab_delete_widget",
							widget_id: widget_id,
							_wpnonce: admin_nonce
						};

						$.post(AjaxUrl, data, function (delete_response) {
							widget.remove();

						});

					});

					$(".uab_close", $form).on("click", function (e) {
						e.preventDefault();
						widget.toggleClass("uab_open");
						widget_inner.hide();
					});


					$form.on("submit", function (e) {
						e.preventDefault();
						var dataa = $(this).serialize();
						$('.uab_save_data').show();
						$('.saving_msg').text(saving_data);
						$.post(AjaxUrl, dataa, function (submit_response) {
							$('.uab_save_data').fadeOut('slow');
						});

					});

					widget_inner.html($response);

					widget.data("uab_loaded", true).toggleClass("uab_open");

					widget_title.removeClass('uab_loading');

				});

			} else {

				widget.toggleClass("uab_open");
			}

			$(".uab_widget_area").not(widget).removeClass("uab_open");

		});

	}

	var widget_area = $('.uab_listed_widgets').find('.listed_selected_widgets');
	$('.uab_widget_area', widget_area).each(function() {
		add_events_widget($(this));
	});

	if ($('#uab-custom-desc-status').prop('checked')) {
		$('.user-custom-description-wrap').show();
	}
	$('#uab-custom-desc-status').on('click',function(){
		if ($(this).prop('checked')) {
			$('.user-custom-description-wrap').show();
		}
		else{
			$('.user-custom-description-wrap').hide();
		}
	});

	uabChangeSelection();
	uab_light_checkbox('.uab-bulb-switch');
	$('.uab-bulb-switch').on('click',function(){
		uab_light_checkbox(this);
	});

});


function uabChangeSelection(){
	jQuery('.uab-guest-details-group .uab_selector_field').on('change',function(){
		var selected_group = jQuery(this).attr('id');
		var selected_field = jQuery(this).val();
		jQuery('.uab-guest-details-group .uab_guest_image_selector').hide();
		jQuery('.uab-guest-details-group .uab_'+selected_field+'_option').show();
	});
	jQuery('.uab-guest-details-group .uab_selector_field').trigger('change');
}



function uab_light_checkbox(current){
	if (jQuery(current).prop('checked')) {
		jQuery(current).parent().parent().siblings('.uab-light-bulb').show();
	}
	else{
		jQuery(current).parent().parent().siblings('.uab-light-bulb').hide();
	}
}