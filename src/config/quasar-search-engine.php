<?php

return [
    'mode'                          => 'NATURAL_LANGUAGE',
    'min_search_length'             => 3,
    'min_fulltext_search_length'    => 4,
    'min_fulltext_search_fallback'  => 'LIKE',
    'index_columns'                 => ['url', 'title', 'content_layer_1', 'content_layer_2', 'content_layer_3']
];