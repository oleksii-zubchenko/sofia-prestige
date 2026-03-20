<?php
if (!defined('ABSPATH')) {
    exit;
}

$page_id = get_queried_object_id();

$consultation_image = get_field('consultation_image', $page_id);
$consultation_image_alt = trim((string) get_field('consultation_image_alt', $page_id));
$consultation_h2 = trim((string) get_field('consultation_h2', $page_id));
$consultation_h1 = trim((string) get_field('consultation_h1', $page_id));
$consultation_h3 = trim((string) get_field('consultation_h3', $page_id));

$img_url = '';
$img_alt = '';

if (is_array($consultation_image)) {
    $img_url = !empty($consultation_image['url']) ? $consultation_image['url'] : '';

    if ($consultation_image_alt !== '') {
        $img_alt = $consultation_image_alt;
    } elseif (!empty($consultation_image['alt'])) {
        $img_alt = $consultation_image['alt'];
    } elseif ($consultation_h1 !== '') {
        $img_alt = wp_strip_all_tags($consultation_h1);
    }
} elseif (is_numeric($consultation_image)) {
    $image_id = (int) $consultation_image;
    $img_url = wp_get_attachment_image_url($image_id, 'full');

    if ($consultation_image_alt !== '') {
        $img_alt = $consultation_image_alt;
    } else {
        $media_alt = trim((string) get_post_meta($image_id, '_wp_attachment_image_alt', true));
        $img_alt = $media_alt !== '' ? $media_alt : wp_strip_all_tags($consultation_h1);
    }
} elseif (is_string($consultation_image) && $consultation_image !== '') {
    $img_url = $consultation_image;
    $img_alt = $consultation_image_alt !== '' ? $consultation_image_alt : wp_strip_all_tags($consultation_h1);
}

$telegram_option = trim((string) get_option('sofia_contact_telegram', ''));
$phone_option = trim((string) get_option('sofia_contact_phone', ''));

$telegram_href = '';
if ($telegram_option !== '') {
    $telegram_href = (stripos($telegram_option, 'http') === 0)
        ? $telegram_option
        : 'https://t.me/' . ltrim($telegram_option, '@');
}

$phone_href = '';
if ($phone_option !== '') {
    $phone_href = 'tel:' . preg_replace('/[^\d+]/', '', $phone_option);
}

$has_content = (
    $img_url !== '' ||
    $consultation_h2 !== '' ||
    $consultation_h1 !== '' ||
    $consultation_h3 !== '' ||
    $telegram_href !== '' ||
    ($phone_option !== '' && $phone_href !== '')
);

if (!$has_content) {
    return;
}
?>

<section class="consultation-block hero<?php echo $img_url === '' ? ' no-image' : ''; ?>">
    <?php if ($img_url !== '') : ?>
        <div class="backgroundContainer">
            <div class="slide">
                <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>">
            </div>
        </div>
    <?php endif; ?>

    <div class="contentBackground"></div>

    <div class="container">
        <div class="content">
            <?php if ($consultation_h2 !== '') : ?>
                <h2><?php echo esc_html($consultation_h2); ?></h2>
            <?php endif; ?>

            <?php if ($consultation_h1 !== '') : ?>
                <h1><?php echo nl2br(esc_html($consultation_h1)); ?></h1>
            <?php endif; ?>

            <?php if ($consultation_h3 !== '') : ?>
                <h3><?php echo esc_html($consultation_h3); ?></h3>
            <?php endif; ?>

            <?php if ($telegram_href !== '' || ($phone_option !== '' && $phone_href !== '')) : ?>
                <footer class="contentFooter">
                    <?php if ($telegram_href !== '') : ?>
                        <a href="<?php echo esc_url($telegram_href); ?>" class="heroContacts">
                            <div class="blueSvg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7" viewBox="0 0 9 7" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.618703 3.01345C3.03462 2.02559 4.64561 1.37433 5.45167 1.05968C7.75314 0.161271 8.23136 0.00520824 8.54306 5.49456e-05C8.61162 -0.00107846 8.7649 0.0148671 8.8642 0.0904821C8.94804 0.15433 8.9711 0.240579 8.98214 0.301114C8.99318 0.361649 9.00693 0.499549 8.996 0.607299C8.87128 1.83715 8.33163 4.82167 8.05709 6.19913C7.94092 6.78198 7.71218 6.97741 7.49073 6.99653C7.00947 7.03809 6.64402 6.69804 6.1779 6.41127C5.44851 5.96254 5.03645 5.68321 4.32845 5.24533C3.51024 4.73929 4.04065 4.46116 4.50695 4.00662C4.62898 3.88767 6.74942 2.07755 6.79046 1.91334C6.7956 1.8928 6.80036 1.81625 6.7519 1.77583C6.70344 1.73541 6.63193 1.74923 6.58032 1.76022C6.50716 1.77581 5.34195 2.49862 3.08469 3.92865C2.75395 4.1418 2.45437 4.24565 2.18596 4.24021C1.89007 4.23421 1.32087 4.08319 0.897739 3.9541C0.378747 3.79577 -0.0337378 3.71206 0.00218033 3.44316C0.0208887 3.30311 0.226396 3.15987 0.618703 3.01345Z" fill="#F5F5F5" />
                                </svg>
                            </div>

                            <span>Telegram</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
                                <path d="M12.0418 0V11.0569H10.0863L10.0158 10.9861V3.4816L1.41008 12.0418L0.0236512 10.7055L0 10.587L8.5551 2.02302H1.03923L0.968752 1.95264V0H12.0418Z" fill="white" />
                            </svg>
                        </a>
                    <?php endif; ?>

                    <?php if ($phone_option !== '' && $phone_href !== '') : ?>
                        <a href="<?php echo esc_url($phone_href); ?>" class="heroContacts small">
                            <div class="blueSvg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="8" viewBox="0 0 9 8" fill="none">
                                    <path d="M4.66606 0.0330587C5.05885 -0.0254907 5.46105 -0.00627155 5.84793 0.0907393C6.35946 0.219011 6.84398 0.483348 7.24325 0.882573C7.64254 1.28186 7.90681 1.76667 8.03508 2.27829C8.13208 2.66515 8.15132 3.06738 8.09276 3.46015C8.05997 3.67948 7.85578 3.83061 7.63642 3.798C7.41698 3.76529 7.26555 3.56109 7.29818 3.34165C7.3412 3.05309 7.32697 2.75752 7.25581 2.4737C7.16194 2.0993 6.96865 1.74474 6.67508 1.45114C6.38149 1.15755 6.02691 0.964309 5.65252 0.870409C5.36867 0.799235 5.07315 0.785015 4.78456 0.828032C4.56508 0.860749 4.36054 0.70928 4.32783 0.489795C4.29513 0.270325 4.44659 0.0657737 4.66606 0.0330587Z" fill="white" />
                                    <path d="M4.74056 1.47116C5.22603 1.39879 5.74029 1.5496 6.11508 1.92437C6.48975 2.29912 6.64022 2.81312 6.5679 3.2985C6.53518 3.51794 6.33098 3.66937 6.11155 3.63674C5.89207 3.60402 5.7406 3.39948 5.77331 3.18C5.80969 2.93566 5.73387 2.67958 5.54691 2.49254C5.35978 2.30542 5.10346 2.2297 4.85906 2.26613C4.63961 2.29884 4.43508 2.14732 4.40232 1.9279C4.3696 1.70842 4.52108 1.50389 4.74056 1.47116Z" fill="white" />
                                    <path d="M2.18996 1.02762C2.33593 1.02762 2.47042 1.10678 2.54125 1.2344L3.03276 2.11973C3.09711 2.23565 3.10012 2.37586 3.04083 2.49445L2.56735 3.44142C2.56735 3.44142 2.70457 4.14689 3.27882 4.72114C3.85308 5.2954 4.55617 5.43024 4.55617 5.43024L5.503 4.95684C5.62167 4.89749 5.76198 4.90057 5.87794 4.96504L6.76579 5.45865C6.89328 5.52953 6.97235 5.66395 6.97235 5.80983V6.8291C6.97235 7.34817 6.49021 7.72307 5.99838 7.55711C4.98827 7.21628 3.4203 6.56733 2.42648 5.57349C1.43264 4.57967 0.783687 3.01169 0.44285 2.00158C0.276902 1.50975 0.651802 1.02762 1.17086 1.02762H2.18996Z" fill="white" />
                                    <path d="M5.68264 5.31639L4.73581 5.78961C4.67663 5.81919 4.61138 5.83366 4.5459 5.83199L4.48037 5.82492L4.47919 5.82453C4.47884 5.82446 4.47842 5.82461 4.47802 5.82453C4.47702 5.82433 4.47582 5.82402 4.47448 5.82375C4.47179 5.8232 4.46832 5.82225 4.46428 5.82139C4.45623 5.81967 4.44556 5.81741 4.4325 5.81433C4.40623 5.80812 4.36994 5.79883 4.32577 5.78608C4.23761 5.76062 4.1162 5.72053 3.97537 5.66051C3.69509 5.54107 3.32797 5.33839 2.9948 5.00523C2.66166 4.67209 2.4583 4.30448 2.33834 4.02387C2.27801 3.88276 2.23766 3.76098 2.21199 3.67269C2.19918 3.62861 2.19001 3.5926 2.18374 3.56635C2.1806 3.5532 2.17802 3.54225 2.17628 3.53418C2.17542 3.53016 2.17487 3.52665 2.17432 3.52397C2.17406 3.52268 2.17373 3.52141 2.17354 3.52044C2.17345 3.52001 2.17322 3.51963 2.17314 3.51927V3.51848L2.17275 3.51809C2.15586 3.43114 2.16847 3.34109 2.20807 3.26186L2.68128 2.31464L2.19002 1.42942H1.17099C0.903117 1.42942 0.753096 1.66387 0.82373 1.87321C1.16174 2.87492 1.78697 4.36559 2.71071 5.28932C3.63446 6.21307 5.12512 6.8383 6.12682 7.1763C6.33615 7.24694 6.57061 7.09693 6.57061 6.82904V5.81001L5.68264 5.31639ZM7.37421 6.82904C7.37421 7.59929 6.64413 8.19921 5.86981 7.93792C4.8513 7.59425 3.20639 6.92134 2.14254 5.85749C1.07869 4.79367 0.405797 3.14874 0.0621097 2.13022C-0.199153 1.35591 0.400748 0.625815 1.17099 0.625815H2.19002C2.4819 0.625836 2.75069 0.784186 2.89239 1.03939L3.38405 1.92461C3.51275 2.15647 3.51868 2.43693 3.40013 2.67407L2.9948 3.48474C3.01316 3.54362 3.03972 3.62034 3.0772 3.708C3.16941 3.92369 3.32186 4.19594 3.56297 4.43706C3.80403 4.6781 4.07564 4.82972 4.29046 4.92126C4.37764 4.95841 4.45399 4.98433 4.51255 5.00248L5.32321 4.59754C5.56056 4.47885 5.84116 4.4851 6.07306 4.61402L6.96103 5.10764C7.21597 5.24941 7.37421 5.51828 7.37421 5.81001V6.82904Z" fill="white" />
                                </svg>
                            </div>

                            <span><?php echo esc_html($phone_option); ?></span>
                        </a>
                    <?php endif; ?>
                </footer>
            <?php endif; ?>
        </div>
    </div>
</section>