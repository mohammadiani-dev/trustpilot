<?php

function trpi_register_shortcode_callback(){
    ob_start();

    $user = wp_get_current_user();
    $state = get_user_meta($user->ID , "user-state" , true);

    if(is_user_logged_in() && $state != "waiting-otp"){

        ?>
            <div>
                <p>شما هم اکنون وارد شده اید</p>
                <a href="<?php echo wp_logout_url(get_the_permalink()); ?>">خروج از حساب کاربری</a>
            </div>
        <?php
        return ob_get_clean();
    }

    global $login_url;
    ?>

    <?php if(!is_user_logged_in()): ?>
    <div id="register_basic_user">
        <a id="TrpiloginByGoogle" href="<?php echo $login_url ?>">
            <span>ثبت نام با گوگل</span>
            <span><img src="<?php echo TRUST_PILOT_URL . '/assets/images/google-icon.png' ?>"></span>
        </a>
        <div class="divider-or">
            <hr>
            <span>یا</span>
        </div>

        <div class="wrapper_field fullname">
            <label>نام و نام خانوادگی</label>
            <input type="text" placeholder=""   autocomplete="off">
        </div>

        <div class="wrapper_field email">
            <label>ایمیل</label>
            <input type="email" placeholder=""   autocomplete="off">
        </div>

        <div class="wrapper_field password">
            <label>رمز عبور</label>
            <input type="password"    autocomplete="off" >
        </div>

        <div class="wrapper_field">
            <button id="singup_account">ثبت نام</button>
        </div>
        <div>صاحب کسب و کار هستید؟ <a href="<?php echo home_url("signup-business"); ?>">ساخت حساب کسب و کار</a></div>
        <div>قبلا ثبت نام کرده اید؟ <a href="<?php echo home_url("login"); ?>">ورود به حساب کاربری</a></div>
    </div>
    <?php endif; ?>

    <?php  if(is_user_logged_in() && $state == "waiting-otp" ):  ?>
    <div id="otp_check_user">

       <div class="wrapper_field otp">
            <p>لطفا کد ارسالی به ایمیل ثبت نام را وارد کنید.</p>
            <div class="wrppaer_inputs">
                <input type="number" placeholder="کد تایید ایمیل">
                <button id="resend_otp_code">ارسال مجدد</button>
            </div>
        </div>

        <div>
            <button id="verify_user_by_otp">تایید کد و فعال سازی حساب</button>
        </div>

    </div>
    <a class="trpi_logout_user_link" href="<?php echo wp_logout_url(get_the_permalink()); ?>">خروج</a>
    <?php endif; ?>

    


    <?php
    return ob_get_clean();
}

add_shortcode("trpi-register" , "trpi_register_shortcode_callback");