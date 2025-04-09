jQuery(function ($) {
    /* Додати нумерацію до товарів */
    $('.woocommerce_order_items #order_line_items tr.item').each(function (index, el) {
        $(this).prepend(`<td class="index" width="1%">${index + 1}</td>`)
    })

    $('.woocommerce_order_items thead tr').prepend('<th>#</th>')

    /* Надсилання повідомлень через viber і sms */

    $('#sms-integration-send').on('click', function (e) {
        e.preventDefault();

        let data = {
            recipients: [
                $('#sms-integration-tel').val(),
            ],
            token: 'e3539bc20902d4322e776833c5f504cb3f44cd6a',
        };

        if ($('input[name=via]:checked').val() == 'sms') {
            data.sms = {
                sender: "BOGIKA",
                text: $('#sms-integration-message').val()
            };
        } else {
            data.viber = {
                sender: "BOGIKA",
                text: $('#sms-integration-message').val()
            }
        }

        $.get({
            url: 'https://api.turbosms.ua/message/send.json',
            data,
            dataType: 'json',
            success: function (data) {
                console.log(data, data.response_status)
                if (data.response_status === 'SUCCESS_MESSAGE_SENT') {
                    $('#sms-integration-message').val('').after('<div class="sms-integration-status sms-integration-status-success">Повідомлення успішно відправлено</div>');
                } else {
                    $('#sms-integration-message').val('').after(`<div class="sms-integration-status sms-integration-status-error">Повідомлення не відправлено. Помилка ${data.response_status}</div>`);
                }

                setTimeout(() => {
                    $('.sms-integration-status').remove();
                }, 4000)
            },
        })
    })

    $('#getlinktoliqpay').on('click', function () {
        let data = {
            action: 'generateliqpay',
            order_id: $('#post_ID').val(),
            description: bogikavars.description,
            amount: $('.wc-order-totals-items .wc-order-totals tr:last-child .total').text().trim().replace('₴', ''),
        }
        console.log(data)
        $.ajax(
            {
                url: ajaxurl,
                data,
                method: "POST",
                dataType: 'json',
                beforeSend: function () {
                    $('#getlinktoliqpay').attr('disabled', '');
                },
                success: function (data) {
                    $('#getlinktoliqpay').removeAttr('disabled');
                    let copyText = document.querySelector('.linktoliqpay');
                    copyText.classList.add('show')
                    copyText.value = (data.data.qr_code);
                    copyText.select();
                    copyText.setSelectionRange(0, 99999);
                    navigator.clipboard.writeText(copyText.value);
                }
            }
        )
    })

    if ($('.post-type-shop_order').length) {
        let ttn = $('#wcus_edit_order_ttn_metabox .wcus-icon-block .wcus-text-center.wcus-mb-5').text().trim();
        let tel = $('#_billing_phone').val();

        if (ttn) {
            getTtnTracking([{
                "DocumentNumber": ttn,
                "Phone": tel,
            }]).then((texts) => {
                $('#wcus_edit_order_ttn_metabox .wcus-icon-block .wcus-text-center.wcus-mb-5').after(`<div class="wcus-text-center">Статус: ${texts.data[0].Status}</div>`)
            })
        }
    }

    /* Показати статус посилки нової пошти */

    if ($('.nova_poshta_ttn_number').length) {
        let arr = [];
        jQuery('.nova_poshta_ttn_number').each((index, el) => {
            console.log(index, el)
            let ttn = $(el).find('div:first-child').text();
            let phone = $(el).find('div:nth-child(2)').text();
            if (!ttn || !phone) return;
            $(el).attr('data-ttn', ttn);
            arr.push({
                "DocumentNumber": ttn,
                "Phone": phone,
            });
        });
        getTtnTracking(arr).then((texts) => {
            $(texts.data).each(function (index, {Status: status, Number: ttn}) {
                $(`.nova_poshta_ttn_number[data-ttn=${ttn}]`).append(`<div>${status}</div>`)
            })
        })
    }

    async function getTtnTracking(docs) {
        let data = JSON.stringify({
            apiKey: 'a2ad9460fb895b9fbbf721ddc7995b53',
            modelName: 'TrackingDocument',
            calledMethod: 'getStatusDocuments',
            methodProperties: {
                Documents: docs
            },
        });
        return await $.ajax({
            data,
            dataType: 'json',
            headers: {
                'Content-Type': 'application/json'
            },
            type: 'POST',
            url: 'https://api.novaposhta.ua/v2.0/json/',
            xhrFields: {
                withCredentials: false
            },
        });
    }

    /* Показувати дані клієнта при створенні замовлення і присвоєння користувача
    до замовлення
    * */
    $('#customer_user').on('change', function () {
        // Get user ID to load data for
        var user_id = $('#customer_user').val();

        var data = {
            user_id: user_id,
            action: 'woocommerce_get_customer_details',
            security: woocommerce_admin_meta_boxes.get_customer_details_nonce
        };

        $.ajax({
            url: woocommerce_admin_meta_boxes.ajax_url,
            data: data,
            type: 'POST',
            success: function (response) {
                if (!response.meta_data) return;

                $('.user_computy_points, .user_computy_role').remove();

                let bfw_status = response.meta_data.find(o => o.key === 'bfw_status');
                let computy_point = response.meta_data.find(o => o.key === 'computy_point');

                if (bfw_status && bfw_status.value === '1') {
                    $('.wc-customer-user').after(`<p class="form-field form-field-wide user_computy_role"><strong>Статус клієнта: </strong>Член клубу</p>`);
                } else {
                    $('.wc-customer-user').after(`<p class="form-field form-field-wide user_computy_role"><strong>Статус клієнта: </strong>Покупець (клієнт)</p>`);
                }

                if (computy_point && computy_point.value) {
                    $('.wc-customer-user').after(`<p class="form-field form-field-wide user_computy_points"><strong>К-сть балів: </strong>${computy_point.value}</p>`);
                }

            }
        });

    })

    function addPoints(points) {
        let data = {
            action: 'bogika_admin_use_points',
            points,
            order_id: woocommerce_admin_meta_boxes.post_id,
        };
        $.ajax({
            url: woocommerce_admin_meta_boxes.ajax_url,
            data,
            type: 'POST',
            success: function (response) {
                window.location.reload();
            },
            error: function () {
                alert('Невідома помилка');
            }
        });
    }

    /* кнопка списання бонусних балів за замовлення */

    $('body').on('click', '.add-points', function () {
        $('.add-points').addClass('b-loading');

        let data = {
            action: 'bogika_admin_available_points',
            order_id: woocommerce_admin_meta_boxes.post_id,
        };
        $.ajax({
            url: woocommerce_admin_meta_boxes.ajax_url,
            data,
            type: 'POST',
            success: function (response) {
                let {message, vozmojniy_ball, computy_point} = response.data
                $('.add-points').removeClass('b-loading');
                let points = prompt(message)
                addPoints(points);
            }
        });
    })

    /* Кнопка для зміни статусу членства в клубі */

    $('body').on('click', '.changeclubstatus', function () {

        $(this).addClass('b-loading');

        let data = {
            action: 'bogika_admin_change_user_status',
            user_id: $(this).data('user_id')
        };


        $.ajax({
            url: ajaxurl,
            data,
            type: 'POST',
            success: function (response) {
                window.location.reload();
            }
        });
    })

    // console.log($('.calculate-action'))
    // $(document.body).on('order-totals-recalculate-before', function (e, dat) {
    //     console.log(data)
    //     let data = {
    //         order_id: woocommerce_admin_meta_boxes.post_id,
    //         items:    $( 'table.woocommerce_order_items :input[name], .wc-order-totals-items :input[name]' ).serialize(),
    //         action:   'woocommerce_save_order_items',
    //     };
    // })

});
