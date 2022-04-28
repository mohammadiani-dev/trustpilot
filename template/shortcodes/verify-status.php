<?php

function trpi_buisiness_verify_status_shortcode(){
    ob_start();

    ?>
    <div class="trpi_verify_status">
        <img src="<?php echo TRUST_PILOT_URL . '/assets/images/check.png' ?>">
        <p>تایید شده</p>
    </div>
    <?php

    return ob_get_clean();
}

add_shortcode("trpi_buisiness_verify_status" , "trpi_buisiness_verify_status_shortcode");