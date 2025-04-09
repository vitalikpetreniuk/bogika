<script>
    function send_cart_analytic()    {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', // сделали запрос
            type: "POST", // указали метод
            data: { // передаем параметры отправляемого запроса
                action: 'get_cart_data', // вызываем хук который обработает наш ajax запрос
            },

            success: function (response) { // получаем результат в переменной data
                eS('sendEvent', 'StatusCart', {
                    'StatusCart': response.data.data,
                    'GUID': response.data.guid
                });
                sessionStorage.setItem("GUID", response.data.guid);
            }
        });
    }
    jQuery(document).ready(function($){
        $('body').on( 'added_to_cart', function(i,e) {
            send_cart_analytic();
        });
        $('body').on( 'updated_cart_totals', function(i,e) {
            send_cart_analytic()
        });
        $('body').on( 'remove_from_cart', function(i,e) {
            send_cart_analytic()
        });
        $('body').on( 'click', '.mini-cart__wrap .quantity .ui-button', function(i,e) {
            window.setTimeout(send_cart_analytic,2000);
        });
        $('body').on( 'click', '.mini-cart__wrap .remove_from_cart_button', function(i,e) {
            window.setTimeout(send_cart_analytic,2000);
        });

    });
</script>


