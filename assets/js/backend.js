jQuery(function ($) {
    let timeout;

    function phoneMask() {
        // Get the input element
        const input = document.querySelector('#billing_phone');
        console.log(input)

// Create a function to format the entered value as a Ukrainian phone number
        const formatPhoneNumber = (value) => {
            // Remove any non-digit characters except for the plus sign
            let digits = value.replace(/[^\d+]/g, '');

            // Add the Ukrainian country code if it's not already present
            if (digits.length === 10 && !digits.startsWith('+')) {
                digits = '+38' + digits.replace('+', '');
            }

            // Format the digits as a Ukrainian phone number
            return digits.replace(/(\+\d{3})(\d{3})(\d{3})(\d{2})(\d{2})/, '$1 ($2) $3-$4-$5');
        };

        if (input) {
            // Add an event listener to the input element to format the entered value on input
            input.addEventListener('input', (event) => {
                console.log(event)
                // Get the current cursor position
                const cursorPosition = event.target.selectionStart;

                // Format the entered value as a Ukrainian phone number
                event.target.value = formatPhoneNumber(event.target.value);

                // Set the cursor position to its original position
                event.target.setSelectionRange(cursorPosition, cursorPosition);
            });

            if (input.value) {
                input.value = formatPhoneNumber(input.value);
            }
        }
    }

    jQuery('.woocommerce.single-product, .woocommerce-cart').on('spin', '.spinner', function () {
        $(this).trigger('change');
        timeout = setTimeout(function () {
            jQuery("[name='update_cart']").click(); // trigger cart update
        }, 300); // 1 second delay, half a second (500) seems comfortable too
    });

    $(document.body).on('update_checkout updated_wc_div', function () {
            console.log('updated');
            initSpinner();
            initVerticalScroll();
        }
    );
    $(document.body).on('updated_checkout', function () {
        console.log('init');
        initVerticalScroll();
    })



    $(".slider").on('init', function () {
        seoViewPromotion();
		  $(this).addClass('_ready');
    })

    $(".slider").on('click', '.btn', function (e) {
        e.preventDefault();
        seoViewPromotion();
        window.location = $(this).attr('href');
    })

    $(".slider").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: !1,
        dots: !0,
        fade: !0,
        autoplay: !0,
        autoplaySpeed: 4e3
    }).on('afterChange', function () {
        seoViewPromotion();
    })

    /* Сео при переключенні слайду в хедері */
    function seoViewPromotion() {
        let slide = $('.slider .slick-current');
        dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
        dataLayer.push({
            'event': 'view_promotion',
            'ecommerce': {
                creative_name: slide.find('.title').text(),
                creative_slot: 'main_slider',
                promotion_id: 'main_slider',
                promotion_name: slide.find('.title').text(),
            }
        });
    }

    $(document.body).on('wc_fragments_refreshed added_to_cart removed_from_cart', function () {
        console.log('wc_fragment_refreshed' + Date.now());
        // if (sessionStorage.mini_cart_open) {
        //     $('.basket-box').addClass('active');
        // }
        // setTimeout(()=>{
        initVerticalScroll();
        initSpinner();
        $(".cross-sells-products").slick({
            slidesToShow: 2, slidesToScroll: 1, arrows: true, responsive: [{
                breakpoint: 768, settings: {slidesToShow: 1, slidesToScroll: 1}
            }]
        })
        // }, 50)
    })

    $('.ui-button').on('click', function() {
        setTimeout(()=>{
        $(".cross-sells-products").slick({
            slidesToShow: 2, slidesToScroll: 1, arrows: true, responsive: [{
                breakpoint: 768, settings: {slidesToShow: 1, slidesToScroll: 1}
            }]
        });
        }, 500)
    });


    $('form.variations_form').on('submit', function (event) {
        event.preventDefault();
        const $form = $(this);
        const data = {
            action: 'woocommerce_add_variation_to_cart',
            product_id: $form.find('input[name=product_id]').val(),
            variation_id: $form.find('input[name=variation_id]').val(),
            quantity: $form.find('input[name=quantity]').val(),
            variation: $form.serialize()
        };
        $.ajax({
            type: 'post',
            url: window.location.origin + '/wp-admin/admin-ajax.php',
            data: data,
            beforeSend: function () {
                $form.find('.variation-add-to-cart-disabled').removeClass('variation-add-to-cart-enabled').addClass('variation-add-to-cart-loading');
            },
            success: function (data) {
                // if (data && data.fragments) {
                //
                //     $.each(data.fragments, function (key, value) {
                //         $(key).replaceWith(value);
                //     });
                //
                //     if ($supports_html5_storage) {
                //         set_cart_hash(data.cart_hash);
                //
                //         if (data.cart_hash) {
                //             set_cart_creation_timestamp();
                //         }
                //     }
                //
                //     sessionStorage.setItem( 'wc_cart_created', ( new Date() ).getTime() );
                $('.single_add_to_cart_button').next('.wc-forward').remove();
                $('.single_add_to_cart_button').after(data.data.forwardbtn);
                
                $(document.body).trigger('wc_fragment_refresh');
                // }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error')
            }
        });
        return false
    });
    jQuery(document).on('spin', '.mini_cart_item .quantity', (event, ui) => {
        let cart_item_key = $(event.target).attr('name').replace(/cart\[([\w]+)\]\[qty\]/g, "$1");
        let item_quantity = $(this).val();
        let currentVal = parseFloat(item_quantity);
        $.ajax({
            type: 'POST',
            url: window.location.origin + '/wp-admin/admin-ajax.php',
            data: {action: 'update_item_from_cart', 'cart_item_key': cart_item_key, 'qty': ui.value,},
            success: function (data) {
                if (data.fragments) {

                    $.each(data.fragments, function (key, value) {
                        $(key).replaceWith(value);
                    });

                }
                initSpinner();
                initVerticalScroll();
                jQuery(document.body).trigger('wc_fragment_refresh');
            }
        });
        // setTimeout(updateMiniCartQuantity, 300);
    });

    function updateMiniCartQuantity() {
        // document.querySelector('.spinner').classList.toggle('active');
        let cartForm = jQuery('.minicart-form');
        let formData = cartForm.serialize();
        console.log(formData);
        jQuery('<input />').attr('type', 'hidden')
            .attr('name', 'update_cart')
            .attr('value', 'Update Cart')
            .appendTo(cartForm);
        jQuery.ajax({
            type: cartForm.attr('method'),
            url: cartForm.attr('action'),
            data: formData,
            dataType: 'html',
            success: function (response) {

                let wc_cart_fragment_url = (wc_cart_fragments_params.wc_ajax_url).replace("%%endpoint%%", "get_refreshed_fragments");
                jQuery.ajax({
                    type: 'post',
                    url: wc_cart_fragment_url,
                    success: function (response) {
                        console.log('success')
                        jQuery(document.body).trigger('wc_fragment_refresh');
                    },
                    complete: function () {
                        // cartForm = jQuery('.shop_table.cart form');
                        // document.querySelector('.spinner').classList.toggle('active');
                    }
                });
            }
        });
    }

    if (!$('body').hasClass('.single-product')) {
        $('#respond p.stars a').on('click', function () {
            let $star = $(this),
                $rating = $(this).closest('#respond').find('#rating'),
                $container = $(this).closest('.stars');

            $rating.val($star.text());
            $star.siblings('a').removeClass('active');
            $star.addClass('active');
            $container.addClass('selected');

            return false;
        })

        $('#respond #submit').on('click', function () {
            let $rating = $(this).closest('#respond').find('#rating'),
                rating = $rating.val();

            if ($rating.length > 0 && !rating && backendvars.review_rating_required === 'yes') {
                window.alert(backendvars.i18n_required_rating_text);

                return false;
            }
        });
    }

    document.addEventListener('wpcf7mailsent', function (event) {
        if (event.detail.contactFormId === 144) {
            $('.email-block-section').addClass('thanks').find('.email-block-text').hide();
            $('.email-block-section').find('.email-block-text-response').show();
        }
    }, false);

    $("body").on('alg_wc_wl_toggle_wl_item', function (e) {
        if (e.response.success) {
            if (jQuery('.bogika-wishlist-table').length) {
                e.target.closest('li').remove();
            }
            if (jQuery('.bogika-wishlist-table li').length == 0) {
                jQuery('.bogika-wishlist-table').remove();
                jQuery('.alg-wc-wl-empty-wishlist').show();
                $('.alg-wc-wl-social').remove();
            }
        }
    });
    if ($('.comment-form').length) {
        $('.comment-form').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                rating: {
                    required: true,
                },
                author: {
                    required: true,
                },
                comment: {
                    required: true,
                    minlength: 5,
                }
            },
            messages: {
                rating: backendvars.required_error,
                author: backendvars.required_error,
                email: {
                    required: backendvars.required_error,
                    email: backendvars.email_error,
                },
                comment: {
                    required: backendvars.required_error,
                    minlength: backendvars.min_length_error,
                }
            }
        });
    }

    let isPickupShippingSelected = function () {
        let currentShipping = jQuery('.shipping_method').length > 1 ?
            jQuery('.shipping_method:checked').val() :
            jQuery('.shipping_method').val();

        console.log(currentShipping)
        return currentShipping && currentShipping.match(/^local_pickup.+/i);
    };

    $('body').on('updated_checkout', function () {
        let arr = ['billing_address_1', 'billing_address_2', 'billing_city', 'billing_postcode', 'billing_state'];
        if (isPickupShippingSelected()) {
            $('#wc-pickup-fields').css('display', 'block');
            $(arr).each(function (index, el) {
                console.log($(`#${el}_field`), 'none')
                $(`#${el}_field`).css('display', 'none');
            })
        } else {
            $('#wc-pickup-fields').css('display', 'none');
            // $(arr).each(function (index, el) {
            //     console.log($(`#${el}_field`), 'none')
            //     $(`#${el}_field`).css('display', 'none');
            // })
        }
    })

    $('body').on('found_variation', function (e, variation) {
        if ($(`.product-view[data-id=${variation.variation_id}]`)) {
            const index = $(`.product-view[data-id=${variation.variation_id}]`).closest('.slick-slide').data('slick-index');
            $('.slider-for').slick('slickGoTo', index);
        }
    });
    $('#sortby-catalog').select2({
        placeholder: backendvars.sortingtext,
        minimumResultsForSearch: -1
    });
    $('body').on('change', '#sortby-catalog', function (e) {
        const url = new URL(window.location.href);
        if (url.searchParams.get('orderby')) {
            url.searchParams.delete('orderby');
        }
        url.searchParams.set('orderby', $(this).val());
        window.history.replaceState(null, null, url); // or pushState
        window.location.reload()
    })

    if ($('#sortby-catalog').length) {
        $(document).on("premmerce-filter-updated", function() {
            // window.location.reload()
            $('#sortby-catalog').select2({
                placeholder: backendvars.sortingtext,
                minimumResultsForSearch: -1
            });
        })
    }

    if ($('form.checkout').length) {
        phoneMask();
        add_shipping_info_seo_text();
    }
});

jQuery(function ($) {
    $(document.body).on('added_to_cart', function (e, fragments, cart_hash, button) {
        let item;
        // console.warn('addded')
        if (button.hasClass('single_add_to_cart_button')) {
            const quantity = $('form.cart input[name="quantity"]').val() || 1;
            item = PrepareSeoItem($(button).data('seo'), {
                quantity: Number(quantity),
            });
            setTimeout(function() {
                $('.btn-basket').trigger('click');
                $('.basket-box').addClass('active');
                $('.body').addClass('open-basket');
            }, 2500);
        } else {
            item = PrepareSeoItem($(button).data('seo'));
        }
        let id = button.data('product_id') ?? button.data('id');
        dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
        dataLayer.push({
            event: 'add_to_cart',
            value: Number(item.price) * item.quantity,
            items: [
                {
                    id: `${id}`, // Уникальный ID товара, до 100 символов.
                    google_business_vertical: 'retail'
                }
            ]
        });

        dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
        dataLayer.push({
            event: "addToCart",
            ecommerce: {
                items: [item]
            }
        });
    })

    $(document.body).on('removed_from_cart', function (e, fragments, cart_hash, button) {
        let item = PrepareSeoItem(button.data('seo'));
        dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
        dataLayer.push({
            event: "removeFromCart",
            ecommerce: {
                items: [item]
            }
        });
    });

    $('body').on('click', '.goods-box', function (e) {
        // e.preventDefault();
        if (!$(e.target).closest('.add_to_cart_button').length) {
            window.dataLayer = window.dataLayer || [];
            let item = PrepareSeoItem($(this).closest('.goods-box').data('seo'));
            dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
            dataLayer.push({
                event: "productClick",
                ecommerce: {
                    items: [item]
                }
            });

            dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
            dataLayer.push({
                event: "select_item",
                ecommerce: {
                    items: [item]
                }
            });
        }

        // window.location = $(this).closest('.goods-box').data('href');
    })

    $(document.body).on('alg_wc_wl_toggle_wl_item', function (e) {
        if (e.response.data.action === 'added') {
            dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
            dataLayer.push({
                event: "add_to_wishlist",
                ecommerce: {
                    items: [PrepareSeoItem($(e.target).closest('.goods-box').data('seo'))]
                }
            });

        }
    })

    $(document.body).on('mini_cart_open', function () {
        dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
        dataLayer.push({
            event: "view_cart",
            ecommerce: {
                items: $('.minicart-form').data('seo')
            }
        });

    })

    $(document.body).on('change', 'input[name="shipping_method[0]"]', add_shipping_info_seo_text)

    /* Відправка сео-коду при зміні способу доставки */
    function add_shipping_info_seo_text() {
        console.log('add_shipping_info_seo_text')
        dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
        dataLayer.push({
            'event': 'add_shipping_info',
            'shipping_tier': $('input[name="shipping_method[0]"]:checked').next().text(),
            'ecommerce': {
                'items': $('.checkout.woocommerce-checkout').data('seo')
            }
        });
    }

    $(document.body).on('payment_method_selected', function () {
        dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
        dataLayer.push({
            'event': 'add_payment_info',
            'payment_type': $('.wc_payment_method.active label').text().trim(),
            'ecommerce': {
                'items': $('.checkout.woocommerce-checkout').data('seo')
            }
        });
    })

    if ($('html').attr('lang') == 'uk') {
        $.datepicker.regional.uk = {
            closeText: "Закрити",
            prevText: "Попередній",
            nextText: "найближчий",
            currentText: "Сьогодні",
            monthNames: ["Січень", "Лютий", "Березень", "Квітень", "Травень", "Червень",
                "Липень", "Серпень", "Вересень", "Жовтень", "Листопад", "Грудень"],
            monthNamesShort: ["Січ", "Лют", "Бер", "Кві", "Тра", "Чер",
                "Лип", "Сер", "Вер", "Жов", "Лис", "Гру"],
            dayNames: ["неділя", "понеділок", "вівторок", "середа", "четвер", "п’ятниця", "субота"],
            dayNamesShort: ["нед", "пнд", "вів", "срд", "чтв", "птн", "сбт"],
            dayNamesMin: ["Нд", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
            weekHeader: "Тиж",
            dateFormat: "dd.mm.yy",
            altFormat: "yy-mm-dd",
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ""
        };
        $.datepicker.setDefaults($.datepicker.regional.uk);
        $('#account_birthday').datepicker({
            ...$.datepicker.regional["uk"],
            maxDate: "-1",
            changeMonth: true,
            changeYear: true,
            altField: "#actualDate",
            yearRange: "1950:2022"
        });
    } else {
        $('#account_birthday').datepicker({
            maxDate: "-1",
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd.mm.yy",
            altFormat: "yy-mm-dd",
            altField: "#actualDate",
            yearRange: "1950:2022"
        });
    }

});

function PrepareSeoItem(data, changedata = {}) {
    let item = data;

    item.price = Number(item.price);
    item.item_name = item.item_name.trim();
    item.quantity = Number(item.quantity);
    return {...item, ...changedata};
}


$( document ).ready(function() {


    setTimeout(function() {
        function setMainMarginTop() {
            var headerHeight = $('header').outerHeight();
            $('main').css('margin-top', headerHeight  + 'px');
        }
        setMainMarginTop()
        $('html, body, main').animate({scrollTop: 0}, 800);
    }, 450);

});





$('.top-bar .close-block').click(function() {
    var headerHeight = $('.header-main').outerHeight();
    $('main').css('margin-top', headerHeight + 'px');
});

jQuery(document).on('click', ".wc-bogo-modal-body button[type='submit']", function () {
    
    setTimeout(function () {
        location.reload();
    }, 1000); 
});


$(document).on('click', '.alg-wc-wl-view-state-add, .alg-wc-wl-view-state-remove', function () {
    
    setTimeout(function () {
      $('.iziToast-close').trigger('click');
    }, 5000);
  });

$(document).ready(function () {
    $('.faq-question').on('click', function () {
        const $answer = $(this).next('.faq-answer');

        if ($answer.is(':visible')) {
            $(this).removeClass('active');
            $answer.slideUp();
        } else {
            $('.faq-answer').slideUp();
            $('.faq-question').removeClass('active');
            $(this).addClass('active');
            $answer.slideDown();
        }
    });
});




   

$(document).on('click', '.add_to_cart_button', function (e) {
    
    if ($(this).hasClass('product_type_variable')) {
       
        return;
    }
    setTimeout(function() {
        $('.btn-basket').trigger('click');
        $('.basket-box').addClass('active');
        $('.body').addClass('open-basket');
    }, 2500);
});


$(document).ready(function() {
    $('.filter__inner').each(function() {
        let $filterInner = $(this);
        let $items = $filterInner.find('.filter__properties-item');
        let itemsToShow = 6;

        if ($items.length > itemsToShow) {
            $items.slice(itemsToShow).hide();
            let $button = $('<button class="filter__toggle-btn">Показати більше</button>');
            $filterInner.append($button);

            $button.on('click', function() {
                if ($items.filter(':hidden').length > 0) {
                    $items.slideDown();
                    $button.text('Показати менше');
                } else {
                    $items.slice(itemsToShow).slideUp();
                    $button.text('Показати більше');
                }
            });
        }
    });
});







