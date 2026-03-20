<?php
/*
Template Name: Blog Page
*/
if (!defined('ABSPATH')) {
    exit;
}

get_header();

if (!function_exists('sofia_get_acf_image_url')) {
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
}

if (!function_exists('sofia_get_acf_image_alt')) {
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
}

if (!function_exists('sofia_get_press_center_page_id_by_acf')) {
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
}

if (!function_exists('sofia_get_press_center_category_data')) {
    function sofia_get_press_center_category_data($fallback_post_id = 0)
    {
        $term = get_category_by_slug('press-center');

        if ($term && !is_wp_error($term)) {
            return [
                    'id'   => (int) $term->term_id,
                    'name' => $term->name,
                    'url'  => get_category_link($term->term_id),
            ];
        }

        if ($fallback_post_id) {
            $categories = get_the_category($fallback_post_id);

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
}

$lang_slug = function_exists('pll_current_language') ? pll_current_language('slug') : 'uk';

$home_label         = $lang_slug === 'en' ? 'Home' : 'Головна';
$press_center_label = $lang_slug === 'en' ? 'Press Center' : 'Прес-центр';
$blog_label         = $lang_slug === 'en' ? 'Blog' : 'Блог';
$faq_label          = $lang_slug === 'en' ? 'FAQ' : 'Часті питання';
$vacancies_label    = $lang_slug === 'en' ? 'Vacancies' : 'Вакансії';
$no_posts_label     = $lang_slug === 'en' ? 'No articles found' : 'Статей не знайдено';
$load_more_label    = $lang_slug === 'en' ? 'Load more' : 'Підвантажити ще';

$per_page_text_map = [
        12 => $lang_slug === 'en' ? 'Show 12 items' : 'Показувати по 12 обʼєктів',
        24 => $lang_slug === 'en' ? 'Show 24 items' : 'Показувати по 24 обʼєкти',
        48 => $lang_slug === 'en' ? 'Show 48 items' : 'Показувати по 48 обʼєктів',
];

$faq_url       = $lang_slug === 'en' ? home_url('/en/#faq') : home_url('/#faq');
$vacancies_url = $lang_slug === 'en' ? home_url('/en/вакансії/') : home_url('/вакансії/');

$source_page_id = sofia_get_press_center_page_id_by_acf();

$press_banner_image = $source_page_id ? get_field('press_banner_image', $source_page_id) : null;
$press_banner_title = $source_page_id ? trim((string) get_field('press_banner_title', $source_page_id)) : '';
$press_banner_alt   = $source_page_id ? trim((string) get_field('press_banner_alt', $source_page_id)) : '';

$banner_url       = sofia_get_acf_image_url($press_banner_image);
$banner_image_alt = sofia_get_acf_image_alt($press_banner_image);

$banner_final_alt = $press_banner_alt !== '' ? $press_banner_alt : $banner_image_alt;
if ($banner_final_alt === '') {
    $banner_final_alt = $press_banner_title !== '' ? $press_banner_title : $blog_label;
}

$press_center_category = sofia_get_press_center_category_data();
$press_center_url = !empty($press_center_category['url']) ? $press_center_category['url'] : '';
$press_center_name = !empty($press_center_category['name']) ? $press_center_category['name'] : $press_center_label;

$allowed_per_page = [12, 24, 48];
$per_page = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 12;
if (!in_array($per_page, $allowed_per_page, true)) {
    $per_page = 12;
}

$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));

$blog_args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
];

if (!empty($press_center_category['id'])) {
    $blog_args['cat'] = (int) $press_center_category['id'];
}

if (function_exists('pll_current_language')) {
    $blog_args['lang'] = $lang_slug;
}

$blog_query = new WP_Query($blog_args);

$next_page_url = '';
if ($blog_query->max_num_pages > $paged) {
    $next_page_url = add_query_arg('per_page', $per_page, get_pagenum_link($paged + 1));
}

$pagination = paginate_links([
        'total'      => $blog_query->max_num_pages,
        'current'    => $paged,
        'type'       => 'array',
        'prev_text'  => '&laquo;',
        'next_text'  => '&raquo;',
        'mid_size'   => 2,
        'end_size'   => 1,
        'add_args'   => [
                'per_page' => $per_page,
        ],
]);
?>

    <div class="wrapper-page-article wrapper-page-blog">
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
                        <h1><?php echo esc_html($press_banner_title !== '' ? $press_banner_title : $blog_label); ?></h1>
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
                            <?php echo esc_html($press_center_name); ?>
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

        <section class="article-page-category article-page">
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
                        <div class="blog-page-content" data-archive-root>
                            <h2 class="article-title">
                                <span class="text"><?php echo esc_html($blog_label); ?></span>
                                <span class="line"></span>
                            </h2>

                            <?php if ($blog_query->have_posts()) : ?>
                                <div class="blog-page-grid" data-archive-grid>
                                    <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
                                        <?php
                                        get_template_part(
                                                'ui/blocks/blog/blog-card',
                                                null,
                                                ['post' => get_post()]
                                        );
                                        ?>
                                    <?php endwhile; ?>
                                </div>

                                <div class="archive-controls">
                                    <?php if ($next_page_url !== '') : ?>
                                        <div data-load-more-wrap>
                                            <button
                                                    type="button"
                                                    class="blog-all-link js-archive-load-more"
                                                    data-next-url="<?php echo esc_url($next_page_url); ?>"
                                            >
                                                <?php echo esc_html($load_more_label); ?>
                                            </button>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($pagination)) : ?>
                                        <div class="blog-page-pagination" data-archive-pagination>
                                            <?php foreach ($pagination as $page_link) : ?>
                                                <?php echo wp_kses_post($page_link); ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <form method="get" class="archive-per-page-form">
                                        <label class="archive-per-page-label">
                                            <span><?php echo esc_html($per_page_text_map[$per_page]); ?></span>

                                            <select name="per_page" class="archive-per-page-select" onchange="this.form.submit()">
                                                <?php foreach ($allowed_per_page as $allowed_value) : ?>
                                                    <option value="<?php echo esc_attr($allowed_value); ?>" <?php selected($per_page, $allowed_value); ?>>
                                                        <?php echo esc_html($allowed_value); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </label>
                                    </form>
                                </div>
                            <?php else : ?>
                                <p><?php echo esc_html($no_posts_label); ?></p>
                            <?php endif; ?>

                            <?php wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

<?php get_footer(); ?>