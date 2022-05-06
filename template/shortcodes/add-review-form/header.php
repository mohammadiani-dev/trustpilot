<?php
function trpi_buisiness_logo_shortcode(){
    ob_start();
    
    if(! (isset($_GET['post_id']) && (int)$_GET['post_id'] > 0 && get_post_type((int)$_GET['post_id']) == 'business') ){
        return;
    }

    global $trpiBusiness; 
    $post_id = (int)$_GET['post_id'];
    $post_page = get_the_permalink($post_id);
    ?>
        <div class="trpi_business_logo_wrapper">
            <a href="<?php echo $post_page ?>"><?php echo get_the_post_thumbnail($post_id); ?></a>
            <div>
                <a href="<?php echo $post_page ?>"><p><?php echo get_the_title($post_id); ?></p></a>
                <a href="<?php echo $post_page ?>"><p><?php echo $trpiBusiness->get_meta("domein");  ?></p></a>
            </div>
        </div>

    <?php
    return ob_get_clean();
}

add_shortcode("trpi_buisiness_logo" , "trpi_buisiness_logo_shortcode");