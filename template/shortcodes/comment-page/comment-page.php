<?php

use TrustPilot\business;

function trpi_single_commnet_content_shortcode(){
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
    
    $item = business::get_review($review_id);


    if(!is_object($item)){
      return;
    }


    $post = get_post($item->comment_post_ID);
    ?>
    <div class="trpi_review_item" data-post="<?php echo $post->post_title; ?>" data-post-id="<?php echo $post->ID; ?>">
        <?php
          global $post;
          $author_id = (int)$post->post_author;
          $current_user_id = get_current_user_id();
          $can_reply = $author_id == $current_user_id ? true : false;
          include __DIR__ . '/../../widgets/review-card.php';
        ?>
    </div>
    <?php
    include_once TRUST_PILOT_PATH . 'template/widgets/popup.php'; 

    return ob_get_clean();
}
add_shortcode("trpi_single_commnet_content" , "trpi_single_commnet_content_shortcode");