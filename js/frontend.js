jQuery(document).ready(function ($) {
    $('.uab-tab-index-wrapper').each(function () {
        let select = $(this);
        if (select.parent().hasClass('uab-template-10') || select.parent().hasClass('uab-template-14')) {
            select.find('.uab-select-tab-header').on('click', function () {
                select.find('ul.uab-tabs').slideToggle(300);

                select.find('ul.uab-tabs li').each(function () {
                    let selector = $(this);
                    selector.on('click', function () {
                        let header = $(this).attr('data-name');
                        selector.parent().siblings('.uab-select-tab-header').html(header);
                        $(this).parent('ul').hide();
                    });
                });
            });
        }
    });

    $('.uab-footer-twitter-feed').slick({
        infinite: true,
        speed: 5000,
        autoplay: true,
        //centerMode: true,
        pauseOnHover: true,
        slidesToShow: 1,
        adaptiveHeight: true,
        swipeToSlide: true,
        //variableWidth:true,
        centerPadding: '60px',
        //rtl:true,
    });


    $('.uab-author-slider').each(function () {
        let SlideToShow;
        let containerWidth = parseInt($(this).closest('.UAB_Author_List_Widget').width());
        // console.log('width: '+containerWidth);
        if (containerWidth > 768) {
            SlideToShow = 5;
        } else if (containerWidth < 768 && containerWidth > 480) {
            SlideToShow = 3;
        } else {
            SlideToShow = 1;
        }
        $(this).slick({
            centerMode: true,
            centerPadding: '0px',
            slidesToShow: SlideToShow,
            // autoplay: true,
            // infinite: true,
            autoplaySpeed: 5000,
            responsive: [
                {
                    breakpoint: 771,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 481,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '0px',
                        slidesToShow: 1
                    }
                }
            ]
        });

    });

//var tabs = $( "#uab-frontend-tab" ).tabs({hide: { effect: "fadeOut", duration: 250 },show: { effect: "fadeIn", duration: 250 }});
    let tabCounter = $("#uab-tab-index-wrapper .uab-tabs li").length;
    if (tabCounter === '1') {
        $('#uab-tab-index-wrapper').hide();
    }
    ;

    $('.uap-contact-form-wrapper form.uab-contact-form').submit(function () {
        let selector = $(this);
        let to = selector.find('.uab-to-field').val();
        //alert(to);
        let from = selector.find('.uab-from-field').val();
        let email = selector.find('.uab-email-field').val();
        let phone = selector.find('.uab-phone-field').val();
        let subject = selector.find('.uab-subject-field').val();
        let message = selector.find('.uab-message-field').val();
        selector.find('.uab-ajax-loader').show();
        $.ajax({
            url: uab_js_obj.ajax_url,
            type: 'post',
            data: {
                action: 'uab_sendmail',
                _wpnonce: uab_js_obj._wpnonce,
                to: to,
                from: from,
                email: email,
                phone: phone,
                subject: subject,
                message: message
            },
            success: function (response) {
                //alert(response);
                if (response === 'success') {
                    selector.find('.uab-ajax-loader').hide();
                    selector.find('.uap-success-message').show();
                    selector.find('.uab-from-field').val("");
                    selector.find('.uab-email-field').val("");
                    selector.find('.uab-phone-field').val("");
                    selector.find('.uab-subject-field').val("");
                    selector.find('.uab-message-field').val("");
                } else {
                    //alert('Failure');
                }
                console.log("success");
            }
        });

        return false;
    });


// Frontend Tabs js
    $('ul.uab-tabs li').click(function () {
        let tab_id = $(this).attr('data-tab');


        $(this).closest('ul.uab-tabs').find('.tab-link').removeClass('uab-current');
        //alert($(this).closest('#uab-frontend-wrapper').find("#"+tab_id).attr('id'));
        $(this).closest('.uab-frontend-wrapper').find('.uab-tab-content').removeClass('uab-current');

        $(this).addClass('uab-current');
        $(this).closest('.uab-frontend-wrapper').find("#" + tab_id).addClass('uab-current');
    });

// Frontend Tabs js
    $('select.uab-tabs').on('change', function () {
        let tab_id = $(this).val();
        /*    $('.uab-tab-content').removeClass('uab-current');
        $("#"+tab_id).addClass('uab-current');*/
    });

//Template-6 contact div count
    count = 0;
    className = '';
    $('.uab-template-6 .uab-short-contact').find('.uab-contact-inner').each(function () {
        if ($(this).contents().length != 0) {
            count++;
            className = "uab-contact-count-" + count;
        }
    });

    $('.uab-template-6 .uab-short-contact').addClass(className);

//Widget Pop Up js @since version 1.0.4
    $('.UAB_Author_List_Widget .uab-widget-popup').on('click', function () {
        let uab_popup = $(this);
        let author_id = $(this).find('span.uab-info').data('userid');
        //alert(author_id);
        $.post(uab_js_obj.ajax_url, {
            _ajax_nonce: uab_js_obj._wpnonce_popup,
            action: "uab_show_popup",
            author_id: author_id
        }, function (data) {
            let uab_popup_area = uab_popup.closest('.UAB_Author_List_Widget');
            uab_popup_area.after(data);
            uab_popup_area.siblings('.uab-frontend-popup-wrapper').fadeIn();
            uab_popup_area.siblings('.uab-frontend-popup-wrapper').find('.uab-popup-close').on('click', function () {
                $(this).closest('.uab-frontend-popup-wrapper').fadeOut('400', function () {
                    $(this).remove();
                });
            });
        });
    });

    /*custom click js*/
    $('.uab-click-me').on('click', function () {

        $(this).closest('.uab-tab-content.uab-current').siblings('.uab-tab-index-wrapper').find('.tab-link.uab_author_detail').removeClass('uab-current').siblings('.tab-link').addClass('uab-current');
        $(this).closest('.uab-tab-content.uab-current').removeClass('uab-current').siblings('.uab-tab-content').addClass('uab-current');

    });

    let redirect = 0;
    let anchor_timeout = 1000;
    let target_anchor = $('.uab-frontend-wrapper-outer').first().children('.uab-frontend-inner-layer.uab_anchor_box');

    if (target_anchor.hasClass('uab_anchor_box')) {
        anchor_timeout = parseInt(target_anchor.data('timeout'));
        redirect = target_anchor.offset().top - 100;
        setTimeout(function () {
            jQuery('html, body').animate({scrollTop: parseFloat(redirect)}, 1500, 'swing');
        }, parseInt(anchor_timeout));
    }


});

function switch_image(current) {
    let parent = jQuery(current).parent();
    jQuery(current).closest('.uab-linkedin-basic').find('.uab-linkedin-profile-name').append('<img class="uab-linkedin-country-img" src="http://www.geonames.org/flags/x/' + parent.data('code') + '.gif">');
    jQuery(current).remove();
    parent.append('<span class="uab_country_logo">' + parent.data('code') + '</span>');
}