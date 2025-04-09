<?php
//Template Name: Test
if(!empty($_GET)){
    //mi($_GET);
}
//mi($_SERVER);
//https://staging.bogika.com.ua/test/?ID=1
function tartata(){
    
    //sql$total_alls = $wpdb->get_results( "SELECT ID,post_date FROM wp_posts WHERE ID = 1 UNION SELECT ID,user_login FROM  wp_users WHERE ID = 1");
    //UNION SELECT ID,user_login FROM  wp_users WHERE ID = 1
    //$total_alls = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts WHERE ID = " .$_GET['ID'] );
    //$total_alls = $wpdb->get_results( "SELECT ID,post_date FROM wp_posts WHERE ID = " . $_GET['ID']);
    // ?ID=2+UNION+SELECT+ID,user_login+FROM+wp_users+WHERE+ID+=+1 для $wpdb->get_results( "SELECT ID,post_date FROM wp_posts WHERE ID = " . $_GET['ID']);
    //echo $_GET['ID'];
    //mi($total_alls);
    //SELECT * FROM `wp_posts` WHERE ID = 2 UNION SELECT * FROM `wp_users` WHERE ID = 3;
    //https://staging.bogika.com.ua/test/?ID=2+UNION+SELECT+ID,user_login+FROM+wp_users+WHERE+ID+=+1
    //https://staging.bogika.com.ua/test/?ID=2+UNION+SELECT+user_pass,user_login+FROM+wp_users+WHERE+ID+=+1
    //https://staging.bogika.com.ua/test/?ID=2+UNION+SELECT+user_pass,user_login+FROM+wp_users+WHERE+ID+=+1+--
    //  --  коментує все наступне в запиті
    //https://staging.bogika.com.ua/test/?ID=2+UNION+SELECT+user_pass,user_login+FROM+wp_users+WHERE+ID+=+1+OR+1=1  OR 1=1 робить значення true і виводить все що є)))

    //$ID = (int)$_GET['ID'];// предотвращає інєкцію бо
    //$ID = esc_sql($_GET['ID']);//не предотвращєт))))))

    /*
    $ID = $_GET['ID'];//не предотвращє
    global $wpdb;
    $total_alls = $wpdb->get_results( "SELECT ID,post_date FROM wp_posts WHERE ID = " . $ID );
    echo $_GET['ID'];
    echo '</br>';
    echo $ID;
    mi($total_alls);
    */
    /*

    $ID = $_GET['search'];

    echo $ID;
    echo '</br>';
    $arr_products = array(
        'post_type' => array('product'),
        'post_status' => 'publish',
        'posts_per_page' => 5,
        'title' => $ID//18682
    );
    $products = new WP_Query($arr_products);

    mi($products->request);
    mi($products->posts);
    */

}
tartata();