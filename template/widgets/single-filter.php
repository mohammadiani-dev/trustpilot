<?php 
    global $trpiBusiness;
    $data = $trpiBusiness->get_star_rating();
    $total = $trpiBusiness->get_review_total();
?>
<div class="single-filter-review" data-ajax="<?php echo admin_url('admin-ajax.php'); ?>" data-post-id="<?php echo get_the_ID(); ?>">

    <div class="header-section">
        <h4>
            <span>نقد و بررسی ها</span>
            <span><?php echo $total; ?></span>
        </h4>
    </div>

    <hr>

    <div class="filter-progress-section">
        <?php foreach($data as $key => $rate): ?>
        <div class="row">
            <div class="check-select">
                <label>
                    <input type="checkbox" data-score="<?php echo $key; ?>">
                    <span><?php echo $rate['label']; ?></span>
                </label> 
            </div>
            <div class="progress">
                <div class="wrapper"></div>
                <div class="percentage" style="width : <?php echo $rate['value']; ?>"></div>
            </div>
            <div class="percent">
                <p><span><?php echo $rate['value']; ?></span></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- <div class="keyword">
        <span class="active">همه</span>
        <span>محصول</span>
        <span>رانندگان</span>
        <span>تخفیف</span>
        <span>خدمات</span>
    </div> -->


    <div class="review_search">
        <input placeholder="جستجو در نقد و بررسی ها...">
        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M500.3 443.7l-119.7-119.7c27.22-40.41 40.65-90.9 33.46-144.7C401.8 87.79 326.8 13.32 235.2 1.723C99.01-15.51-15.51 99.01 1.724 235.2c11.6 91.64 86.08 166.7 177.6 178.9c53.8 7.189 104.3-6.236 144.7-33.46l119.7 119.7c15.62 15.62 40.95 15.62 56.57 0C515.9 484.7 515.9 459.3 500.3 443.7zM79.1 208c0-70.58 57.42-128 128-128s128 57.42 128 128c0 70.58-57.42 128-128 128S79.1 278.6 79.1 208z"/></svg>
    </div>

</div>
<?php include_once TRUST_PILOT_PATH . 'template/widgets/popup.php'; ?>