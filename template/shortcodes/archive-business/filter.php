<?php

function trpi_filter_side_bar_shortcode(){
    ob_start();
    $terms = get_terms( 'categories', array("hide_empty" => false) );
    ?>

    <div class="filter-section">

        <div class="filter-widget filter-category">
            <h5>دسته بندی ها</h5>
            <?php foreach($terms as $term) :
                echo "<li><a href='".home_url('categories/' .$term->slug )."'>".$term->name."</a></li>";
            endforeach; ?>
        </div>

        <div class="filter-widget filter-city">
            <label>
                <h5>موقعیت کسب و کار</h5>
                <input placeholder="مثلا تهران" value="<?php echo isset($_GET['city']) ? $_GET['city'] : '' ?>" >
            </label>
            <button id="apply_city_filter">اعمال</button>
        </div>

        <div class="filter-widget filter-count-review">
            <h5>تعداد تجربه ها</h5>
            <div>
                <label>
                    <input type="radio" name="count_review" value="0" <?php echo !isset($_GET['count']) ? 'checked' : '' ?> >
                    <span>همه</span>
                </label>
                <label>
                    <input type="radio" name="count_review"  value="25"  <?php echo isset($_GET['count']) && $_GET['count'] == '25' ? 'checked' : '' ?> >
                    <span>+25</span>
                </label>
                <label>
                    <input type="radio" name="count_review" value="50" <?php echo isset($_GET['count']) && $_GET['count'] == '50' ? 'checked' : '' ?> >
                    <span>+50</span>
                </label>
                <label>
                    <input type="radio" name="count_review" value="100"  <?php echo isset($_GET['count']) && $_GET['count'] == '100' ? 'checked' : '' ?> >
                    <span>+100</span>
                </label>
                <label>
                    <input type="radio" name="count_review" value="250"  <?php echo isset($_GET['count']) && $_GET['count'] == '250' ? 'checked' : '' ?>>
                    <span>+250</span>
                </label>
            </div>
        </div>

        <div class="filter-widget filter-time-period">
            <h5>بازه زمانی</h5>
            <div>
                <label>
                    <input type="radio" name="time_period" value="0" <?php echo !isset($_GET['period']) ? 'checked' : '' ?> >
                    <span>از ابتدا</span>
                </label>
                <label>
                    <input type="radio" name="time_period"  value="180" <?php echo isset($_GET['period']) && $_GET['period'] ==  '180' ? 'checked' : '' ?> >
                    <span>6 ماه پیش</span>
                </label>
                <label>
                    <input type="radio" name="time_period"  value="365" <?php echo isset($_GET['period']) && $_GET['period'] ==  '365' ? 'checked' : '' ?>>
                    <span>12 ماه پیش</span>
                </label>
                <label>
                    <input type="radio" name="time_period"  value="540" <?php echo isset($_GET['period']) && $_GET['period'] ==  '540' ? 'checked' : '' ?>>
                    <span>18 ماه پیش</span>
                </label>
            </div>
        </div>

    </div>
    <?php
    return ob_get_clean();
}

add_shortcode("trpi_filter_side_bar" , "trpi_filter_side_bar_shortcode");