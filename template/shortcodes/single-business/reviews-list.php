<?php

function trpi_reviews_list_shortcode(){
    ob_start();
    global $trpiBusiness;
    $args = [];
    if(isset($_GET['cpage']) && (int)$_GET['cpage'] > 0){
        $args['page'] = (int)$_GET['cpage'];
    }
    $data = $trpiBusiness->get_reviews($args);
    $title = get_the_title();
    ?>
    <div class="trpi_review_item" data-post="<?php echo $title; ?>" data-post-id="<?php echo get_the_ID(); ?>">
        <?php
        global $post;
        $author_id = (int)$post->post_author;
        $current_user_id = get_current_user_id();

        $can_reply = $author_id == $current_user_id ? true : false;

        foreach($data['result'] as $item){
            include __DIR__ . '/../../widgets/review-card.php';
        }
        include __DIR__ . '/../../widgets/pagination.php';
        ?>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode("trpi_reviews_list" , "trpi_reviews_list_shortcode");