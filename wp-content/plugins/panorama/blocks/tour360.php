<?php

$tours = $get_value('tour_360', false, [] );

$items  = [];

if (is_array($tours)) {
    foreach ($tours as $key => $tour) {
        $items[] = [
            "tour_id" => $tour['tour_id'],
            "tour_img" =>  $tour['tour_img'],
            "tourTitleAuthor" => (bool) $tour['tourTitleAuthor'],
            "title" => $tour['title'],
            "author" => $tour['author'],
            "tour_hotSpot" => (bool) $tour['tour_hotSpot'],
            "hotSpot_txt" => $tour['hotSpot_txt'],
            "target_id" =>  $tour['target_id'],
            "default_data" => (bool) $tour['default_data']
        ];
    }
}


$block = [
    "blockName" => "panorama/tour",
    "attrs" =>  [
        "tour_360" => $items,
        "previewImgUrl" => $bppiv_meta['previewImgUrl'] ?? '',
        "loadButtonText" => $bppiv_meta['loadButtonText'] ?? 'Click to Load Panorama',
        "options" => [
            "isRotate" => $get_value('bppiv_auto_rotate', true, true),
            "autoRotateSpeed" => $bppiv_meta['bppiv_speed'] ?? 1,
            "autoRotateInactivityDelay" => $bppiv_meta['auto_rotate_inactivity_delay'] ?? 3000,
            "hideDefaultCtrl" => $get_value('control_show_hide', true),
            "initialView" => $get_value('initial_view', true),
            "initialViewPosition" => [
                "pitch" => (float)$get_value('initial_view_property', false, 0, 'top'),
                "yaw" => (float)$get_value('initial_view_property', false, 0, 'right'),
                "hfov" => (float)$get_value('initial_view_property', false, 0, 'bottom')
            ],
            "autoLoad" => $get_value('bppiv_auto_load', true, true),
            "draggable" => $get_value('draggable_360', true, true),
            "compass" => $get_value('compass_360', true),
            "mouseZoom" => $get_value('mouse_zoom', true, true) ,
            "disableKeyboardCtrl" =>  $get_value('disable_keyboard_ctrl', true) ,
            "doubleClickZoom" => $get_value('double_click_zoom', true, true) ,
            "isByline" => $get_value('tourShowByPrefix', true, true),
        ],
        "layout" => [
            "alignSl" => [
                "desktop" =>$bppiv_meta['bppiv_alignment'] ?? "center",
                "tablet" => $bppiv_meta['bppiv_alignment'] ?? "center",
                "mobile" => $bppiv_meta['bppiv_alignment'] ?? "center"
            ],
            "width" => [
                "desktop" => $bppiv_width,
                "tablet" => $bppiv_width,
                "mobile" => $bppiv_width
            ],
            "height" => [
                "desktop" => $bppiv_height,
                "tablet" => $bppiv_height,
                "mobile" => $bppiv_height
            ]
        ]
    ],
    "innerBlocks" => [],
    "innerHTML" => "",
    "innerContent" => []
];