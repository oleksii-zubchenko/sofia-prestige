<?php

$galleries = $get_value('bppiv_pan_gallery', false, [] );

$items  = [];

foreach ($galleries as $key => $gallery) {
    $items[] = [
        "img" => $gallery['panoramic_img']['url'],
        "isSetVideo" => (bool) $gallery['gal_type_cheek'],
        "video" => $gallery['gal_type_video']['url']
    ];
}

$block = [
    "blockName" => "panorama/gallery",
    "attrs" =>  [
        "galleries" => $items,
        "galleryLimit" => (int)$get_value('bppiv_gallery_limit', false),
        "column" => $get_value('bppiv_gallery_column', false),
        "gap" => $get_value('bppiv_gallery_column_gap', false, 'width'),
        "gap" =>  $bppiv_meta['bppiv_gallery_column_gap']['width'] . 'px' ,
        "loadMoreBtn" => [
            "text" => $get_value('loadMore_btn_text', false),
            "colors" => [
                "color" => $get_value('loadMore_text_color', false),
                 "bg" => $get_value('loadMore_btn_bg', false),
            ],
            "hoverColors" => [
                "color" => $get_value('loadMore_text_hover_color', false),
                 "bg" => $get_value('loadMore_hover_bg', false),
            ]
        ]
    ],
    "innerBlocks" => [],
    "innerHTML" => "",
    "innerContent" => []
];