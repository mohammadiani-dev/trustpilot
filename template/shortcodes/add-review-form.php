

<?php

use TrustPilot\business;
use TrustPilot\functions;

function trpi_add_form_section_shortcode(){
    ob_start();


    if(! (isset($_GET['post_id']) && (int)$_GET['post_id'] > 0 && get_post_type((int)$_GET['post_id']) == 'business') ){
        return;
    }



    $post_id = (int)$_GET['post_id'];


    ?>
    <div class="trpi_add_review_form" data-ajax="<?php echo admin_url("admin-ajax.php"); ?>" data-post-id="<?php echo $post_id; ?>">
        <div class="rating-section">
            <p>به تجربه ی اخیر خود امتیاز دهید</p>
            <div class="buisiness_user_star_rating" data-star="<?php echo isset($_GET['star']) ? $_GET['star'] : 0 ?>"></div>
        </div>
        <div class="review-form">
            
            <div class="content-field">
                <p>در مورد تجربه ی خود برای ما بگویید</p>
                <a href="#">چگونه یک تجربه مفید بنویسم!</a>
                <div class="wrapper">
                    <textarea placeholder="تجربه ی من اینه که ..."></textarea>
                </div>
            </div>

            <div class="title-field">
                <p>برای تجربه خود عنوانی اختصاص بدید</p>
                <div class="wrapper">
                    <input type="text" placeholder="یک خرید دوست داشتنی...">
                </div>
            </div>

            <div class="condition-field">
                <label>
                    <input type="checkbox">
                    <p>تایید کنید این تجربه واقعی من است <a href="#">شرایط لازم</a></p>
                </label>
            </div>


            <div class="submit-section">
                <?php if(is_user_logged_in()): ?>
                    <button id="trpi_publish_review" data-nonce="<?php echo wp_create_nonce("trpi_submit_review"); ?>">انتشار تجربه</button>
                <?php else: ?>
                    <button id="trpi_login_before_publish">برای انتشار تجربه باید وارد شوید</button>
                <?php endif; ?>
            </div>

        </div>
    </div>


    <div class="trpi_review_item fansy_review_thumb" id="fansy_review_thumb">
        <div class="review-item">
            <div class="item-header">
                <div>
                    <?php echo get_avatar(get_current_user_id()); ?>
                </div>
                <div>
                    <?php $user = get_user_by("ID" , get_current_user_id()); ?>
                    <strong><?php echo $user->display_name ?></strong>
                    <p><?php echo ' در نقد و بررسی ' . '<b>'.get_the_title($_GET['post_id']).'</b>' ?></p>
                </div>
            </div>
            <div class="item-body">
                <div class="meta">
                    <div class="trpi_wrapper_star">
                        <div class="trpi_star_valid"></div>
                    </div>
                </div>
                <h5 class="review-title"></h5>
                <p class="review-content"></p>
            </div>
            <div class="item-footer">
                <div class="image-container">
                    <img width="100" src="<?php echo TRUST_PILOT_URL.'/assets/images/logo.png' ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="trpi_badge_holder">
        <div id='company_badge_box' >
            <div class="trpi_wrapper_logo">
                <img width="150" src="<?php echo TRUST_PILOT_URL.'/assets/images/logo.png' ?>">
            </div>
            <div class="trpi_wrapper_star">
                <div class="trpi_star_valid"></div>
            </div>
            <div class="trpi_wrapper_meta">
                <span> امتیاز </span><strong class="level"></strong>
                <p><span> از </span><strong class="total"></strong><strong> تجربه </strong></p>
            </div>
        </div> 
    </div>
    


    <?php
    return ob_get_clean();
}

add_shortcode("trpi_add_form_section" , "trpi_add_form_section_shortcode");