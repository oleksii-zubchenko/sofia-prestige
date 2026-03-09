<?php

$block = [
    "blockName" => "panorama/google-street",
    "attrs" => [
        "panoId" => $get_value('bppiv_pano_id', false, "",),
        "options" => [
            "autoRotate" => $get_value('bppiv_auto_rotate', true, true),
            "autoRotateSpeed" => $bppiv_meta['bppiv_speed'] ?? 1,
            "autoRotateActivationDuration" => $bppiv_meta['auto_rotate_inactivity_delay'] ?? 3000,
            "hideDefaultCtrl" => $get_value('control_show_hide', true),
            "initialView" => $get_value('initial_view', true),
            "initialPosition" => [
                "x" => (float)$get_value('initial_view_image_property', false, 0, 'top'),
                "y" => (float)$get_value('initial_view_image_property', false, 0, 'right'),
                "z" => (float)$get_value('initial_view_image_property', false, 0, 'bottom')
            ]
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