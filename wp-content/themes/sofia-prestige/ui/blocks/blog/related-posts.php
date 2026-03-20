<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!isset($args['post_id'])) {
    return;
}

$post_id = (int) $args['post_id'];
$lang_slug = function_exists('pll_current_language') ? pll_current_language('slug') : 'uk';

$section_title = $lang_slug === 'en' ? 'Related materials' : 'Схожі матеріали';
$all_posts_text = $lang_slug === 'en' ? 'All articles' : 'Всі статті';

$categories = get_the_category($post_id);
$category_ids = [];

if (!empty($categories)) {
    foreach ($categories as $category) {
        $category_ids[] = (int) $category->term_id;
    }
}

$posts_page_url = '';
if (function_exists('pll_get_post')) {
    $base_blog_page_id = $lang_slug === 'en' ? 22 : 20;
    $translated_id = pll_get_post($base_blog_page_id, $lang_slug);

    if ($translated_id) {
        $posts_page_url = get_permalink($translated_id);
    }
}

if ($posts_page_url === '') {
    $posts_page_url = home_url('/');
}

$query_args = [
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => 6,
    'post__not_in'        => [$post_id],
    'ignore_sticky_posts' => true,
];

if (!empty($category_ids)) {
    $query_args['category__in'] = $category_ids;
}

if (function_exists('pll_current_language')) {
    $query_args['lang'] = $lang_slug;
}

$related_query = new WP_Query($query_args);

if (!$related_query->have_posts()) {
    return;
}
?>

    <section class="blog-section related-posts-section" data-blog-slider>
        <div class="container">
            <div class="blog-head">
                <h2 class="blog-title"><?php echo esc_html($section_title); ?></h2>
                <span class="blog-line"></span>

                <a href="<?php echo esc_url($posts_page_url); ?>" class="blog-all-link blog-all-link-desktop">
                    <?php echo esc_html($all_posts_text); ?>
                </a>
            </div>

            <div class="blog-slider">
                <div class="blog-track" data-blog-track>
                    <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                        <div class="blog-slide">
                            <?php get_template_part('ui/blocks/blog/blog-card', null, ['post' => get_post()]); ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="blog-pagination" data-blog-pagination></div>

            <a href="<?php echo esc_url($posts_page_url); ?>" class="blog-all-link blog-all-link-mobile">
                <?php echo esc_html($all_posts_text); ?>
            </a>
        </div>
    </section>

<?php wp_reset_postdata(); ?>