<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!isset($args['post']) || !($args['post'] instanceof WP_Post)) {
    return;
}

$post_obj = $args['post'];
$post_id = $post_obj->ID;

$post_title = get_the_title($post_id);
$post_link = get_permalink($post_id);

$post_excerpt = trim((string) get_the_excerpt($post_id));

if ($post_excerpt === '') {
    $post_content_raw = get_post_field('post_content', $post_id);
    $post_content_clean = wp_strip_all_tags(strip_shortcodes($post_content_raw));
    $post_excerpt = wp_trim_words($post_content_clean, 18, '...');
}

$image_id = get_post_thumbnail_id($post_id);
$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
$image_alt = $image_id ? trim((string) get_post_meta($image_id, '_wp_attachment_image_alt', true)) : '';

if ($image_alt === '') {
    $image_alt = $post_title;
}

if ($image_url === '') {
    $image_url = get_template_directory_uri() . '/assets/img/blog-placeholder.jpg';
}

$lang_slug = function_exists('pll_current_language') ? pll_current_language('slug') : 'uk';
$read_more_text = $lang_slug === 'en' ? 'Read more' : 'Читати більше';
?>

<article class="blog-card">
    <a href="<?php echo esc_url($post_link); ?>" class="blog-card-image">
        <img
                src="<?php echo esc_url($image_url); ?>"
                alt="<?php echo esc_attr($image_alt); ?>"
                loading="lazy"
        >
    </a>

    <div class="blog-card-content">
        <h3 class="blog-card-title">
            <a href="<?php echo esc_url($post_link); ?>">
                <?php echo esc_html($post_title); ?>
            </a>
        </h3>

        <?php if ($post_excerpt !== '') : ?>
            <div class="blog-card-excerpt">
                <p><?php echo esc_html($post_excerpt); ?></p>
            </div>
        <?php endif; ?>

        <a href="<?php echo esc_url($post_link); ?>" class="blog-card-link">
            <?php echo esc_html($read_more_text); ?>
        </a>
    </div>
</article>