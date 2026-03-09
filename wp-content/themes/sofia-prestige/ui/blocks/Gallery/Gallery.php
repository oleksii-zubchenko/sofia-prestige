<?php
$flags_file = __DIR__ . '/flags.json';
$flags = [];

if (is_readable($flags_file)) {
    $flags_raw = file_get_contents($flags_file);

    if ($flags_raw !== false) {
        $decoded = json_decode($flags_raw, true);

        if (json_last_error() === JSON_ERROR_NONE && !empty($decoded['flags']) && is_array($decoded['flags'])) {
            $flags = $decoded['flags'];
        }
    }
}

$lang_slug = function_exists('pll_current_language') ? pll_current_language('slug') : '';
$name_key = $lang_slug === 'en' ? 'eng' : 'ukr';

$svg_allowed_tags = [
    'svg' => [
        'xmlns' => true,
        'width' => true,
        'height' => true,
        'viewbox' => true,
        'fill' => true,
        'stroke' => true,
        'role' => true,
        'aria-hidden' => true,
        'focusable' => true,
    ],
    'path' => [
        'd' => true,
        'fill' => true,
        'stroke' => true,
        'stroke-width' => true,
        'stroke-linecap' => true,
        'stroke-linejoin' => true,
        'fill-rule' => true,
        'clip-rule' => true,
        'opacity' => true,
        'transform' => true,
    ],
    'rect' => [
        'x' => true,
        'y' => true,
        'width' => true,
        'height' => true,
        'rx' => true,
        'ry' => true,
        'fill' => true,
        'stroke' => true,
        'stroke-width' => true,
        'opacity' => true,
    ],
    'circle' => [
        'cx' => true,
        'cy' => true,
        'r' => true,
        'fill' => true,
        'stroke' => true,
        'stroke-width' => true,
        'opacity' => true,
    ],
    'ellipse' => [
        'cx' => true,
        'cy' => true,
        'rx' => true,
        'ry' => true,
        'fill' => true,
        'stroke' => true,
        'stroke-width' => true,
        'opacity' => true,
    ],
    'g' => [
        'fill' => true,
        'stroke' => true,
        'stroke-width' => true,
        'opacity' => true,
        'transform' => true,
        'clip-path' => true,
        'mask' => true,
    ],
    'defs' => [],
    'clippath' => [
        'id' => true,
    ],
    'lineargradient' => [
        'id' => true,
        'x1' => true,
        'x2' => true,
        'y1' => true,
        'y2' => true,
        'gradientunits' => true,
    ],
    'radialgradient' => [
        'id' => true,
        'cx' => true,
        'cy' => true,
        'r' => true,
        'fx' => true,
        'fy' => true,
        'gradientunits' => true,
    ],
    'stop' => [
        'offset' => true,
        'stop-color' => true,
        'stop-opacity' => true,
    ],
    'mask' => [
        'id' => true,
        'x' => true,
        'y' => true,
        'width' => true,
        'height' => true,
        'maskunits' => true,
    ],
    'use' => [
        'href' => true,
        'xlink:href' => true,
    ],
];
?>

<section class="gallerySection">
    <header class="galleryHeader">
        <div class="cardsTypes">
            <button type="button" class="cardsType active">Продаж</button>
            <button type="button" class="cardsType">Оренда</button>
        </div>
        <div class="itemFlags">
            <?php foreach ($flags as $index => $flag):
                $name_ukr = isset($flag['name']['ukr']) ? (string) $flag['name']['ukr'] : '';
                $name_eng = isset($flag['name']['eng']) ? (string) $flag['name']['eng'] : '';
                $flag_name = $name_key === 'eng' ? $name_eng : $name_ukr;

                if ($flag_name === '') {
                    $flag_name = $name_ukr !== '' ? $name_ukr : $name_eng;
                }

                $flag_svg = isset($flag['svg']) ? (string) $flag['svg'] : '';
                $flag_key = isset($flag['key']) ? sanitize_title((string) $flag['key']) : '';

                if ($flag_key === '') {
                    $flag_key = sanitize_title($flag_name);
                }

                if ($flag_key === '') {
                    $flag_key = 'flag-' . $index;
                }

                $classes = 'itemFlag' . ($index === 0 ? ' is-active' : '');
                ?>
                <button class="<?php echo esc_attr($classes); ?>" type="button"
                    data-flag="<?php echo esc_attr($flag_key); ?>">
                    <div class="svgContainer">
                        <?php echo wp_kses($flag_svg, $svg_allowed_tags); ?>
                    </div>
                    <span><?php echo esc_html($flag_name); ?></span>
                    <span class="qtyCounter">50</span>
                </button>
            <?php endforeach; ?>
        </div>
        <div class="mobileFiltersButtons">
            <button class="searchLink">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path
                        d="M6.99967 12.6668C10.1293 12.6668 12.6663 10.1298 12.6663 7.00016C12.6663 3.87056 10.1293 1.3335 6.99967 1.3335C3.87007 1.3335 1.33301 3.87056 1.33301 7.00016C1.33301 10.1298 3.87007 12.6668 6.99967 12.6668Z"
                        stroke="#003285" stroke-width="1.33333" stroke-linejoin="round" />
                    <path d="M11.0737 11.0737L13.9022 13.9022" stroke="#003285" stroke-width="1.33333"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
            <button class="filtersButtonMobile">
                <span>Фільтри</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="16" viewBox="0 0 13 16" fill="none">
                    <path
                        d="M12.4867 2.96084L5.79761 2.96084C5.51434 2.96084 5.2847 2.73315 5.2847 2.45229V2.45193C5.2847 2.17107 5.51434 1.94339 5.79761 1.94339L12.4867 1.94339C12.77 1.94339 12.9996 2.17107 12.9996 2.45193V2.45229C12.9996 2.73315 12.77 2.96084 12.4867 2.96084Z"
                        fill="#022257" />
                    <path
                        d="M12.4871 14.0566L5.74548 14.0566C5.46221 14.0566 5.23258 13.8289 5.23258 13.5481V13.5477C5.23258 13.2668 5.46221 13.0392 5.74548 13.0392L12.4871 13.0392C12.7704 13.0392 13 13.2668 13 13.5477V13.5481C13 13.8289 12.7704 14.0566 12.4871 14.0566Z"
                        fill="#022257" />
                    <path
                        d="M8.45914 7.95442C8.45914 7.67341 8.68899 7.44588 8.97204 7.44588H12.4867C12.7702 7.44588 12.9996 7.67377 12.9996 7.95442C12.9996 8.23507 12.7698 8.46297 12.4867 8.46297L8.97204 8.46297C8.68863 8.46297 8.45914 8.23507 8.45914 7.95442Z"
                        fill="#022257" />
                    <path
                        d="M3.26095 15.4915L3.26095 14.0566H0.512905C0.229486 14.0566 0 13.8287 0 13.5481C0 13.2671 0.229848 13.0395 0.512905 13.0395H3.26095V11.6047C3.26095 11.3237 3.4908 11.0961 3.77385 11.0961C4.05691 11.0961 4.28676 11.324 4.28676 11.6047L4.28676 15.4915C4.28676 15.7725 4.05691 16 3.77385 16C3.4908 16 3.26095 15.7721 3.26095 15.4915Z"
                        fill="#022257" />
                    <path
                        d="M6.54181 9.89781V8.46297H0.512905C0.229486 8.46297 0 8.23507 0 7.95442C0 7.67377 0.229848 7.44588 0.512905 7.44588H6.54181V6.01104C6.54181 5.73003 6.77166 5.50249 7.05471 5.50249C7.33777 5.50249 7.56762 5.73039 7.56762 6.01104V9.89781C7.56762 10.1788 7.33777 10.4064 7.05471 10.4064C6.77166 10.4064 6.54181 10.1785 6.54181 9.89781Z"
                        fill="#022257" />
                    <path
                        d="M3.26095 4.39532L3.26095 2.96048H0.512905C0.229486 2.96048 0 2.73258 0 2.45193C0 2.17092 0.229848 1.94339 0.512905 1.94339H3.26095V0.508547C3.26095 0.227537 3.4908 9.53674e-07 3.77385 9.53674e-07C4.05691 9.53674e-07 4.28676 0.227896 4.28676 0.508547V4.39532C4.28676 4.67633 4.05691 4.90386 3.77385 4.90386C3.4908 4.90386 3.26095 4.67597 3.26095 4.39532Z"
                        fill="#022257" />
                </svg>
            </button>
        </div>
    </header>
    <div class="mainGallery">
        <aside class="sidebar">
            <div class="filters">
                <div class="searchFull">
                    <input type="text" class="searchInput" placeholder="Пошук по ID чи слову...">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path
                            d="M6.99992 12.6668C10.1295 12.6668 12.6666 10.1298 12.6666 7.00016C12.6666 3.87056 10.1295 1.3335 6.99992 1.3335C3.87032 1.3335 1.33325 3.87056 1.33325 7.00016C1.33325 10.1298 3.87032 12.6668 6.99992 12.6668Z"
                            stroke="#003285" stroke-width="1.33333" stroke-linejoin="round" />
                        <path d="M11.0739 11.0737L13.9023 13.9022" stroke="#003285" stroke-width="1.33333"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="silgleFilter">
                    <header class="filterHeader">
                        <span>Тип нерухомості</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6" fill="none">
                            <path
                                d="M5.4143 5.56903C5.15802 5.78038 4.77983 5.76502 4.54093 5.52351L0.191105 1.12596C-0.0637018 0.86836 -0.0637018 0.450804 0.191105 0.193202C0.445913 -0.0644006 0.858938 -0.0644005 1.11374 0.193202L5.00225 4.12437L8.89076 0.193202C9.14557 -0.0643998 9.5586 -0.0643998 9.8134 0.193202C10.0682 0.450805 10.0682 0.868361 9.8134 1.12596L5.46357 5.52351L5.4143 5.56903Z"
                                fill="#2680F2" />
                        </svg>
                    </header>
                    <div class="filterCOntent">
                        <button class="filterButton active">квартири</button>
                        <button class="filterButton">будинки</button>
                        <button class="filterButton">ділянки</button>
                        <button class="filterButton">гаражі</button>
                        <button class="filterButton">комерційне</button>
                        <button class="filterButton">готелі</button>
                        <button class="filterButton">виробництва</button>
                        <button class="filterButton">бізнес</button>
                        <button class="filterButton">інші</button>
                    </div>
                </div>
                <div class="locationFull">
                    <input type="text" class="locationFullInput" placeholder="Київ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="4" viewBox="0 0 17 4" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M2.02378 0C3.14151 0 4.04765 0.895638 4.04765 2.00018C4.04765 3.10471 3.14151 4 2.02378 4C0.906044 4 0 3.10471 0 2.00018C0 0.895638 0.906044 0 2.02378 0Z"
                            fill="#2680F2" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.49995 0C9.61769 0 10.5238 0.895638 10.5238 2.00018C10.5238 3.10471 9.61769 4 8.49995 4C7.38222 4 6.47618 3.10471 6.47618 2.00018C6.47618 0.895638 7.38222 0 8.49995 0Z"
                            fill="#2680F2" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M14.9761 0C16.0939 0 17 0.895638 17 2.00018C17 3.10471 16.0939 4 14.9761 4C13.8585 4 12.9524 3.10471 12.9524 2.00018C12.9524 0.895638 13.8585 0 14.9761 0Z"
                            fill="#2680F2" />
                    </svg>
                </div>
                <div class="silgleFilter">
                    <header class="filterHeader">
                        <span>Ціна</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6" fill="none">
                            <path
                                d="M5.4143 5.56903C5.15802 5.78038 4.77983 5.76502 4.54093 5.52351L0.191105 1.12596C-0.0637018 0.86836 -0.0637018 0.450804 0.191105 0.193202C0.445913 -0.0644006 0.858938 -0.0644005 1.11374 0.193202L5.00225 4.12437L8.89076 0.193202C9.14557 -0.0643998 9.5586 -0.0643998 9.8134 0.193202C10.0682 0.450805 10.0682 0.868361 9.8134 1.12596L5.46357 5.52351L5.4143 5.56903Z"
                                fill="#2680F2" />
                        </svg>
                    </header>
                    <div class="filterCOntent">
                        <div class="currencySelect">
                            <button class="currency active">
                                $
                            </button>
                            <button class="currency">
                                ₴
                            </button>
                            <button class="currency">
                                €
                            </button>
                        </div>
                        <div class="rangeContainer">
                            <input type="text" class="fromPrice" placeholder="30 000">
                            <span>-</span>
                            <input type="text" class="toPrice" placeholder="500 000">
                        </div>
                        <div class="slider">
                            <input type="range" min="0" max="100" value="50" />
                            <div class="slider__points">
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="additionalButton">
                    <span>Додаткові параметри</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="4" viewBox="0 0 17 4" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M2.02378 0C3.14151 0 4.04765 0.895638 4.04765 2.00018C4.04765 3.10471 3.14151 4 2.02378 4C0.906044 4 0 3.10471 0 2.00018C0 0.895638 0.906044 0 2.02378 0Z"
                            fill="#2680F2" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.49995 0C9.61769 0 10.5238 0.895638 10.5238 2.00018C10.5238 3.10471 9.61769 4 8.49995 4C7.38222 4 6.47618 3.10471 6.47618 2.00018C6.47618 0.895638 7.38222 0 8.49995 0Z"
                            fill="#2680F2" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M14.9761 0C16.0939 0 17 0.895638 17 2.00018C17 3.10471 16.0939 4 14.9761 4C13.8585 4 12.9524 3.10471 12.9524 2.00018C12.9524 0.895638 13.8585 0 14.9761 0Z"
                            fill="#2680F2" />
                    </svg>
                </button>
            </div>
        </aside>
    </div>
</section>