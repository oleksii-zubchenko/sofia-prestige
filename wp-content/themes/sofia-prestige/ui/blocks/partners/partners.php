<?php
if (!defined('ABSPATH')) {
    exit;
}

$page_id = get_queried_object_id();

$partners_title = trim((string) get_field('partners_title', $page_id));
$partners_title = $partners_title !== '' ? $partners_title : 'ПАРТНЕРИ';

$partners = [];

for ($i = 1; $i <= 10; $i++) {
    $image = get_field('partner_logo_' . $i, $page_id);
    $alt = trim((string) get_field('partner_logo_' . $i . '_alt', $page_id));
    $link = trim((string) get_field('partner_logo_' . $i . '_link', $page_id));

    if (empty($image)) {
        continue;
    }

    $image_url = '';
    $image_alt = '';

    if (is_array($image)) {
        $image_url = !empty($image['url']) ? $image['url'] : '';
        $image_alt = $alt !== '' ? $alt : (!empty($image['alt']) ? $image['alt'] : '');
    } elseif (is_numeric($image)) {
        $image_id = (int) $image;
        $image_url = wp_get_attachment_image_url($image_id, 'full');
        $media_alt = trim((string) get_post_meta($image_id, '_wp_attachment_image_alt', true));
        $image_alt = $alt !== '' ? $alt : $media_alt;
    } elseif (is_string($image)) {
        $image_url = $image;
        $image_alt = $alt;
    }

    if ($image_url === '') {
        continue;
    }

    $partners[] = [
        'url'  => $image_url,
        'alt'  => $image_alt,
        'link' => $link,
    ];
}

if (empty($partners)) {
    return;
}
?>

<section class="partners-section">
    <div class="container">
        <div class="partners-head">
            <h2 class="partners-title"><?php echo esc_html($partners_title); ?></h2>
            <span class="partners-line"></span>
        </div>

        <div class="partners-slider" data-partners-slider>
            <div class="partners-track" data-partners-track>
                <?php foreach ($partners as $partner) : ?>
                    <div class="partners-slide">
                        <?php if ($partner['link'] !== '') : ?>
                            <a href="<?php echo esc_url($partner['link']); ?>" class="partners-card" target="_blank" rel="noopener noreferrer">
                                <img src="<?php echo esc_url($partner['url']); ?>" alt="<?php echo esc_attr($partner['alt']); ?>">
                            </a>
                        <?php else : ?>
                            <div class="partners-card">
                                <img src="<?php echo esc_url($partner['url']); ?>" alt="<?php echo esc_attr($partner['alt']); ?>">
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="partners-pagination" data-partners-pagination></div>
        </div>
    </div>
</section>