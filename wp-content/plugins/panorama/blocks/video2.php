<?php

$block = [
    "blockName" => "panorama/video-360",
    "attrs" => [
        "videoUrl" =>$get_value('bppiv_video_src', false, 0, 'url'),
        "options" => [
            "autoplay" => $get_value('bppiv_video_autoplay', true, true),
            "loop" => $get_value('bppiv_video_loop', true, true),
            "play" => $get_value('video_play_pause_ctrl', true, true),
            "progress" => $get_value('video_progress_ctrl', true, true),
            "volume" => $get_value('video_volume_ctrl', true, true),
            "remainingTime" => $get_value('video_remaining_time_ctrl', true),
            "pip" => $get_value('video_pip_ctrl', true),
            "fullscreen" => $get_value('video2_fullscreen_ctrl', true),
            "playbackSpeed" => $get_value('video_playback_speed_ctrl', true),
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
            ]
        ]
    ],
    "innerBlocks" => [],
    "innerHTML" => "",
    "innerContent" => []
];