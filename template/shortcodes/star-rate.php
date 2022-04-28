<?php

function trpi_star_rate_shortcode(){
    ob_start();
    global $trpiBusiness;
    $average = $trpiBusiness->get_review_average();
    ?>
    <div class="trpi_wrapper_rating">
        <div class="buisiness_star_rating" data-rating="<?php echo $average; ?>" ></div>
        <div><span><?php echo round($average , 2); ?></span></div>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode("trpi_star_rate" , "trpi_star_rate_shortcode");