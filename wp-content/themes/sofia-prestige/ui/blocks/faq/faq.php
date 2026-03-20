<?php
if (!defined('ABSPATH')) {
    exit;
}

$page_id = get_queried_object_id();
$lang_slug = function_exists('pll_current_language') ? pll_current_language('slug') : 'uk';

$fallback_title = $lang_slug === 'en' ? 'Frequently Asked Questions — FAQ' : 'Часті запитання — FAQ';
$fallback_button_text = $lang_slug === 'en' ? 'All answers' : 'Всі відповіді';

$faq_title = trim((string) get_field('faq_title', $page_id));
$faq_button_text = trim((string) get_field('faq_button_text', $page_id));
$faq_button_link = trim((string) get_field('faq_button_link', $page_id));

if ($faq_title === '') {
    $faq_title = $fallback_title;
}

if ($faq_button_text === '') {
    $faq_button_text = $fallback_button_text;
}

$faq_items = [];

for ($i = 1; $i <= 100; $i++) {
    $question_field_name = 'faq_question_' . $i;
    $answer_field_name = 'faq_answer_' . $i;

    $question_field_object = get_field_object($question_field_name, $page_id, false, false);
    $answer_field_object = get_field_object($answer_field_name, $page_id, false, false);

    if (!$question_field_object && !$answer_field_object) {
        break;
    }

    $question = trim((string) get_field($question_field_name, $page_id));
    $answer = trim((string) get_field($answer_field_name, $page_id));

    if ($question === '' || $answer === '') {
        continue;
    }

    $faq_items[] = [
        'question' => $question,
        'answer'   => $answer,
    ];
}

if (empty($faq_items)) {
    return;
}

$faq_schema_entities = [];

foreach ($faq_items as $item) {
    $faq_schema_entities[] = [
        '@type' => 'Question',
        'name' => trim(wp_strip_all_tags(str_replace(['<br>', '<br/>', '<br />'], ' ', $item['question']))),
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => trim(wp_strip_all_tags($item['answer'])),
        ],
    ];
}

$faq_schema = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => $faq_schema_entities,
];

$left_items = [];
$right_items = [];

foreach ($faq_items as $index => $item) {
    if ($index % 2 === 0) {
        $left_items[] = $item;
    } else {
        $right_items[] = $item;
    }
}

if (!function_exists('sofia_render_faq_column_items')) {
    function sofia_render_faq_column_items($items, $page_id, $column_key = 'left')
    {
        foreach ($items as $index => $item) {
            $item_id = 'faq-item-' . $column_key . '-' . $page_id . '-' . $index;
            ?>
            <div class="faq-item">
                <button
                    class="faq-button"
                    type="button"
                    aria-expanded="false"
                    aria-controls="<?php echo esc_attr($item_id); ?>"
                    id="<?php echo esc_attr($item_id); ?>-button"
                >
                    <span class="faq-question">
                        <?php echo wp_kses($item['question'], ['br' => []]); ?>
                    </span>

                    <span class="faq-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6" fill="none">
                            <path d="M5.4143 5.56903C5.15802 5.78038 4.77983 5.76502 4.54093 5.52351L0.191105 1.12596C-0.0637018 0.86836 -0.0637018 0.450804 0.191105 0.193202C0.445913 -0.0644006 0.858938 -0.0644005 1.11374 0.193202L5.00225 4.12437L8.89076 0.193202C9.14557 -0.0643998 9.5586 -0.0643998 9.8134 0.193202C10.0682 0.450805 10.0682 0.868361 9.8134 1.12596L5.46357 5.52351L5.4143 5.56903Z" fill="#022257"/>
                        </svg>
                    </span>
                </button>

                <div
                    class="faq-content"
                    id="<?php echo esc_attr($item_id); ?>"
                    aria-labelledby="<?php echo esc_attr($item_id); ?>-button"
                >
                    <div class="faq-answer">
                        <?php echo wpautop(wp_kses_post($item['answer'])); ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>

<script type="application/ld+json">
<?php echo wp_json_encode($faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
</script>

<section id="faq" class="faq-section">
    <div class="container">
        <div class="head">
            <h2 class="title"><?php echo esc_html($faq_title); ?></h2>
            <span class="line"></span>

            <?php if ($faq_button_link !== '') : ?>
                <a href="<?php echo esc_url($faq_button_link); ?>" class="all-link hidden-mob">
                    <?php echo esc_html($faq_button_text); ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="faq-list">
            <div class="faq-column">
                <?php sofia_render_faq_column_items($left_items, $page_id, 'left'); ?>
            </div>

            <div class="faq-column">
                <?php sofia_render_faq_column_items($right_items, $page_id, 'right'); ?>
            </div>
        </div>

        <?php if ($faq_button_link !== '') : ?>
            <a href="<?php echo esc_url($faq_button_link); ?>" class="all-link hidden-dec">
                <?php echo esc_html($faq_button_text); ?>
            </a>
        <?php endif; ?>
    </div>
</section>