<div class="custom-posts">
    <?php
    $args = array(
        'post_type' => 'news', // Change this to your custom post type if needed.
        'posts_per_page' => -1,
    );
    $custom_query = new WP_Query($args);
    if ($custom_query->have_posts()) :
        while ($custom_query->have_posts()) : $custom_query->the_post();
            $post_id = Get_the_ID();
    ?>
            <div class="post">
                <h2><?php the_title(); ?></h2>
                <div class="post-content">
                    <?php echo get_the_content($post_id); ?>
                </div>
                <div class="post-meta">
                    Author: <?php echo esc_html(get_post_meta($post_id, '_author_name', true)); ?>
                </div>
            </div>
    <?php endwhile;
        wp_reset_postdata(); // Reset the global $wp_query to the main query.
    endif; ?>
</div>