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
        foreach($data['result'] as $item){
            include __DIR__ . '/../widgets/review-item.php';
        }
        include __DIR__ . '/../widgets/pagination.php';
        ?>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode("trpi_reviews_list" , "trpi_reviews_list_shortcode");