<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package bogika
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta property="og:image" content="path/to/image.jpg">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=get_template_directory_uri()?>/assets/img/favicon/apple-touch-icon-180x180.png">
    <link rel="icon" href="<?=get_template_directory_uri()?>/assets/img/favicon.ico">
    <meta name="theme-color" content="#000">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head();
	    global $post;
	?>
</head>

<body <?php body_class(); ?>>
<!-- Custom HTML -->
<div id="wrapper">
    <header id="header">
        <div class="top-bar">
            <div class="holder">
                <?=get_field('info_text','option')?>
                <div class="close-block">x</div>
            </div>
        </div>
        <div class="header-main">
            <div class="holder">
                <div class="header-info flexbox">
                    <strong class="logo"><a href="/"><img src="<?=get_field('header_logo','option')['url']?>" alt="image description"></a></strong>
                    <div class="info flexbox">
                        <nav id="nav">
                            <a class="mob-btn" href="#"><span>меню</span></a>
                            <div class="nav-open-drop">
                                <strong class="logo"><a href="/"><img src="<?=get_field('header_logo','option')['url']?>" alt="image description"></a></strong>
                                <div class="lang">
                                    <a href="#" class="lang-opener">UA</a>
                                </div>
                                <?php echo str_replace('<ul class="sub-menu','<i class="arrow-down">arrow-down</i><ul class="sub-menu',wp_nav_menu(
                                        [
                                        'menu'=>'Mainmenu',
                                        'menu_class' => 'nav-menu flexbox',
                                        'echo' => false
                                        ]
                                        ));
                                ?>
                                <div class="nav-open-drop-bottom">
                                    <ul class="contacts-info">
                                        <!--<li class="location"><?=get_field('city','option')?></li>-->
                                        <li class="phone">Телефон
                                            <?=get_field('phone','option')?>
                                        </li>
                                        <!--<li class="data">Режим роботи <b><?=get_field('opening_hours','option')?></b></li>
                                        <li class="email"><a href="mailto:<?=get_field('email','option')?>"><?=get_field('email','option')?></a></li> -->
                                    </ul>
                                    <ul class="social-networks flexbox">
                                        <?php if(get_field('facebook','option')) { ?>
                                            <li><a class="icon-facebook" href="<?=get_field('facebook','option')?>" target="_blank">facebook</a></li>
                                        <?php } ?>
                                        <?php if(get_field('youtube','option')) { ?>
                                            <li><a class="icon-youtube" href="<?=get_field('youtube','option')?>" target="_blank">youtube</a></li>
                                        <?php } ?>
                                        <?php if(get_field('instagram','option')) { ?>
                                            <li><a class="icon-instagram" href="<?=get_field('instagram','option')?>" target="_blank">instagram</a></li>
                                        <?php } ?>
                                        <?php if(get_field('tiktok','option')) { ?>
                                            <li><a class="icon-tiktok" href="<?=get_field('twitter','option')?>" target="_blank">twitter</a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-search">
                                <a class="form-search-btn" href="#">search</a>
                                <div class="search">
                                    <?php get_search_form(); ?>
                                </div>
                            </div>
                        </nav>
                        <ul class="header-user-info flexbox">
                            <li class="header-user-info-search">
                                <div class="form-search">
                                    <a class="form-search-btn" href="#">search</a>
                                    <div class="search">
                                        <?php get_search_form(); ?>
                                    </div>
                                </div>
                            </li>
                            <li class="header-user-info-signin"><a class="signin" href="#">signin</a></li>
                            <li class="header-user-info-basket">
                                <div class="basket-box">
                                    <a href="/cart/" class="btn-basket">basket <span class="basket-num"> <?php echo WC()->cart->get_cart_contents_count();?></span></a>
                                    <div class="basket-services-box">
                                        <h3>кошик</h3>
                                        <?php woocommerce_mini_cart(); ?>
                                    </div>
                                </div>
                            </li>
                            <li class="header-user-info-lang">
                                <div class="lang">
                                    <a href="#" class="lang-opener">UA</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main id="main" role="main">
        <?php if(!is_front_page()) { ?>
        <ul class="breadcrumbs">
            <?php
            if(!is_singular('post')) bcn_display_list();
            else { ?>
                <li class="home"><span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="Перейти до BOGIKA." href="/" class="home"><span property="name">Головна</span></a><meta property="position" content="1"></span></li>
                <li class="page"><span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="Go to the Обличчя Категорія archives." href="/blog/" class="page"><span property="name">Блог</span></a><meta property="position" content="2"></span></li>
                <li class="post post-post current-item"><span property="itemListElement" typeof="ListItem"><span property="name" class="post post-post current-item"><?=get_the_title($post->ID)?></span><meta property="url" content="<?=get_the_permalink($post->ID)?>"><meta property="position" content="3"></span></li>
            <?php }
            ?>
        </ul>
        <?php } ?>
        <?php
        if(!is_page('blog')&&!is_singular('post')&&!is_front_page()&&!is_page('about')) {
        if(!is_404()) $post_name = $post->post_name; ?>
        <div id="content" class="content-page <?=$post_name?>-page <?=$post_name?>">
            <div class="holder">
        <?php } ?>
        <?php if(!get_field('hide_title')&&!is_singular('post')) { ?>
        <h1><?php the_title(); ?></h1>
        <?php   } ?>

