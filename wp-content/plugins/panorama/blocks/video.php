<?php

$block = [
    "blockName" => "panorama/video",
    "attrs" => [
        "videoUrl" => $get_value('bppiv_video_src', false, 0, 'url'),
        "options" => [
            "autoplay" => $get_value('bppiv_auto_play', true),
            "loop" => $get_value('bppiv_video_loop', true, true),
            "muted" => $get_value('bppiv_video_mute', true),
            "controlBar" => $get_value('control_show_hide_video', true, true),
            "fullscreen" => $get_value('video_fullscreen_ctrl', true, true),
            "setting" => $get_value('video_setting_ctrl', true, true),
            "video" => $get_value('video_video_range_ctrl', true, true),
            "initialView" => $get_value('initial_view_video', true),
            "initialPosition" => [
                "x" => (float)$get_value('initial_view_video_property', false, 0, 'top'),
                "y" => (float)$get_value('initial_view_video_property', false, 0, 'right'),
                "z" => (float)$get_value('initial_view_video_property', false, 0, 'bottom')
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