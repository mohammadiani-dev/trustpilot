<?php

function trpi_register_buisiness_shortcode(){
    ob_start();

    if(is_user_logged_in() && !current_user_can("edit_business_meta") || current_user_can("administrator")){
        ?>
            <div>
                <p>دسترسی شما به این صفحه محدود شده است. لطفا برای ساخت حساب کاربری کسب و کار از حساب کاربری فعلی خود خارج شوید.</p>
                <a href="<?php echo wp_logout_url(get_the_permalink()); ?>">خروج از حساب کاربری</a>
            </div>
        <?php
        return ob_get_clean();
    }
    ?>


    <?php if(!is_user_logged_in()): ?>
    <div id="register_business_form">
    
        <div class="wrapper_field name">
            <label>نام کسب و کار</label>
            <input type="text" placeholder="">
        </div>

        <div class="wrapper_field domein">
            <label>صفحه رسمی کسب و کار (آدرس سایت یا صفحه رسمی اینستاگرام)</label>
            <input type="url" >
        </div>
            
        <div class="wrapper_field email">
            <label>ایمیل کسب و کار</label>
            <input type="email" placeholder="مثلا info@yoursite.com"   autocomplete="off">
        </div>

        <div class="wrapper_field password">
            <label>رمز عبور</label>
            <input type="password"    autocomplete="off" >
        </div>

        <div class="wrapper_field">
            <button id="register_business">ثبت نام</button>
        </div>
        <p>با ثبت نام در این سایت ، شما با شرایط رازداری و حفظ حریم خصوصی و شرایط استفاده از خدمات موافقت می‌کنید.</p>
        <div>قبلا ثبت‌نام کرده‌اید؟ <a href="<?php echo home_url("login"); ?>">ورود به حساب کاربری</a></div>
    </div>
    <?php else :
        $user = wp_get_current_user();
        $user_id = $user->ID;
        $state = get_user_meta($user_id , "user-state" , true);
        $address = get_user_meta($user_id , "company_domein" , true);
    ?>

    <?php if($state == "waiting-otp") : ?>
    <div id="register_virify_step">

        <div class="steps">

            <div class="step">
                <div class="step_number"><span>1</span><strong>گام اول : </strong><p>تایید ایمیل کسب و کار</p></div>
                <div class="wrapper_field otp">
                    <p>لطفا کد ارسالی به ایمیل ثبت نام را وارد کنید.</p>
                    <div class="wrppaer_inputs">
                        <input type="number" placeholder="کد تایید ایمیل">
                        <button id="resend_otp_code">ارسال مجدد</button>
                    </div>
                </div>
            </div>

            <div class="step">
                <div class="step_number"><span>2</span><strong>گام دوم : </strong><p>تایید دامنه کسب و کار</p></div>
                <div class="wrapper_field verify_file">
                    <p>فایل زیر را در ریشه سایت آپلود کنید طوری که از طریق <a href="">این</a> آدرس در دسترس باشد</p>
                    <button id="" data-valid="<?php echo md5($user_id); ?>">دانلود فایل اعتبارسنجی</button>
                </div>
            </div>

        </div>

        <div>
            <button id="verify_business">تایید و ایجاد صفحه کسب و کار</button>
        </div>


    </div>
    <a class="trpi_logout_user_link" href="<?php echo wp_logout_url(get_the_permalink()); ?>">خروج</a>    
    <?php endif; ?>

    <?php if($state == "waiting-complete-info") : ?>
    <div id="complete_company_data">

        <div class="wrapper_field name">
            <label>نام کسب و کار</label>
            <input type="text" placeholder="" value="<?php echo $user->display_name; ?>" disabled>
        </div>

        <div class="wrapper_field domain">
            <label>صفحه رسمی کسب و کار</label>
            <input type="url" value="<?php echo $address; ?>" disabled>
        </div>

        <div class="wrapper_field category">
            <label>دسته بندی کسب و کار</label>
            <select>
                   <?php 
                    $terms = get_terms( 'categories', array("hide_empty" => false) );
                    foreach($terms as $term) :
                        echo "<option value='$term->term_id'>$term->name</option>";
                    endforeach; 
                    ?>
            </select>
        </div>


        <div class="wrapper_field about">
            <label>درباره کسب و کار</label>
            <textarea></textarea>
        </div>

        <div class="wrapper_field phone">
            <label>شماره تماس</label>
            <input type="text">
        </div>

        <div class="wrapper_field state">
            <label>استان</label>
            <select>
                <option value="0">استان را انتخاب کنید</option>
                <option value="KHZ">خوزستان</option>
                <option value="THR">تهران</option>
                <option value="ILM">ایلام</option>
                <option value="BHR">بوشهر</option>
                <option value="ADL">اردبیل</option>
                <option value="ESF">اصفهان</option>
                <option value="YZD">یزد</option>
                <option value="KRH">کرمانشاه</option>
                <option value="KRN">کرمان</option>
                <option value="HDN">همدان</option>
                <option value="GZN">قزوین</option>
                <option value="ZJN">زنجان</option>
                <option value="LRS">لرستان</option>
                <option value="ABZ">البرز</option>
                <option value="EAZ">آذربایجان شرقی</option>
                <option value="WAZ">آذربایجان غربی</option>
                <option value="CHB">چهارمحال و بختیاری</option>
                <option value="SKH">خراسان جنوبی</option>
                <option value="RKH">خراسان رضوی</option>
                <option value="NKH">خراسان شمالی</option>
                <option value="SMN">سمنان</option>
                <option value="FRS">فارس</option>
                <option value="QHM">قم</option>
                <option value="KRD">کردستان</option>
                <option value="KBD">کهگیلویه و بویراحمد</option>
                <option value="GLS">گلستان</option>
                <option value="GIL">گیلان</option>
                <option value="MZN">مازندران</option>
                <option value="MKZ">مرکزی</option>
                <option value="HRZ">هرمزگان</option>
                <option value="SBN">سیستان و بلوچستان</option>
            </select>
        </div>

        <div class="wrapper_field city">
            <label>شهرستان</label>
            <input type="text">
        </div>

        <div class="wrapper_field address">
            <label>آدرس دقیق کسب و کار</label>
            <textarea></textarea>
        </div>

        <div class="wrapper_field_upload logo">
            <div>لوگوی کسب و کار خود را انتخاب نمایید</div>
            <div class="select_logo">
                <img class="complete_company_logo" src="<?php echo TRUST_PILOT_URL . '/assets/images/logo.jpg' ?>" />
                <input type="file" id="complete_company_logo" hidden>
            </div>
        </div>

        <button id="save_complete_company_data">ارسال اطلاعات</button>

    </div>
    <a class="trpi_logout_user_link" href="<?php echo wp_logout_url(get_the_permalink()); ?>">خروج</a>
    <?php endif; ?>

    <?php if($state == "completed") : ?>
    <div id="show_status_company">
        <p>اطلاعات شما توسط مدیریت در حال بررسی است.پس از تایید اطلاعات ، صفحه کسب و کار شما در سایت ساخته خواهد شد.</p>
    </div>
    <a class="trpi_logout_user_link" href="<?php echo wp_logout_url(get_the_permalink()); ?>">خروج</a>    <?php endif; ?>

    <?php
    endif;

    return ob_get_clean();
}

add_shortcode("trpi_register_buisiness" , "trpi_register_buisiness_shortcode");