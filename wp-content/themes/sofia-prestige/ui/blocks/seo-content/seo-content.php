<?php
if (!defined('ABSPATH')) {
    exit;
}

$page_id = get_queried_object_id();
$lang_slug = function_exists('pll_current_language') ? pll_current_language('slug') : 'uk';

$about_title = trim((string) get_field('about_company_title', $page_id));
$about_visible = trim((string) get_field('about_company_text_visible', $page_id));
$about_hidden = trim((string) get_field('about_company_text_hidden', $page_id));

if ($about_title === '') {
    $about_title = $lang_slug === 'en' ? 'ABOUT COMPANY' : 'ПРО КОМПАНІЮ';
}

$has_visible = $about_visible !== '';
$has_hidden = $about_hidden !== '';

if (!$has_visible && !$has_hidden) {
    return;
}

$block_id = 'about-company-' . $page_id;
?>

<section class="about-company-section">
    <div class="container">
        <div class="about-company-head">
            <h2 class="about-company-title"><?php echo esc_html($about_title); ?></h2>
            <span class="about-company-line"></span>
        </div>

        <div class="about-company-box<?php echo $has_hidden ? ' has-hidden-content' : ''; ?>" data-about-company>
            <div class="about-company-content is-collapsed" data-about-company-content>
                <?php if ($has_visible) : ?>
                    <div class="about-company-visible text-editor">
                        <?php echo wpautop(wp_kses_post($about_visible)); ?>
                    </div>
                <?php endif; ?>

                <?php if ($has_hidden) : ?>
                    <div
                        class="about-company-hidden text-editor"
                        id="<?php echo esc_attr($block_id); ?>"
                        data-about-company-hidden
                    >
                        <?php echo wpautop(wp_kses_post($about_hidden)); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($has_hidden) : ?>
            <div class="wrap-btn">
                <button
                    class="about-company-toggle"
                    type="button"
                    aria-expanded="false"
                    aria-controls="<?php echo esc_attr($block_id); ?>"
                    data-about-company-toggle
                >
                    <span class="about-company-toggle-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8" fill="none">
                          <path d="M7.21581 7.42203C6.87427 7.7037 6.37024 7.68323 6.05185 7.36136L0.254692 1.50061C-0.0848975 1.15729 -0.0848975 0.600801 0.254692 0.257486C0.594282 -0.0858287 1.14473 -0.0858286 1.48432 0.257486L6.66667 5.49668L11.849 0.257488C12.1886 -0.0858271 12.7391 -0.085827 13.0786 0.257488C13.4182 0.600803 13.4182 1.15729 13.0786 1.50061L7.28148 7.36136L7.21581 7.42203Z" fill="white"/>
                        </svg>
                    </span>
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>