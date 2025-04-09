<?php
/**
 * bogika functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package bogika
 */

if ( ! defined( '_S_VERSION' ) ) {
    // Replace the version number of the theme on each release.
    define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function bogika_setup() {
    /*
        * Make theme available for translation.
        * Translations can be filed in the /languages/ directory.
        * If you're building a theme based on bogika, use a find and replace
        * to change 'bogika' to the name of your theme in all the template files.
        */
    load_theme_textdomain( 'bogika', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    /*
        * Let WordPress manage the document title.
        * By adding theme support, we declare that this theme does not use a
        * hard-coded <title> tag in the document head, and expect WordPress to
        * provide it for us.
        */
    add_theme_support( 'title-tag' );

    /*
        * Enable support for Post Thumbnails on posts and pages.
        *
        * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
        */
    add_theme_support( 'post-thumbnails' );

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(
        array(
            'menu-1' => esc_html__( 'Primary', 'bogika' ),
        )
    );

    /*
        * Switch default core markup for search form, comment form, and comments
        * to output valid HTML5.
        */
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );

    // Set up the WordPress core custom background feature.
    add_theme_support(
        'custom-background',
        apply_filters(
            'bogika_custom_background_args',
            array(
                'default-color' => 'ffffff',
                'default-image' => '',
            )
        )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        )
    );
}

add_action( 'after_setup_theme', 'bogika_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function bogika_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'bogika_content_width', 640 );
}

add_action( 'after_setup_theme', 'bogika_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function bogika_widgets_init() {
    register_sidebar(
        array(
            'name'          => esc_html__( 'Sidebar', 'bogika' ),
            'id'            => 'sidebar-1',
            'description'   => esc_html__( 'Add widgets here.', 'bogika' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
}

add_action( 'widgets_init', 'bogika_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function bogika_scripts() {
    $path     = get_template_directory_uri();
    $path_css = $path . '/assets/css/';
    $path_js  = $path . '/assets/js/';
    $styles   = [
        'main.min.css',
        'main.css'
    ];
    $scripts  = [
        'jquery-ui.js'             => [ 'dependence' => [ 'jquery' ] ],
        'jquery.ui.touch-punch.js' => [ 'dependence' => [ 'jquery-ui' ] ],
        'slick.js'                 => [ 'dependence' => [ 'jquery' ] ],
        'slicky-kit.js'            => [ 'dependence' => [ 'slick' ] ],
        'select2.js'               => [ 'dependence' => [ 'jquery' ] ],
        'imagelightbox.min.js'     => [ 'dependence' => [ 'jquery' ] ],
        'jquery.scrollbar.js'      => [ 'dependence' => [ 'jquery' ] ],
        'jquery.fancybox.min.js'   => [ 'dependence' => [ 'jquery' ] ],
        'scripts.min.js'           => [
            'dependence' => [
                'jquery',
                'slicky-kit',
                'jquery-touch-punch',
                'select2',
                'imagelightbox-min',
                'jquery-scrollbar'
            ]
        ],
        'backend.js'               => [ 'dependence' => [ 'scripts-min' ] ],
    ];
    if ( is_product() || page_has_block( 'reviewsform', get_queried_object_id() ) ) {
        wp_enqueue_script( 'jquery-validate', get_template_directory_uri() . '/assets/js/jquery.validate.min.js', [ 'jquery' ] );
    }
    if ( is_checkout() ) {
        wp_enqueue_script( 'imask', get_template_directory_uri() . '/assets/js/imask.js' );


        wp_enqueue_script( 'bogika-intlTelInput', get_stylesheet_directory_uri() . '/assets/libs/phone-mask/intlTelInput.min.js' );
        wp_enqueue_script( 'bogika-intlTelInput-utils', get_stylesheet_directory_uri() . '/assets/libs/phone-mask/utils.js' );
        wp_enqueue_script( 'bogika-mask', get_stylesheet_directory_uri() . '/assets/libs/phone-mask/mask.js' );
        wp_enqueue_script( 'bogika-mask_list', get_stylesheet_directory_uri() . '/assets/libs/phone-mask/mask_list.js' );

        wp_enqueue_style( 'bogika-intlTelInput', get_stylesheet_directory_uri() . '/assets/libs/phone-mask/intlTelInput.min.css' );
    }
    foreach ( $styles as $style ) {
        wp_enqueue_style( str_replace( [ '.css', '.' ], [ '', '-' ], $style ), $path_css . $style, array(), time() );
    }
    foreach ( $scripts as $script => $args ) {
        wp_enqueue_script( str_replace( [ '.js', '.' ], [
            '',
            '-'
        ], $script ), $path_js . $script, $args['dependence'], time(), ( isset( $args['footer'] ) ? $args['footer'] : true ) );
    }

    wp_localize_script( 'backend', 'backendvars', array(
        'i18n_required_rating_text' => esc_attr__( 'Please select a rating', 'woocommerce' ),
        'review_rating_required'    => wc_review_ratings_required() ? 'yes' : 'no',
        'required_error'            => __( 'The field is required', 'bogika' ),
        'email_error'               => __( 'The field is not a valid email address', 'bogika' ),
        'min_length_error'          => __( 'The minimum length of the field is 5 symbols', 'bogika' ),
        'sortingtext'               => __( 'Sorting', 'bogika' ),
    ) );

    wp_deregister_style( 'woocommerce-general' );
    wp_deregister_style( 'woocommerce-layout' );
}

add_action( 'wp_enqueue_scripts', 'bogika_scripts' );

add_action( 'admin_enqueue_scripts', function () {
    wp_enqueue_script( 'bogika-admin', get_stylesheet_directory_uri() . '/assets/js/admin.js' );
    wp_enqueue_style( 'bogika-admin', get_stylesheet_directory_uri() . '/assets/css/admin.css' );
} );

if ( function_exists( 'acf_add_options_page' ) ) {
    acf_add_options_page();
}

function p_to_li( $content ) {
    return str_replace( [ '<p>', '</p>' ], [ '<li>', '</li>' ], $content );
}

function loadmore() {
    $args = unserialize( stripslashes( $_POST['query'] ) );
    echo load_template_part( 'template-parts/content', 'blog-posts', [ 'count' => 3 ] );
    die();
}

add_action( 'wp_ajax_loadmore', 'loadmore' );
add_action( 'wp_ajax_nopriv_loadmore', 'loadmore' );

function load_template_part( $template_name, $part_name = null, $args = [] ) {
    ob_start();
    get_template_part( $template_name, $part_name, $args );
    $var = ob_get_contents();
    ob_end_clean();

    return $var;
}

if ( class_exists( 'WooCommerce' ) ) {
    require_once( get_template_directory() . '/woo-c.php' );
}


register_sidebar(
    [
        'id'            => 'filter',
        'name'          => 'Фільтр товарів',
        'before_title'  => '<p class="expanded-opener">',
        'after_title'   => '</p>',
        'before_widget' => '<div class="filter-options-item">',
        'after_widget'  => '</div>',
    ]
);


register_sidebar(
    [
        'id'            => 'product_reviews',
        'name'          => 'Відгуки на товар',
        'before_title'  => '',
        'after_title'   => '',
        'before_widget' => '',
        'after_widget'  => '',
    ]
);

require_once "inc/shop-functions.php";

add_filter( 'pre_get_posts', function ( $query ) {
    if ( $query->is_search && $query->is_main_query() && ! $query->is_admin ) {
        $query->set( 'post_type', [ 'product' ] );
        $query->set( 'posts_per_page', 12 );
    }

    return $query;
} );

function enableHolder() {
    if ( ! is_page( 'blog' ) && ! is_singular( 'post' ) && ! is_front_page() && ! is_page( 'about' ) && ! is_product() ) {
        return true;
    }

    return false;
}

//Save the rating submitted by the user.
add_action( 'comment_post', 'cfsr_save_comment_rating' );
function cfsr_save_comment_rating( $comment_id ) {
    if ( ( isset( $_POST['rating'] ) ) && ( '' !== $_POST['rating'] ) ) {
        $rating = intval( $_POST['rating'] );
    }
    add_comment_meta( $comment_id, 'rating', intval( $_POST['rating'] ), true ); // WPCS: input var ok, CSRF
}

add_action( 'comment_post', 'save_comment_extra_fields' );

function save_comment_extra_fields( $comment_id ) {
    if ( isset( $_POST['rating'] ) ) {
        $rating = sanitize_text_field( $_POST['rating'] );
        add_comment_meta( $comment_id, 'rating', $rating );
    }
}

function page_has_block( $block_name, $post = null ) {
    if ( ! is_string( $post ) ) {
        $wp_post = get_post( $post );
        if ( $wp_post instanceof WP_Post ) {
            $post = $wp_post->post_content;
        }
    }
    /*
     * Normalize block name to include namespace, if provided as non-namespaced.
     * This matches behavior for WordPress 5.0.0 - 5.3.0 in matching blocks by
     * their serialized names.
     */
    if ( false === strpos( $block_name, '/' ) ) {
        $block_name = 'acf/' . $block_name;
    }
    // Test for existence of block by its fully qualified name.
    $has_block = false !== strpos( $post, '<!-- wp:' . $block_name . ' ' );
    if ( ! $has_block ) {
        /*
         * If the given block name would serialize to a different name, test for
         * existence by the serialized form.
         */
        $serialized_block_name = strip_core_block_namespace( $block_name );
        if ( $serialized_block_name !== $block_name ) {
            $has_block = false !== strpos( $post, '<!-- wp:' . $serialized_block_name . ' ' );
        }
    }

    return $has_block;
}

function bogika_current_language() {
    $lang = apply_filters( 'wpml_current_language', null );

    return ( $lang );
}

add_filter( 'woocommerce_get_template', 'my_custom_login_template', 10, 5 );
function my_custom_login_template( $located, $template_name, $args, $template_path, $default_path ) {
    if ( $template_name === 'myaccount/form-login.php' || $template_name === 'myaccount/form-register.php' ) {
        $located = get_stylesheet_directory() . '/my-account/' . $template_name;
    }

    return $located;
}

function my_filter_plugin_updates( $value ) {
    if ( isset( $value->response['wc-ukr-shipping-pro/wc-ukr-shipping-pro.php'] ) ) {
        unset( $value->response['wc-ukr-shipping-pro/wc-ukr-shipping-pro.php'] );
    }

    return $value;
}

add_filter( 'site_transient_update_plugins', 'my_filter_plugin_updates' );

add_filter( 'body_class', function ( $classes ) {
    if ( $classes[0] === 'privacy-policy' ) {
        $classes[0] = 'privacy-policy-page';
    }
    switch ( get_queried_object_id() ) {
        case apply_filters( 'wpml_object_id', 90, 'page' ) :
            $classes[] = 'loyalty-program-page';
    }

    return $classes;
} );

/* Ввімкнено 22.09.2023 на прохання клієнта
	відповідає за Email на день народження
 */
require 'inc/components/emails/BirthdayPromo.php';
function get_sum_of_prices( $category_or_products ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'postmeta';

    if ( is_array( $category_or_products ) ) {
        $product_ids = implode( ',', $category_or_products );

        $query = "
      SELECT SUM(meta_value) as total_price
      FROM {$table_name}
      WHERE meta_key='_price' AND post_id IN ({$product_ids})
    ";
    } else {
        $query = "
      SELECT SUM(pm.meta_value) as total_price
      FROM {$table_name} pm
      LEFT JOIN {$wpdb->prefix}term_relationships tr ON pm.post_id = tr.object_id
      LEFT JOIN {$wpdb->prefix}term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
      LEFT JOIN {$wpdb->prefix}terms t ON t.term_id = tt.term_id
      WHERE pm.meta_key='_price' AND tt.taxonomy='product_cat' AND t.term_id={$category_or_products}
    ";
    }

    $total_price = $wpdb->get_var( $query );

    return floatval( $total_price );
}

//function bogika_query_optimizer( $query ) {
//	if ( is_admin() || ! $query->is_main_query() ){
//		return;
//	}
//
//	$query->set( 'update_post_meta_cache', false );
//	$query->set( 'update_post_term_cache', false );
//}
//add_action( 'pre_get_posts', 'bogika_query_optimizer', 1 );
function pr($arr, $field = '', $pre = true)	{
    if($pre) echo "<pre>";
    if(isset($field)&&empty($field))	print_r($arr);
    else
        foreach($arr as $item) {
            if(gettype($field) == 'string')	$arrfields = array($field); else $arrfields = $field;
            $row = array();
            if(gettype($item)=='object') foreach($arrfields as $f) $row[] = "<b>".$f."</b> => ".$item->$f;
            else foreach($arrfields as $f) $row[] = "<b>".$f."</b> => ".$item[$f];
            echo implode(" | ",$row)."<br>";
        }
    if($pre) echo "</pre>";
}

function get_16_random($session = false)    {
    $str = '0123456789ABCDEF';
    $arr = str_split($str);
    $r_str = '';
    for($i=1;$i<=32;$i++) {
        $r_str .= $arr[rand(0,15)];
        if($i==8) $r_str .= '-';
        if($i==12||$i==16||$i==20) $r_str .= '-';
    }
    return $r_str;
}

add_action("wp_ajax_get_cart_data", "get_cart_data");// для фронтенда
add_action("wp_ajax_nopriv_get_cart_data", "get_cart_data");// для админки
function get_cart_data(){ // функция которая вызывается
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();
    $arr = ['data'=>[],'guid'=>''];
    foreach ($items as $values) {
        $_product = wc_get_product($values['data']->get_id());
        $item = [   "productKey" => $_product->get_id().'',
            "price" => $_product->get_regular_price(),
            "quantity" => $values['quantity'].'',
            "currency" => get_woocommerce_currency() ];
        $arr['data'][] = $item;
    }
    $arr['guid'] = get_16_random();
    wp_send_json_success($arr);
    wp_die();
}


function sitemap_exclude_url( $url, $type, $post) {
    if(strpos($url['loc'],'register') !== false) return false;
    return $url;
}
add_filter( 'wpseo_sitemap_entry', 'sitemap_exclude_url', 1, 3 );


function generate_itemlist_schema()
{
    if (!is_product_category()) {
        return;
    }

    global $wp_query;
    $category = $wp_query->get_queried_object();

    $category_name = $category->name;
    $category_description = $category->description;

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'product_cat' => $category->slug,
    );

    $products = new WP_Query($args);
    $itemListElements = array();

    if ($products->have_posts()) {
        $position = 1;
        while ($products->have_posts()) {
            $products->the_post();
            $product_id = get_the_ID();
            $product = wc_get_product($product_id);
            $product_url = get_permalink();
            $product_image = wp_get_attachment_url($product->get_image_id());

            $itemListElements[] = array(
                "@type" => "ListItem",
                "position" => $position,
                "url" => $product_url,
            );

            $position++;
        }
        wp_reset_postdata();
    }

    $schema = array(
        "@context" => "http://schema.org",
        "@type" => "ItemList",
        "name" => $category_name,
        "description" => $category_description,
        "itemListElement" => $itemListElements,
    );

    echo '<script type="application/ld+json" >' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

add_action('wp_head', 'generate_itemlist_schema');

function add_product_schema() {
    if (is_product()) {
        global $product;

        $comments = get_comments(array('post_id' => $product->get_id()));
        if(!get_the_post_thumbnail_url($product->get_id()) && $product->get_type() == 'variable') {
            $variations = $product->get_available_variations();
            $prod_image = $variations[0]['image']['url'];
        }
        else $prod_image = wp_get_attachment_url($product->get_image_id());
        ?>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org/",
                "@type": "Product",
                "name": "<?php echo esc_html($product->get_name()); ?>",
            "description": "<?php echo esc_html($product->get_short_description()); ?>",
            "image": [
                "<?php echo esc_url($prod_image); ?>"
            ],
            "sku": "<?php echo esc_html($product->get_sku()); ?>",
            "brand": {
                "@type": "Thing",
                "name": "Bogika"
            },
            "offers": {
                "@type": "Offer",
                "url": "<?php echo esc_url(get_permalink($product->get_id())); ?>",
                "priceCurrency": "UAH",
                "price": "<?php echo esc_html($product->get_price()); ?>",
                "itemCondition": "https://schema.org/NewCondition",
                "availability": "https://schema.org/InStock"
            },
            "reviews": [
            <?php foreach ($comments as $key => $comment) : ?>
                {
                    "@type": "Review",
                    "datePublished": "<?php echo esc_html(str_replace('-', '/', substr($comment->comment_date, 0, 10))); ?>",
                    "name": "<?php echo esc_html($comment->comment_author); ?>",
                    "reviewBody": "<?php echo esc_html($comment->comment_content); ?>",
                    "reviewRating": {
                        "@type": "Rating",
                        "ratingValue": <?php echo esc_html(get_comment_meta($comment->comment_ID, 'rating', true))?esc_html(get_comment_meta($comment->comment_ID, 'rating', true)):5; ?>,
                        "bestRating": 5,
                        "worstRating": 1
                    },
                    "author": {
                        "@type": "Person",
                        "name": "ClientPseudo"
                    }
                }
        <?php if (($key + 1) < count($comments)) echo ','; ?>
            <?php endforeach; ?>
            ]
            <?php if(!empty($product->get_review_count())) { ?>
            ,
            "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "<?php echo esc_html($product->get_average_rating()); ?>",
                "reviewCount": "<?php echo esc_html($product->get_review_count()); ?>",
                "bestRating": 5,
                "worstRating": 1
            }
            <?php   } ?>
            }
        </script>
        <?php
    }
}
add_action('wp_head', 'add_product_schema');

////// 16.01.2025 вимкнув виведення структурованих даних де тільки міг 
/////+ довелося напряму закоментувати метод в плагіні WC класс WC_Structured_Data //add_action( 'wp_footer', array( $this, 'output_structured_data' ), 10 )
//// Обробник і генератор структурованих даних у форматі JSON-LD
add_action( 'init', 'my_remove_json_ld_frontend' );
function my_remove_json_ld_frontend() {
    remove_action( 'wp_footer', array( WC()->structured_data, 'output_structured_data' ), 10 );
}
add_filter( 'awsm_job_structured_data', '__return_false');
add_filter( 'wpjm_output_job_listing_structured_data', '__return_false');
add_filter( 'appthemes_schema_output', '__return_false');
add_filter( 'easy_testimonials_json_ld', '__return_false');
add_filter( 'wp_postratings_schema_itemtype', '__return_false');
add_filter( 'wp_postratings_google_structured_data', '__return_false');
add_filter( 'wpseo_json_ld_output', '__return_false');
add_filter( 'web_stories_enable_schemaorg_metadata', '__return_false');
add_filter( 'sq_json_ld', '__return_false',99);
add_filter( 'wds-schema-data', '__return_false');
////////////////////////////////////


//add_filter('woocommerce_get_breadcrumb', 'custom_remove_breadcrumb', 20);
//
//function custom_remove_breadcrumb($breadcrumb) {
//    // Повертаємо порожній масив, що призведе до відсутності breadcrumb
//    return array();
//}

function get_monthes()
{
    return ["Січня", "Лютого", "Березня", "Квітня", "Травня", "Червня",
        "Липня", "Серпня", "Вересня", "Жовтня", "Листопада", "Грудня"];
}


function get_term_meta_repeater($catID, $repeater = 'list_items') {
    $arr = [];
    if(isset(get_term_meta($catID, 'list_items')[0]))
        for($i=0; $i<(integer)get_term_meta($catID, 'list_items')[0]; $i++)
            $arr[$i] = ['question' => get_term_meta($catID, 'list_items_'.$i.'_question')[0], 'answer' => get_term_meta($catID, 'list_items_'.$i.'_answer')[0]];
    return $arr;
}

///
//remove_action('manage_shop_order_posts_custom_column', array((new MRKV_UA_SHIPPING_WOO_ORDERS), 'mrkv_ua_ship_woo_custom_column') );
//remove_action( 'wp_footer', array( WC()->structured_data, 'output_structured_data' ), 10 );



function mi($arr){
    echo "<pre>" . print_r($arr, true) . "</pre>";
}







add_action("wp_ajax_get_mini_cart_data", "get_mini_cart_data");// для фронтенда
add_action("wp_ajax_nopriv_get_mini_cart_data", "get_mini_cart_data");// для админки
function get_mini_cart_data() { // функция которая вызывается ?>
    <div class="basket-services-box__wrapper">
		<div class="basket-services-box-top">
            <h3><?php esc_html_e( 'Basket', 'bogika' ); ?></h3>

            <span class="cart-close"></span>
        </div>
    <?php woocommerce_mini_cart(); ?>
    </div>
    <?php die();
}

add_action("wp_ajax_get_mini_cart_num_data", "get_mini_cart_num_data");// для фронтенда
add_action("wp_ajax_nopriv_get_mini_cart_num_data", "get_mini_cart_num_data");// для админки
function get_mini_cart_num_data() { // функция которая вызывается ?>
    <?php echo WC()->cart->get_cart_contents_count();
    die();
}

function ajax_my_cart_qty() {

    // Set item key as the hash found in input.qty's name
    $cart_item_key = $_POST['hash'];

    // Get the array of values owned by the product we're updating
    $threeball_product_values = WC()->cart->get_cart_item( $cart_item_key );

    // Get the quantity of the item in the cart
    $threeball_product_quantity = apply_filters( 'woocommerce_stock_amount_cart_item', apply_filters( 'woocommerce_stock_amount', preg_replace( "/[^0-9\.]/", '', filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT)) ), $cart_item_key );

    // Update cart validation
    $passed_validation  = apply_filters( 'woocommerce_update_cart_validation', true, $cart_item_key, $threeball_product_values, $threeball_product_quantity );

    // Update the quantity of the item in the cart
    if ( $passed_validation ) {
        WC()->cart->set_quantity( $cart_item_key, $threeball_product_quantity, true );
    }

    // Refresh the page
    woocommerce_mini_cart();

    die();

}

add_action('wp_ajax_my_cart_qty', 'ajax_my_cart_qty');
add_action('wp_ajax_nopriv_my_cart_qty', 'ajax_my_cart_qty');



function my_cart_num_qty() {

    echo WC()->cart->get_cart_contents_count();
    die();

}

add_action('wp_ajax_my_cart_num_qty', 'my_cart_num_qty');
add_action('wp_ajax_nopriv_my_cart_num_qty', 'my_cart_num_qty');