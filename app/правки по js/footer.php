<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package bogika
 */

?>
<?php if(!is_page('blog')&&!is_singular('post')&&!is_front_page()&&!is_page('about')) { ?>
        </div>
    </div>
<?php } ?>
</main>
<footer id="footer">
    <div class="holder">
        <div class="footer-sections flexbox">
            <section>
                <strong class="logo"><a href="/"><img src="<?=get_field('footer_logo','option')['url']?>" alt="image description"></a></strong>
                <ul class="social-networks flexbox">
                    <li><a class="icon-facebook" href="<?=get_field('facebook','option')?>" target="_blank">facebook</a></li>
                    <li><a class="icon-youtube" href="<?=get_field('youtube','option')?>" target="_blank">youtube</a></li>
                    <li><a class="icon-instagram" href="<?=get_field('instagram','option')?>" target="_blank">instagram</a></li>
                    <li><a class="icon-tiktok" href="<?=get_field('tiktok','option')?>" target="_blank">twitter</a></li>
                </ul>
            </section>
            <section>
                <?php wp_nav_menu(
                    [
                        'menu'=>'Footer1',
                    ]
                );
                ?>
            </section>
            <section>
                <?php wp_nav_menu(
                    [
                        'menu'=>'Footer2',
                    ]
                );
                ?>
            </section>
            <section class="contacts-info-section">
                <ul class="contacts-info">
                    <li class="location"><?=get_field('city','option')?></li>
                    <li class="phone">Телефон
                        <?=get_field('phone','option')?>
                    </li>
                    <li class="data">Режим роботи <b><?=get_field('opening_hours','option')?></b></li>
                    <li class="email"><a href="mailto:<?=get_field('email','option')?>"><?=get_field('email','option')?></a></li>
                </ul>
            </section>
        </div>
    </div>
</footer>
<div id="overlay"></div>
<?php wp_footer(); ?>
</body>
</html>
