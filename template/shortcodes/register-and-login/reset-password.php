<?php

function trpi_reset_password_shortcode_callback(){
    ob_start();

    if(is_user_logged_in()){
        ?>
            <div>
                <p>شما هم اکنون وارد شده اید</p>
                <a href="<?php echo wp_logout_url(get_the_permalink()); ?>">خروج از حساب کاربری</a>
            </div>
        <?php
        return ob_get_clean();
    }

    if(!isset($_GET['token']) || !isset($_GET['user'])){
        echo "دسترسی شما به این صفحه محدود شده است.";
        return ob_get_clean();
    }

    $token = trim(sanitize_text_field( $_GET["token"] ));
    $user_id = (int)trim(sanitize_text_field( $_GET["user"] ));

    $user = get_user_by("ID" , $user_id);
    $activation_key = $user->user_activation_key;
    $activation_key = explode(":" , $activation_key);
    $expire_time = (int)$activation_key[0];
    
    if(end($activation_key) !== md5($token) || time() > $expire_time ){
        echo "لینک بازیابی رمز عبور نامعتبر است!";
        return ob_get_clean();
    }

    ?>

    <div id="reset_password_form">
        
        <div class="wrapper_field password1">
            <label>رمز عبور جدید</label>
            <input type="password"    autocomplete="off" >
        </div>

        <div class="wrapper_field password2">
            <label>تکرار عبور جدید</label>
            <input type="password"    autocomplete="off" >
        </div>

        <div class="wrapper_field">
            <button id="trpi_save_new_password" data-token="<?php echo $token ?>" data-user="<?php echo $user->ID; ?>">ذخیره رمز جدید</button>
        </div>
    </div>


    <?php
    return ob_get_clean();
}

add_shortcode("trpi-reset-password" , "trpi_reset_password_shortcode_callback");