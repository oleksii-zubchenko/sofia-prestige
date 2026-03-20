<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();

function sofia_get_acf_image_url($image_field_value)
{
    if (empty($image_field_value)) {
        return '';
    }

    if (is_array($image_field_value) && !empty($image_field_value['url'])) {
        return (string) $image_field_value['url'];
    }

    if (is_numeric($image_field_value)) {
        $image_url = wp_get_attachment_image_url((int) $image_field_value, 'full');
        return $image_url ? (string) $image_url : '';
    }

    if (is_string($image_field_value)) {
        return $image_field_value;
    }

    return '';
}

function sofia_get_acf_image_alt($image_field_value)
{
    if (empty($image_field_value)) {
        return '';
    }

    if (is_array($image_field_value) && !empty($image_field_value['alt'])) {
        return trim((string) $image_field_value['alt']);
    }

    if (is_numeric($image_field_value)) {
        return trim((string) get_post_meta((int) $image_field_value, '_wp_attachment_image_alt', true));
    }

    return '';
}

function sofia_get_press_center_page_id_by_acf()
{
    $lang_slug = function_exists('pll_current_language') ? pll_current_language('slug') : '';

    $query = new WP_Query([
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_query'     => [
                    'relation' => 'OR',
                    [
                            'key'     => 'press_banner_image',
                            'compare' => 'EXISTS',
                    ],
                    [
                            'key'     => 'press_banner_title',
                            'compare' => 'EXISTS',
                    ],
                    [
                            'key'     => 'press_banner_alt',
                            'compare' => 'EXISTS',
                    ],
            ],
    ]);

    if (empty($query->posts)) {
        return 0;
    }

    if ($lang_slug !== '' && function_exists('pll_get_post_language')) {
        foreach ($query->posts as $page_id) {
            if (pll_get_post_language($page_id, 'slug') === $lang_slug) {
                return (int) $page_id;
            }
        }
    }

    return (int) $query->posts[0];
}

function sofia_get_press_center_category_data($post_id = 0)
{
    $term = get_category_by_slug('press-center');

    if ($term && !is_wp_error($term)) {
        return [
                'id'   => (int) $term->term_id,
                'name' => $term->name,
                'url'  => get_category_link($term->term_id),
        ];
    }

    if ($post_id) {
        $categories = get_the_category($post_id);

        if (!empty($categories) && !is_wp_error($categories)) {
            $first_category = $categories[0];

            return [
                    'id'   => (int) $first_category->term_id,
                    'name' => $first_category->name,
                    'url'  => get_category_link($first_category->term_id),
            ];
        }
    }

    return [
            'id'   => 0,
            'name' => '',
            'url'  => '',
    ];
}

while (have_posts()) :
    the_post();

    $post_id = get_the_ID();
    $lang_slug = function_exists('pll_current_language') ? pll_current_language('slug') : 'uk';

    $source_page_id = sofia_get_press_center_page_id_by_acf();

    $press_banner_image = $source_page_id ? get_field('press_banner_image', $source_page_id) : null;
    $press_banner_title = $source_page_id ? trim((string) get_field('press_banner_title', $source_page_id)) : '';
    $press_banner_alt = $source_page_id ? trim((string) get_field('press_banner_alt', $source_page_id)) : '';

    $banner_url = sofia_get_acf_image_url($press_banner_image);
    $banner_image_alt = sofia_get_acf_image_alt($press_banner_image);

    $banner_final_alt = $press_banner_alt !== '' ? $press_banner_alt : $banner_image_alt;
    if ($banner_final_alt === '') {
        $banner_final_alt = $press_banner_title;
    }

    $post_title = get_the_title($post_id);
    $post_content_raw = get_post_field('post_content', $post_id);
    $post_content = apply_filters('the_content', $post_content_raw);

    $featured_image_id = get_post_thumbnail_id($post_id);
    $featured_image_url = $featured_image_id ? wp_get_attachment_image_url($featured_image_id, 'full') : '';
    $featured_image_alt = $featured_image_id ? trim((string) get_post_meta($featured_image_id, '_wp_attachment_image_alt', true)) : '';

    if ($featured_image_alt === '') {
        $featured_image_alt = $post_title;
    }

    $article_author_custom = trim((string) get_field('article_author_custom', $post_id));
    $article_category_custom = trim((string) get_field('article_category_custom', $post_id));
    $article_created_date_custom = trim((string) get_field('article_created_date_custom', $post_id));
    $article_rating = (int) get_field('article_rating', $post_id);
    $article_rating_average = trim((string) get_field('article_rating_average', $post_id));

    if ($article_rating < 1 || $article_rating > 5) {
        $article_rating = 0;
    }

    $home_label = $lang_slug === 'en' ? 'Home' : 'Головна';
    $press_center_label_default = $lang_slug === 'en' ? 'Press Center' : 'Прес-центр';
    $blog_label = $lang_slug === 'en' ? 'Blog' : 'Блог';
    $faq_label = $lang_slug === 'en' ? 'FAQ' : 'Часті питання';
    $vacancies_label = $lang_slug === 'en' ? 'Vacancies' : 'Вакансії';
    $related_title = $lang_slug === 'en' ? 'Related materials' : 'СХОЖІ МАТЕРІАЛИ';
    $all_articles_label = $lang_slug === 'en' ? 'All articles' : 'Всі статті';

    $date_label = $lang_slug === 'en' ? 'Date created' : 'Дата створення';
    $author_label = $lang_slug === 'en' ? 'Author' : 'Автор';
    $category_label = $lang_slug === 'en' ? 'Category' : 'Категорія';
    $rate_label = $lang_slug === 'en' ? 'Rate the article:' : 'Оцініть матеріал:';
    $average_label = $lang_slug === 'en' ? 'Average rating' : 'Середня оцінка';

    $faq_url = $lang_slug === 'en' ? home_url('/en/#faq') : home_url('/#faq');
    $vacancies_url = $lang_slug === 'en' ? home_url('/en/вакансії/') : home_url('/вакансії/');

    $press_center_category = sofia_get_press_center_category_data($post_id);
    $press_center_url = !empty($press_center_category['url']) ? $press_center_category['url'] : '';
    $press_center_label = !empty($press_center_category['name']) ? $press_center_category['name'] : $press_center_label_default;

    $related_args = [
            'post_type'      => 'post',
            'posts_per_page' => 6,
            'post__not_in'   => [$post_id],
            'orderby'        => 'date',
            'order'          => 'DESC',
    ];

    if (!empty($press_center_category['id'])) {
        $related_args['cat'] = (int) $press_center_category['id'];
    }

    if (function_exists('pll_current_language')) {
        $related_args['lang'] = $lang_slug;
    }

    $related_posts = get_posts($related_args);

    $has_meta_block = (
            $article_created_date_custom !== '' ||
            $article_author_custom !== '' ||
            $article_category_custom !== '' ||
            $article_rating_average !== '' ||
            $article_rating > 0
    );
    ?>

    <div class="wrapper-page-article">
        <?php if ($banner_url !== '') : ?>
            <section class="hero hero--inner">
                <div class="backgroundContainer">
                    <div class="slide">
                        <img src="<?php echo esc_url($banner_url); ?>" alt="<?php echo esc_attr($banner_final_alt); ?>">
                    </div>
                </div>

                <div class="contentBackground"></div>

                <div class="container">
                    <div class="content content--inner">
                        <?php if ($press_banner_title !== '') : ?>
                            <h1><?php echo esc_html($press_banner_title); ?></h1>
                        <?php endif; ?>

                        <span class="contentFooter"></span>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <div class="breadcrumbs-wrap">
            <div class="container">
                <nav class="breadcrumbs" aria-label="Breadcrumbs">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <?php echo esc_html($home_label); ?>
                    </a>

                    <?php if ($press_center_url !== '') : ?>
                        <span class="breadcrumbs-sep">
                        <svg xmlns="http://www.w3.org/2000/svg" width="5" height="6" viewBox="0 0 5 6" fill="none">
                            <path d="M4.5 2.59814L2.32137e-07 5.19622L4.59269e-07 6.82294e-05L4.5 2.59814Z" fill="#2680F2"/>
                        </svg>
                    </span>

                        <a href="<?php echo esc_url($press_center_url); ?>">
                            <?php echo esc_html($press_center_label); ?>
                        </a>
                    <?php endif; ?>

                    <span class="breadcrumbs-sep">
                    <svg xmlns="http://www.w3.org/2000/svg" width="5" height="6" viewBox="0 0 5 6" fill="none">
                        <path d="M4.5 2.59814L2.32137e-07 5.19622L4.59269e-07 6.82294e-05L4.5 2.59814Z" fill="#2680F2"/>
                    </svg>
                </span>

                    <span><?php echo esc_html($blog_label); ?></span>
                </nav>
            </div>
        </div>

        <section class="article-page">
            <div class="container">
                <div class="article-layout">
                    <aside class="article-sidebar">
                        <div class="article-sidebar-box">
                        <span class="article-sidebar-link is-active">
                            <?php echo esc_html($blog_label); ?>
                        </span>

                            <a href="<?php echo esc_url($faq_url); ?>" class="article-sidebar-link">
                                <?php echo esc_html($faq_label); ?>
                            </a>

                            <a href="<?php echo esc_url($vacancies_url); ?>" class="article-sidebar-link">
                                <?php echo esc_html($vacancies_label); ?>
                            </a>
                        </div>
                    </aside>

                    <div class="article-content">
                        <article class="article-entry">
                            <h2 class="article-title">
                                <span class="text"><?php echo esc_html($post_title); ?></span>
                                <span class="line"></span>
                            </h2>

                            <?php if ($featured_image_url !== '') : ?>
                                <div class="article-image">
                                    <img
                                            src="<?php echo esc_url($featured_image_url); ?>"
                                            alt="<?php echo esc_attr($featured_image_alt); ?>"
                                            loading="lazy"
                                    >
                                </div>
                            <?php endif; ?>

                            <div class="article-text">
                                <?php echo $post_content; ?>
                            </div>

                            <?php if ($has_meta_block) : ?>
                                <div class="article-meta">
                                    <div class="article-meta-grid">
                                        <div class="wrap-item-top wrapper-grid">
                                            <?php if ($article_created_date_custom !== '') : ?>
                                                <div class="article-meta-item">
                                                    <span class="article-meta-label"><?php echo esc_html($date_label); ?></span>
                                                    <span class="article-meta-value"><?php echo esc_html($article_created_date_custom); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($article_author_custom !== '') : ?>
                                                <div class="article-meta-item">
                                                    <span class="article-meta-label"><?php echo esc_html($author_label); ?></span>
                                                    <span class="article-meta-value"><?php echo esc_html($article_author_custom); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($article_category_custom !== '') : ?>
                                                <div class="article-meta-item">
                                                    <span class="article-meta-label"><?php echo esc_html($category_label); ?></span>
                                                    <span class="article-meta-value"><?php echo esc_html($article_category_custom); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="wrap-item-bottom wrapper-grid">
                                            <?php if ($article_rating > 0) : ?>
                                                <div class="article-meta-label"><?php echo esc_html($rate_label); ?></div>

                                                <div class="article-meta-item article-meta-item-rating">
                                                    <div class="article-rating-stars">
                                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                            <span class="article-star <?php echo $i <= $article_rating ? 'is-active' : ''; ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="24" viewBox="0 0 26 24" fill="none">
                                                                <path d="M10.5275 1.4773C11.1709 -0.492437 13.972 -0.492433 14.6153 1.47731L16.2706 6.54535C16.4624 7.13261 17.0125 7.53022 17.6332 7.53022H22.9897C25.0715 7.53022 25.9371 10.1805 24.2529 11.3978L19.9194 14.5301C19.4172 14.893 19.2071 15.5364 19.3989 16.1236L21.0541 21.1917C21.6975 23.1614 19.4313 24.7994 17.7471 23.582L13.4136 20.4498C12.9114 20.0868 12.2314 20.0868 11.7293 20.4498L7.39579 23.582C5.71154 24.7994 3.44539 23.1614 4.08872 21.1917L5.74397 16.1236C5.93577 15.5364 5.72565 14.893 5.22351 14.5301L0.889997 11.3978C-0.794259 10.1805 0.0713378 7.53022 2.15319 7.53022H7.5097C8.13039 7.53022 8.68049 7.13261 8.87229 6.54535L10.5275 1.4773Z" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($article_rating_average !== '') : ?>
                                                <div class="article-meta-item">
                                                    <span class="article-meta-label"><?php echo esc_html($average_label); ?></span>
                                                    <span class="article-meta-value"><?php echo esc_html($article_rating_average); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php if (!empty($related_posts)) : ?>
    <section class="blog-section related-posts-section" data-blog-slider>
        <div class="container">
            <div class="blog-head">
                <h2 class="blog-title"><?php echo esc_html($related_title); ?></h2>
                <span class="blog-line"></span>

                <?php if ($press_center_url !== '') : ?>
                    <a href="<?php echo esc_url($press_center_url); ?>" class="blog-all-link blog-all-link-desktop">
                        <?php echo esc_html($all_articles_label); ?>
                    </a>
                <?php endif; ?>
            </div>

            <div class="blog-slider">
                <div class="blog-track" data-blog-track>
                    <?php foreach ($related_posts as $related_post) : ?>
                        <div class="blog-slide">
                            <?php
                            get_template_part(
                                    'ui/blocks/blog/blog-card',
                                    null,
                                    ['post' => $related_post]
                            );
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="blog-pagination" data-blog-pagination></div>

            <?php if ($press_center_url !== '') : ?>
                <a href="<?php echo esc_url($press_center_url); ?>" class="blog-all-link blog-all-link-mobile">
                    <?php echo esc_html($all_articles_label); ?>
                </a>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

    <?php wp_reset_postdata(); ?>

<?php
endwhile;

get_footer();