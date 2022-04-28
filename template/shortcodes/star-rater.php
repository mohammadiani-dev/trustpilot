<?php

function trpi_star_rater_shortcode(){
    ob_start();
    echo '<div class="buisiness_user_star_rating"></div>';
    return ob_get_clean();
}

add_shortcode("trpi_star_rater" , "trpi_star_rater_shortcode");