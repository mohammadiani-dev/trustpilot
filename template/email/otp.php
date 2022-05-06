<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="body-email-score" style="font-family:iransans,vazir,tahoma;width: 100%;margin: 0;padding: 0;">
    <div class="container-email" style="width:85%;margin:20px auto;background:#eee;padding:20px 30px;line-height:2;box-sizing:border-box;direction:rtl;">
        <div class="title-email" style="font-size:18px;font-weight:700;padding-right:9px;padding-bottom:14px;text-align:center;border-bottom:2px dashed #aaa;" >تایید حساب ایمیل در {site_name}</div>
        <div class="text-main">
            <h4 class="hello-text">{display_name} عزیز</h4>
            <p class="main">کد تایید ایمیل شما در سایت 
            <strong>{site_name}</strong> به صورت زیر است.</p>
            <p class="verification_code" style="background:#fff;display:table;text-align: center;width:170px;padding:12px;font-size: 20px;line-height: 48px;margin:auto;border-radius:5px;">{verification_code}</p>
            <!-- <p class="after_code">یا اینکه می توانید بر روی دکمه زیر کلیک کنید تا حساب ایمیلتان تایید شود.</p> -->
            <!-- <a class="button verification_link" href="{verification_link}" style="display:table;text-align:center;font-size: 14px;line-height:48px;background:#4caf50;width:170px;margin:20px auto;text-decoration:none;color:#fff;padding:12px;border-radius:5px;">تایید حساب ایمیل</a> -->
            <p class="thanks">سپاس از همراهی شما</p>
            <p class="sitename">{site_name}</p>
        </div>
    </div>
</body>

</html>
