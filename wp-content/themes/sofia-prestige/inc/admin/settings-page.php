<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_init', function () {
    register_setting('sofia_settings_group', 'sofia_contact_telegram');
    register_setting('sofia_settings_group', 'sofia_contact_phone');
    register_setting('sofia_settings_group', 'sofia_contact_address');
    register_setting('sofia_settings_group', 'sofia_contact_address_en');
    register_setting('sofia_settings_group', 'sofia_contact_address_link');
    register_setting('sofia_settings_group', 'sofia_contact_email');
    register_setting('sofia_settings_group', 'sofia_social_facebook');
    register_setting('sofia_settings_group', 'sofia_social_instagram');
    register_setting('sofia_settings_group', 'sofia_social_youtube');
    register_setting('sofia_settings_group', 'sofia_social_tiktok');

    register_setting('sofia_settings_group', 'sofia_footer_logo');
    register_setting('sofia_settings_group', 'sofia_footer_logo_alt');
    register_setting('sofia_settings_group', 'sofia_footer_copyright');
    register_setting('sofia_settings_group', 'sofia_footer_copyright_en');
    register_setting('sofia_settings_group', 'sofia_footer_policy_text');
    register_setting('sofia_settings_group', 'sofia_footer_policy_text_en');
    register_setting('sofia_settings_group', 'sofia_footer_policy_link');
    register_setting('sofia_settings_group', 'sofia_footer_back_to_top');
    register_setting('sofia_settings_group', 'sofia_footer_back_to_top_en');

    register_setting('sofia_settings_group', 'sofia_payment_icon_1');
    register_setting('sofia_settings_group', 'sofia_payment_icon_2');
    register_setting('sofia_settings_group', 'sofia_payment_icon_3');
    register_setting('sofia_settings_group', 'sofia_payment_icon_4');
    register_setting('sofia_settings_group', 'sofia_payment_icon_5');
    register_setting('sofia_settings_group', 'sofia_payment_icon_6');
    register_setting('sofia_settings_group', 'sofia_payment_icon_7');
    register_setting('sofia_settings_group', 'sofia_payment_icon_8');
    register_setting('sofia_settings_group', 'sofia_payment_icon_9');
    register_setting('sofia_settings_group', 'sofia_payment_icon_10');
    register_setting('sofia_settings_group', 'sofia_payment_icon_11');
});

add_action('admin_menu', function () {
    add_menu_page(
        'Налаштування',
        'Налаштування',
        'manage_options',
        'sofia-settings',
        'sofia_render_settings_page',
        'dashicons-admin-generic',
        2
    );
});

function sofia_render_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $telegram = get_option('sofia_contact_telegram', '');
    $phone = get_option('sofia_contact_phone', '');
    $address = get_option('sofia_contact_address', '');
    $address_en = get_option('sofia_contact_address_en', '');
    $address_link = get_option('sofia_contact_address_link', '');
    $email = get_option('sofia_contact_email', '');
    $facebook = get_option('sofia_social_facebook', '');
    $instagram = get_option('sofia_social_instagram', '');
    $youtube = get_option('sofia_social_youtube', '');
    $tiktok = get_option('sofia_social_tiktok', '');

    $footer_logo = get_option('sofia_footer_logo', '');
    $footer_logo_alt = get_option('sofia_footer_logo_alt', '');
    $footer_copyright = get_option('sofia_footer_copyright', '');
    $footer_copyright_en = get_option('sofia_footer_copyright_en', '');
    $footer_policy_text = get_option('sofia_footer_policy_text', '');
    $footer_policy_text_en = get_option('sofia_footer_policy_text_en', '');
    $footer_policy_link = get_option('sofia_footer_policy_link', '');
    $footer_back_to_top = get_option('sofia_footer_back_to_top', '');
    $footer_back_to_top_en = get_option('sofia_footer_back_to_top_en', '');

    $payment_icon_1 = get_option('sofia_payment_icon_1', '');
    $payment_icon_2 = get_option('sofia_payment_icon_2', '');
    $payment_icon_3 = get_option('sofia_payment_icon_3', '');
    $payment_icon_4 = get_option('sofia_payment_icon_4', '');
    $payment_icon_5 = get_option('sofia_payment_icon_5', '');
    $payment_icon_6 = get_option('sofia_payment_icon_6', '');
    $payment_icon_7 = get_option('sofia_payment_icon_7', '');
    $payment_icon_8 = get_option('sofia_payment_icon_8', '');
    $payment_icon_9 = get_option('sofia_payment_icon_9', '');
    $payment_icon_10 = get_option('sofia_payment_icon_10', '');
    $payment_icon_11 = get_option('sofia_payment_icon_11', '');
    ?>
    <div class="wrap">
        <h1>Налаштування сайту</h1>

        <form method="post" action="options.php">
            <?php settings_fields('sofia_settings_group'); ?>
            <?php do_settings_sections('sofia_settings_group'); ?>

            <h2 style="margin-top: 30px;">Контакти</h2>
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="sofia_contact_telegram">Telegram</label></th>
                    <td><input name="sofia_contact_telegram" type="text" id="sofia_contact_telegram" value="<?php echo esc_attr($telegram); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_contact_address">Адреса</label></th>
                    <td><input name="sofia_contact_address" type="text" id="sofia_contact_address" value="<?php echo esc_attr($address); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_contact_address_en">Address (EN)</label></th>
                    <td><input name="sofia_contact_address_en" type="text" id="sofia_contact_address_en" value="<?php echo esc_attr($address_en); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_contact_address_link">Google Maps посилання</label></th>
                    <td><input name="sofia_contact_address_link" type="text" id="sofia_contact_address_link" value="<?php echo esc_attr($address_link); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_contact_phone">Телефон</label></th>
                    <td><input name="sofia_contact_phone" type="text" id="sofia_contact_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_contact_email">Email</label></th>
                    <td><input name="sofia_contact_email" type="text" id="sofia_contact_email" value="<?php echo esc_attr($email); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_social_facebook">Facebook</label></th>
                    <td><input name="sofia_social_facebook" type="text" id="sofia_social_facebook" value="<?php echo esc_attr($facebook); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_social_instagram">Instagram</label></th>
                    <td><input name="sofia_social_instagram" type="text" id="sofia_social_instagram" value="<?php echo esc_attr($instagram); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_social_youtube">YouTube</label></th>
                    <td><input name="sofia_social_youtube" type="text" id="sofia_social_youtube" value="<?php echo esc_attr($youtube); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_social_tiktok">TikTok</label></th>
                    <td><input name="sofia_social_tiktok" type="text" id="sofia_social_tiktok" value="<?php echo esc_attr($tiktok); ?>" class="regular-text"></td>
                </tr>
                </tbody>
            </table>

            <h2 style="margin-top: 30px;">Footer</h2>
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="sofia_footer_logo">Footer logo</label></th>
                    <td><?php sofia_render_media_option_field('sofia_footer_logo', $footer_logo, 'Завантажити логотип'); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_footer_logo_alt">Footer logo alt</label></th>
                    <td><input name="sofia_footer_logo_alt" type="text" id="sofia_footer_logo_alt" value="<?php echo esc_attr($footer_logo_alt); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_footer_copyright">Copyright</label></th>
                    <td><input name="sofia_footer_copyright" type="text" id="sofia_footer_copyright" value="<?php echo esc_attr($footer_copyright); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_footer_copyright_en">Copyright (EN)</label></th>
                    <td><input name="sofia_footer_copyright_en" type="text" id="sofia_footer_copyright_en" value="<?php echo esc_attr($footer_copyright_en); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_footer_policy_text">Текст публічні договори</label></th>
                    <td><input name="sofia_footer_policy_text" type="text" id="sofia_footer_policy_text" value="<?php echo esc_attr($footer_policy_text); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_footer_policy_text_en">Policy text (EN)</label></th>
                    <td><input name="sofia_footer_policy_text_en" type="text" id="sofia_footer_policy_text_en" value="<?php echo esc_attr($footer_policy_text_en); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_footer_policy_link">Policy link</label></th>
                    <td><input name="sofia_footer_policy_link" type="text" id="sofia_footer_policy_link" value="<?php echo esc_attr($footer_policy_link); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_footer_back_to_top">Кнопка "До гори"</label></th>
                    <td><input name="sofia_footer_back_to_top" type="text" id="sofia_footer_back_to_top" value="<?php echo esc_attr($footer_back_to_top); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_footer_back_to_top_en">Back to top (EN)</label></th>
                    <td><input name="sofia_footer_back_to_top_en" type="text" id="sofia_footer_back_to_top_en" value="<?php echo esc_attr($footer_back_to_top_en); ?>" class="regular-text"></td>
                </tr>
                </tbody>
            </table>

            <h2 style="margin-top: 30px;">Іконки оплати</h2>
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="sofia_payment_icon_1">Payment icon 1</label></th>
                    <td><?php sofia_render_media_option_field('sofia_payment_icon_1', $payment_icon_1, 'Завантажити іконку'); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_payment_icon_2">Payment icon 2</label></th>
                    <td><?php sofia_render_media_option_field('sofia_payment_icon_2', $payment_icon_2, 'Завантажити іконку'); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_payment_icon_3">Payment icon 3</label></th>
                    <td><?php sofia_render_media_option_field('sofia_payment_icon_3', $payment_icon_3, 'Завантажити іконку'); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_payment_icon_4">Payment icon 4</label></th>
                    <td><?php sofia_render_media_option_field('sofia_payment_icon_4', $payment_icon_4, 'Завантажити іконку'); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_payment_icon_5">Payment icon 5</label></th>
                    <td><?php sofia_render_media_option_field('sofia_payment_icon_5', $payment_icon_5, 'Завантажити іконку'); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_payment_icon_6">Payment icon 6</label></th>
                    <td><?php sofia_render_media_option_field('sofia_payment_icon_6', $payment_icon_6, 'Завантажити іконку'); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_payment_icon_7">Payment icon 7</label></th>
                    <td><?php sofia_render_media_option_field('sofia_payment_icon_7', $payment_icon_7, 'Завантажити іконку'); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_payment_icon_8">Payment icon 8</label></th>
                    <td><?php sofia_render_media_option_field('sofia_payment_icon_8', $payment_icon_8, 'Завантажити іконку'); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_payment_icon_9">Payment icon 9</label></th>
                    <td><?php sofia_render_media_option_field('sofia_payment_icon_9', $payment_icon_9, 'Завантажити іконку'); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_payment_icon_10">Payment icon 10</label></th>
                    <td><?php sofia_render_media_option_field('sofia_payment_icon_10', $payment_icon_10, 'Завантажити іконку'); ?></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sofia_payment_icon_11">Payment icon 11</label></th>
                    <td><?php sofia_render_media_option_field('sofia_payment_icon_11', $payment_icon_11, 'Завантажити іконку'); ?></td>
                </tr>
                </tbody>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}