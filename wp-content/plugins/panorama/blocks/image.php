<?php

$block = [
    "blockName" => "panorama/image-3d",
    "attrs" => [
        "imageUrl" => $get_value('bppiv_image_src', false, 0, 'url'),
        "options" => [
            "autoRotate" => $get_value('bppiv_auto_rotate', true, true),
            "autoRotateSpeed" => $bppiv_meta['bppiv_speed'] ?? 1,
            "autoRotateInactivityDelay" => $bppiv_meta['auto_rotate_inactivity_delay'] ?? 3000,
            "hideDefaultCtrl" => $get_value('control_show_hide', true),
            "initialView" => $get_value('initial_view', true),
            "initialPosition" => [
                "x" => (float)$get_value('initial_view_image_property', false, 0, 'top'),
                "y" => (float)$get_value('initial_view_image_property', false, 0, 'right'),
                "z" => (float)$get_value('initial_view_image_property', false, 0, 'bottom')
            ],
            "isDeviceMotion" => $get_value('is_motion_button', true)
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
            ],
            "buttonColors" => [
                "color" =>  $bppiv_meta['motion_button_text_color'] ?? '',
                "bg" =>  $bppiv_meta['motion_button_btn_bg'] ?? ''
            ],
            "buttonHoverColors" => [
                "color" =>  $bppiv_meta['hover_motion_button_text_color'] ?? '',
                "bg" =>  $bppiv_meta['hover_motion_button_btn_bg'] ?? ''
            ]
        ]
    ],
    "innerBlocks" => [],
    "innerHTML" => "",
    "innerContent" => []
];