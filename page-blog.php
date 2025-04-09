<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package bogika
 */

get_header(); ?>
<?php if ( ! get_field( 'hide_title' ) ) { ?>
    <h1><?php the_title(); ?></h1>
<?php } ?>
<?php the_content(); ?>
<?php get_footer(); ?>
<script>
    jQuery(function(jQuery){
        var current_page = 1;
        button.click(function(){
            var text = jQuery(this).text();
            jQuery(this).find('a').text('Завантаження.....'); // изменяем текст кнопки, вы также можете добавить прелоадер
            var data = {
                'action': 'loadmore',
                'current_page': current_page.toString()
            };
            jQuery.ajax({
                url:ajaxurl, // обработчик
                data:data, // данные
                type:'POST', // тип запроса
                success:function(data){
                    if( data ) {
                        current_page++;
                        button.find('a').text(text); // возвращаем прежний текст кнопке
                        main_tag.append(data); // вставляем новые посты
                    } else {
                        button.remove(); // если мы дошли до последней страницы постов, скроем кнопку
                    }
                }
            });
        });
    });
</script>
