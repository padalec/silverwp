<?php
/**
 * image configuration file
 */
$image_sizes = array(
    'default' => array(
        'thumbnail' => array( // list view, container-grid-4
            'width'  => 360, // proporcja (1/3 grid)
            'height' => 182,
            'crop'   => true,
        ),
        'medium'    => array( // full width with sidebar, container-grid-9
            'width'  => 823, // z widoku listy wpisow bloga, proporcja: 2,284210526315789
            'height' => 360, // (~3/4 grid) =2/3 750x380
            'crop'   => true,
        ),
        'large'     => array( // full container width, container-grid-12
            'width'  => 1140, // proporcja
            'height' => 578,  // (1/1 grid)
            'crop'   => true,
        ),
        'post-thumbnail' => array(
            'width'  => 76, // wartosc z szablonu
            'height' => 76,
            'crop'   => true,
        )
    ),
    'custom' => array(
        // add image for all post type
        'merge-grid-1/3' => array( // thumbnail size
            'width'  => 380,
            'height' => 192,
            'crop'   => true,
        ),
        'grid-1/2' => array( // thumbnail size
            'width'  => 555,
            'height' => 281,
            'crop'   => true,
        ),
        'grid-1/4' => array( // thumbnail size
            'width'  => 263,
            'height' => 133,
            'crop'   => true,
        ),
        'masonry' => array( // thumbnail size
            'width'  => 360,
            'height' => 1000,
            'crop'   => false,
        ),
        'square' => array( // related projects - slider
            'width'  => 170,
            'height' => 170,
            'crop'   => true,
        ),
    )
);
return $image_sizes;
