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
<html <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#">
<head>
    
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="apple-touch-icon" sizes="192x192"
	      href="<?= get_template_directory_uri() ?>/assets/img/favicon/android-chrome-192x192.png">
	<link rel="icon" href="<?= get_template_directory_uri() ?>/assets/img/favicon/favicon.png">
    <link rel="preload" href="<?= get_template_directory_uri() ?>/assets/css/main.css" as="style">
    <link rel="preload" href="<?= get_template_directory_uri() ?>/assets/js/scripts.min.js" as="script">
    <meta name="theme-color" content="#000">
	<link rel="profile" href="https://gmpg.org/xfn/11">
    <meta property="og:title" content="Тег Title" />
    <meta property="og:description" content="Мета-тег Description" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="URL поточної сторінки" />
    <meta property="og:image" content="URL зображення" />
    <meta content="__" itemprop="offerCount">
    <meta content="__" itemprop="lowPrice">
    <meta content="__" itemprop="highPrice">
    <meta content="" itemprop="priceCurrency">
    <?php wp_head();
    session_start();
    global $post;
	?>
	<?php if ($_SERVER['HTTP_HOST'] === 'bogika.com.ua') : ?>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-NLQ8RGJ');</script>
		<!-- End Google Tag Manager -->
	<?php endif; ?>
    <script>
        document.oncopy = function () {
            let bodyElement = document.body;
            let selection = getSelection();
            let href = document.location.href;
            let copyright = "<br><br>Source: <a href='"+ href +"'>" + href + "</a><br>© Bogika";
            let text = selection + copyright;
            let divElement = document.createElement('div');
            divElement.style.position = 'absolute';
            divElement.style.left = '-99999px';
            divElement.innerHTML = text;
            bodyElement.appendChild(divElement);
            selection.selectAllChildren(divElement);
            setTimeout(function() {
                bodyElement.removeChild(divElement);
            }, 0);
        };
    </script>
    <script type="application/ld+json" >
        {
            "@context": "https://schema.org/",
            "@type": "WebSite",
            "name": "BOGIKA УКРАЇНСЬКА НАТУРАЛЬНА КОСМЕТИКА",
            "url": "https://bogika.com.ua/",
            "potentialAction": {
                "@type": "SearchAction",
                "target": "https://bogika.com.ua/?s={search_term_string}",
                "query-input": "required name=search_term_string"
            }
        }
    </script>
    <?php if(is_front_page()) { ?>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Organization",
                "name": "BOGIKA Інтернет-магазин української натуральної косметики",
                "alternateName": "BOGIKA УКРАЇНСЬКА НАТУРАЛЬНА КОСМЕТИКА",
                "url": "https://bogika.com.ua/",
                "logo": "https://bogika.com.ua/wp-content/webp-express/webp-images/uploads/2023/04/logo-21-2-1.png.webp",
                "contactPoint": {
                    "@type": "ContactPoint",
                    "telephone": "+380 (96) 964-68-46",
                    "contactType": "customer service",
                    "availableLanguage": "Ukrainian"
                },
                "sameAs": [
                    "https://www.facebook.com/bogikashop",
                    "https://instagram.com/bogika.cosmetics",
                    "https://www.tiktok.com/@bogika.cosmetics?_t=8c0jEUu6lFN&_r=1"
                ]
            }
        </script>
    <?php } ?>
    <?php if(is_page('contacts')) { ?>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "LocalBusiness",
                "name": "BOGIKA",
                "image": "https://bogika.com.ua/wp-content/uploads/2023/03/logo-footer.svg",
                "@id": "",
                "url": "https://bogika.com.ua/",
                "telephone": "+380 (96) 964-68-46",
                "priceRange": "$$",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "Київська 53",
                    "addressLocality": "Обухів",
                    "postalCode": "08700",
                    "addressCountry": "UA"
                },
                "geo": {
                    "@type": "GeoCoordinates",
                    "latitude": 50.1193883,
                    "longitude": 30.6425209
                },
                "openingHoursSpecification": {
                    "@type": "OpeningHoursSpecification",
                    "dayOfWeek": [
                        "Monday",
                        "Tuesday",
                        "Wednesday",
                        "Thursday",
                        "Friday",
                        "Saturday",
                        "Sunday"
                    ],
                    "opens": "00:00",
                    "closes": "23:59"
                },
                "sameAs": [
                    "https://www.facebook.com/bogikashop",
                    "https://www.instagram.com/bogika.cosmetics"
                ]
            }
        </script>
    <?php } ?>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=TAG_ID"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        //gtag('config', 'G-FSR1MTHFSX');
    </script>

</head>

<body <?php body_class(); ?>>
<?php if ($_SERVER['HTTP_HOST'] === 'bogika.com.ua') : ?>
<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NLQ8RGJ"
	                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
<?php endif; ?>
<!-- Custom HTML -->
<div id="wrapper">
	<header id="header">
        <div class="top-bar" style="position: relative">
            <?php if (get_field('pokazaty_tajmer', 'option')) : ?>
            <a class="hero-hover" href="<?= get_field( 'posylannya_dlya_promo', 'option' ) ?>" style="display:block; text-decoration: none; position: absolute; left: 0; top: 0; z-index: 9; width: 100%; height: 93px;"></a>
            <?php else : ?>
            <a class="hero-hover" href="<?= get_field( 'posylannya_dlya_promo', 'option' ) ?>" style="display:block; text-decoration: none; position: absolute; left: 0; top: 0; z-index: 9; width: 100%; height: 100%;"></a>
            <?php endif;?>
            <div  class="holder" >
                <a href="<?= get_field( 'posylannya_dlya_promo', 'option' ) ?>" style="display:block; text-decoration: none"><?= get_field( 'info_text', 'option' ) ?></a>
                <?php if (get_field('pokazaty_tajmer', 'option')) : ?>
                    <div id="timer">
                        <span class="timer-section" id="days" style="color:<?= the_field('kolir_tekstu_tajmera', 'option')?>; background-color: <?= the_field('kolir_fonu_tajmera', 'option')?>;"></span>
                        <span class="timer-section" id="hours" style="color: <?= the_field('kolir_tekstu_tajmera', 'option')?>; background-color: <?= the_field('kolir_fonu_tajmera', 'option')?>;"></span>
                        <span class="timer-section" id="minutes" style="color: <?= the_field('kolir_tekstu_tajmera', 'option')?>; background-color: <?= the_field('kolir_fonu_tajmera', 'option')?>;"></span>
                        <span class="timer-section" id="seconds" style="color: <?= the_field('kolir_tekstu_tajmera', 'option')?>; background-color: <?= the_field('kolir_fonu_tajmera', 'option')?>;"></span>
                    </div>
                    <div class="close-block">x</div>
                    <script>
                        // Отримати дату з ACF поля із сервера або іншим чином
                        var acfDate = '<?php echo get_field("data_dlya_tajmeru", "option"); ?>';
                        // Розбиваємо рядок на складники дати (день, місяць, рік)
                        var acfDateParts = acfDate.split('/');
                        // Перетворюємо складники дати на об'єкт Date (місяць - 1, бо в JavaScript місяці індексуються з 0)
                        var targetDate = new Date(acfDateParts[2], acfDateParts[1] - 1, acfDateParts[0]);

                        function updateTimer() {
                            var currentDate = new Date();
                            var difference = targetDate.getTime() - currentDate.getTime();

                            if (difference <= 0) {
                                // Якщо досягнуто цільову дату, зупинити таймер
                                clearInterval(timerInterval);
                                document.getElementById('timer').innerHTML = 'Час вийшов!';
                            } else {
                                var days = Math.floor(difference / (1000 * 60 * 60 * 24));
                                var hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                var minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                                var seconds = Math.floor((difference % (1000 * 60)) / 1000);

                                document.getElementById('days').innerHTML = days + 'д';
                                document.getElementById('hours').innerHTML = hours + 'г';
                                document.getElementById('minutes').innerHTML = minutes + 'хв';
                                document.getElementById('seconds').innerHTML = seconds + 'с';
                            }
                        }

                        // Оновлення таймера кожну секунду
                        var timerInterval = setInterval(updateTimer, 1000);

                        // Виклик першого оновлення таймера
                        updateTimer();
                    </script>
                <?php endif; ?>
            </div>
        </div>
		<div class="header-main">
			<div class="holder">
				<div class="header-info flexbox">
					<strong class="logo">
                        <a href="<?= home_url(); ?>">
	                        <?= wp_get_attachment_image(get_field('header_logo','option'), 'full') ?>
                        </a>
                    </strong>
					<div class="info flexbox">
						<nav id="nav">
							<a class="mob-btn" href="#"><span><?php esc_html_e( 'menu', 'bogika' ); ?></span></a>
							<div class="nav-open-drop">
								<strong class="logo">
                                    <a href="<?= home_url() ?>">
	                                    <?= wp_get_attachment_image(get_field('header_logo','option'), 'full') ?>
                                    </a>
                                </strong>
								<div class="lang">
									<a href="#" class="lang-opener">UA</a>
								</div>
								<?php echo str_replace( '<ul class="sub-menu', '<i class="arrow-down">arrow-down</i><ul class="sub-menu', wp_nav_menu(
									[
										'menu'       => 'Mainmenu',
										'menu_class' => 'nav-menu flexbox',
										'echo'       => false
									]
								) );
								?>
								<div class="nav-open-drop-bottom">
									<ul class="contacts-info">
										<!--<li class="location"><?= get_field( 'city', 'option' ) ?></li>-->
										<li class="phone"><?php esc_html_e( 'Phone', 'bogika' ); ?>
											<?= get_field( 'phone', 'option' ) ?>
										</li>
										<!--<li class="data">Режим роботи <b><?= get_field( 'opening_hours', 'option' ) ?></b></li>
                                        <li class="email"><a href="mailto:<?= get_field( 'email', 'option' ) ?>"><?= get_field( 'email', 'option' ) ?></a></li> -->
									</ul>
									<ul class="social-networks flexbox">
										<?php if ( get_field( 'facebook', 'option' ) ) { ?>
											<li><a class="icon-facebook" href="<?= get_field( 'facebook', 'option' ) ?>"
											       target="_blank">facebook</a></li>
										<?php } ?>
										<?php if ( get_field( 'youtube', 'option' ) ) { ?>
											<li><a class="icon-youtube" href="<?= get_field( 'youtube', 'option' ) ?>"
											       target="_blank">youtube</a></li>
										<?php } ?>
										<?php if ( get_field( 'instagram', 'option' ) ) { ?>
											<li><a class="icon-instagram"
											       href="<?= get_field( 'instagram', 'option' ) ?>" target="_blank">instagram</a>
											</li>
										<?php } ?>
										<?php if ( get_field( 'tiktok', 'option' ) ) { ?>
											<li><a class="icon-tiktok" href="<?= get_field( 'twitter', 'option' ) ?>"
											       target="_blank">twitter</a></li>
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
							<li class="header-user-info-signin"><a class="signin" href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>">signin</a></li>
							<li class="header-user-info-basket">
								<div class="basket-box">
									<a href="/cart/" class="btn-basket">basket <span
											class="basket-num"> <?php echo WC()->cart->get_cart_contents_count(); ?></span></a>
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
		<?php if ( ! is_front_page() ) { ?>
			<ul class="breadcrumbs">
				<?php
				if ( ! is_singular( 'post' ) ) {
					bcn_display_list();
				} else { ?>
					<li class="home"><span property="itemListElement" typeof="ListItem"><a property="item"
					                                                                       typeof="WebPage"
					                                                                       title="Перейти до BOGIKA."
					                                                                       href="/" class="home"><span
									property="name">Головна</span></a><meta property="position" content="1"></span></li>
					<li class="page"><span property="itemListElement" typeof="ListItem"><a property="item"
					                                                                       typeof="WebPage"
					                                                                       title="Go to the Обличчя Категорія archives."
					                                                                       href="/blog/"
					                                                                       class="page"><span
									property="name">Блог</span></a><meta property="position" content="2"></span></li>
					<li class="post post-post current-item"><span property="itemListElement" typeof="ListItem"><span
								property="name"
								class="post post-post current-item"><?= get_the_title( $post->ID ) ?></span><meta
								property="url" content="<?= get_the_permalink( $post->ID ) ?>"><meta property="position"
					                                                                                 content="3"></span>
					</li>
				<?php }
				?>
			</ul>
		<?php } ?>
		<?php if ( enableHolder() ) {
		$post_name = '';
		if ( ! is_404() ) {
			$post_name = $post->post_name;
		} ?>
		<div id="content" class="content-page <?= $post_name ?>-page <?= $post_name ?>">
			<?php if (!get_field('disable_holder')) : ?>
			<div class="holder">
				<?php endif; ?>
				<?php } ?>
				<!--        --><?php //if(!get_field('hide_title')&&!is_singular('post')) { ?>
				<!--        <h1>--><?php //the_title(); ?><!--</h1>-->
				<!--        --><?php //  } ?>
