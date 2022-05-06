<?php
use TrustPilot\business;
$bussiness = new business($post->ID);


?>
<div class="wrapper_business_data">

    <div class="wrapper_field domain">
        <label>صفحه رسمی کسب و کار</label>
        <input name="domein" type="url" value="<?php echo $bussiness->get_meta("domein"); ?>" >
    </div>

    <div class="wrapper_field address">
        <label>ایمیل کسب و کار</label>
        <input name="email" value="<?php echo is_array($bussiness->get_meta("other-data")) ? $bussiness->get_meta("other-data")['email'] : ''; ?>" />
    </div>

    <div class="wrapper_field phone">
        <label>شماره تماس</label>
        <input name="phone" type="text" value="<?php echo is_array($bussiness->get_meta("other-data")) ? $bussiness->get_meta("other-data")['phone'] : ''; ?>" >
    </div>

    <div class="wrapper_field state">
        <label>استان</label>
        <input name="state" type="text" value="<?php echo $bussiness->get_meta("state"); ?>">
    </div>

    <div class="wrapper_field city">
        <label>شهرستان</label>
        <input name="city" type="text" value="<?php echo $bussiness->get_meta("city"); ?>">
    </div>

    <div class="wrapper_field address">
        <label>آدرس دقیق کسب و کار</label>
        <textarea name="address"><?php echo is_array($bussiness->get_meta("other-data")) ? $bussiness->get_meta("other-data")['address'] : ''; ?></textarea>
    </div>



</div>