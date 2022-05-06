<?php

use TrustPilot\business;

function trpi_single_commnet_sidebar_shortcode(){
    ob_start();
  
    global $wp_query;
    $query_vars = $wp_query->get_queried_object();

    if($query_vars->post_name !== "reviews"){
      return;
    }

    $review_id = str_replace("/reviews/" , "" ,  $_SERVER['REQUEST_URI']);
    $review_id = (int)str_replace("/" , "" ,  $review_id);

    if($review_id == 0){
      return;
    }

    $business_id = business::get_business_by_review_id($review_id);
    $title = get_the_title($business_id);
    ?>

    <div class="trpi_business_sumary_details">
        <div class="image">
            <?php echo get_the_post_thumbnail($business_id); ?>
        </div>
        <div>
            <h3 class="title"><?php echo $title ?></h3>
            <p>این تجربه در صفحه <strong><?php echo $title ?></strong> بیان شده است. </p>
            <a target="_blank" class="gotopage" href="<?php echo get_the_permalink($business_id); ?>">مشاهده صفحه کسب و کار</a>
        </div>
    </div>

    <?php
    

    return ob_get_clean();
}
add_shortcode("trpi_single_commnet_sidebar" , "trpi_single_commnet_sidebar_shortcode");