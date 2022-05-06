<?php

function trpi_add_review_section_shortcode(){
    ob_start();
    ?>


    <div class="add_review_section">

        <div class="user-data-section">
            <?php echo get_avatar(get_current_user_id()); ?>
            <div>
                <?php 
                    if(get_current_user_id() > 0){
                        $user = wp_get_current_user();
                        $fullname = $user->display_name;
                        echo "<strong>$fullname</strong>";
                    }
                ?>
                <a id="trpi_add_new_review_button"  target="_blank" href="<?php echo add_query_arg(['post_id' => get_the_ID() ] , home_url('add-review') ) ?>">یک تجربه جدید اضافه کن</a>
            </div>
        </div>

        <div class="star-rating-section">
            <?php echo do_shortcode("[trpi_star_rater]") ?>
        </div>

    </div>


    <?php
    return ob_get_clean();
}

add_shortcode("trpi_add_review_section" , "trpi_add_review_section_shortcode");