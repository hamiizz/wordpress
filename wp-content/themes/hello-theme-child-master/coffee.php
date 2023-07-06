<?php
/**
 * Template Name: coffee
 *
**/
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        $coffee_link = hs_give_me_coffee();

        if (!empty($coffee_link)) {
            echo '<a href="' . esc_url($coffee_link) . '" target="_blank">Get a cup of coffee</a>';
        }else{
            echo "coffee not working";
        }
        ?>

    </main>
</div>

<?php
get_footer();
