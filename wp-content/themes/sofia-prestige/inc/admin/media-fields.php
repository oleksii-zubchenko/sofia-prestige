<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'toplevel_page_sofia-settings') {
        return;
    }

    wp_enqueue_media();

    wp_add_inline_script('jquery-core', "
        jQuery(function ($) {
            $(document).on('click', '.sofia-media-upload', function (e) {
                e.preventDefault();

                const button = $(this);
                const wrapper = button.closest('.sofia-media-field');
                const input = wrapper.find('.sofia-media-input');
                const preview = wrapper.find('.sofia-media-preview');
                const removeButton = wrapper.find('.sofia-media-remove');

                const frame = wp.media({
                    title: 'Оберіть зображення',
                    button: {
                        text: 'Використати зображення'
                    },
                    library: {
                        type: 'image'
                    },
                    multiple: false
                });

                frame.on('select', function () {
                    const attachment = frame.state().get('selection').first().toJSON();

                    input.val(attachment.id);
                    preview.html('<img src=\"' + attachment.url + '\" alt=\"\" style=\"max-width:120px;height:auto;display:block;\">');
                    removeButton.show();
                });

                frame.open();
            });

            $(document).on('click', '.sofia-media-remove', function (e) {
                e.preventDefault();

                const button = $(this);
                const wrapper = button.closest('.sofia-media-field');

                wrapper.find('.sofia-media-input').val('');
                wrapper.find('.sofia-media-preview').html('');
                button.hide();
            });
        });
    ");
});

function sofia_render_media_option_field($option_name, $value, $button_text = 'Завантажити')
{
    $attachment_id = (int) $value;
    $image_url = $attachment_id ? wp_get_attachment_image_url($attachment_id, 'medium') : '';
    ?>
    <div class="sofia-media-field">
        <input
                type="hidden"
                name="<?php echo esc_attr($option_name); ?>"
                id="<?php echo esc_attr($option_name); ?>"
                value="<?php echo esc_attr($attachment_id); ?>"
                class="sofia-media-input"
        >

        <div class="sofia-media-preview" style="margin-bottom:10px;">
            <?php if ($image_url) : ?>
                <img src="<?php echo esc_url($image_url); ?>" alt="" style="max-width:120px;height:auto;display:block;">
            <?php endif; ?>
        </div>

        <button type="button" class="button sofia-media-upload">
            <?php echo esc_html($button_text); ?>
        </button>

        <button
                type="button"
                class="button sofia-media-remove"
                style="<?php echo $image_url ? '' : 'display:none;'; ?> margin-left:8px;"
        >
            Видалити
        </button>
    </div>
    <?php
}