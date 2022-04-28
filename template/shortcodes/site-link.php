<?php

function trpi_site_link_shortcode(){
    ob_start();

    include __DIR__ . "/../widgets/site-link-button.php";

    return ob_get_clean();
}

add_shortcode("trpi_site_link" , "trpi_site_link_shortcode");