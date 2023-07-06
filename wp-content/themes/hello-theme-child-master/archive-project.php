<?php
/**
 * Template Name: Archive projects
 *
**/
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        $args = array(
            'post_type' => 'projects',
            'posts_per_page' => 6,
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
        );

        $projects_query = new WP_Query($args);

        if ($projects_query->have_posts()) :
            ?>

            <header class="page-header">
                <h1 class="page-title"><?php post_type_archive_title(); ?></h1>
            </header>

            <?php
            while ($projects_query->have_posts()) :
                $projects_query->the_post();
                ?>

                <article class="post">
                    <header class="entry-header">
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                    </header>
                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>
                </article>

            <?php
            endwhile;
            ?>

            <div class="pagination">
                <?php
                echo paginate_links(array(
                    'base' => get_pagenum_link(1) . '%_%',
                    'format' => '?paged=%#%',
                    'current' => max(1, get_query_var('paged')),
                    'total' => $projects_query->max_num_pages,
                    'prev_text' => '&laquo; Previous',
                    'next_text' => 'Next &raquo;',
                ));
                ?>
            </div>

        <?php
        else :
            ?>
            <p>No projects found.</p>
        <?php
        endif;
        wp_reset_postdata();
        ?>

    </main>
</div>

<?php
get_footer();
