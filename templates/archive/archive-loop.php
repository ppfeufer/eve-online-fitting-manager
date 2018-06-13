<?php

$subDoctrines = \get_terms([
    'taxonomy' => $taxonomy,
    'orderby' => 'name',
    'order' => 'ASC',
    'child_of' => $doctrineData->term_id
]);

$resultMainDoctrine = new \WP_Query([
    'post_type' => 'fitting',
    'orderby' => 'name',
    'order' => 'ASC',
    'posts_per_page' => -1,
    'tax_query' => [
        [
            'taxonomy' => $taxonomy,
            'field' => 'id',
            'terms' => $doctrineData->term_id,
            'include_children' => false
        ]
    ]
]);

// loop through the main doctrine
if($resultMainDoctrine->have_posts()) {
    if(\get_post_type() === 'fitting') {
        $uniqueID = \uniqid();

        if(\count($subDoctrines) > 0) {
            echo '<header class="entry-header header-doctrine"><h2 class="entry-title header-subdoctrine">' . \__('Main Line Doctrine Ships', 'eve-online-fitting-manager') . '</h2></header>';
        } // if(\count($subDoctrines) > 0)

        echo '<div class="gallery-row row">';
        echo '<ul class="bootstrap-post-loop-fittings bootstrap-post-loop-fittings-' . $uniqueID . ' clearfix">';
    } // if(\get_post_type() === 'fitting')

    while($resultMainDoctrine->have_posts()) {
        $resultMainDoctrine->the_post();

        if(\get_post_type() === 'fitting') {
            echo '<li>';
        } // if(\get_post_type() === 'fitting')

        \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('content-fitting');

        if(\get_post_type() === 'fitting') {
            echo '</li>';
        } // if(\get_post_type() === 'fitting')
    } // while($resultMainDoctrine->have_posts())

    \wp_reset_postdata();

    if(\get_post_type() === 'fitting') {
        echo '</ul>';
        echo '</div>';

        echo '<script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery("ul.bootstrap-post-loop-fittings-' . $uniqueID . '").bootstrapGallery({
                        "classes" : "' . \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\PluginHelper::getLoopContentClasses() . '",
                        "hasModal" : false
                    });
                });
                </script>';
    } // if(\get_post_type() === 'fitting')
} // if($resultMainDoctrine->have_posts())

// Loop throgh the sub doctrines ...
if(\count($subDoctrines) > 0) {
    foreach($subDoctrines as $subDoctrine) {
        $resultSubDoctrine = new \WP_Query([
            'post_type' => 'fitting',
            'orderby' => 'name',
            'order' => 'ASC',
            'posts_per_page' => -1,
            'tax_query' => [
                [
                    'taxonomy' => $taxonomy,
                    'field' => 'id',
                    'terms' => $subDoctrine->term_id,
                    'include_children' => false
                ]
            ]
        ]);

        if($resultSubDoctrine->have_posts()) {
            if(\get_post_type() === 'fitting') {
                $uniqueID = \uniqid();

                echo '<header class="entry-header header-doctrine"><h2 class="entry-title header-subdoctrine">' . $subDoctrine->name . '</h2></header>';
                echo '<div class="gallery-row row">';
                echo '<ul class="bootstrap-post-loop-fittings bootstrap-post-loop-fittings-' . $uniqueID . ' clearfix">';
            } // if(\get_post_type() === 'fitting')

            while($resultSubDoctrine->have_posts()) {
                $resultSubDoctrine->the_post();

                if(\get_post_type() === 'fitting') {
                    echo '<li>';
                } // if(\get_post_type() === 'fitting')

                \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('content-fitting');

                if(\get_post_type() === 'fitting') {
                    echo '</li>';
                } // if(\get_post_type() === 'fitting')
            } // while($resultSubDoctrine->have_posts())

            \wp_reset_postdata();

            if(\get_post_type() === 'fitting') {
                echo '</ul>';
                echo '</div>';

                echo '<script type="text/javascript">
                        jQuery(document).ready(function() {
                            jQuery("ul.bootstrap-post-loop-fittings-' . $uniqueID . '").bootstrapGallery({
                                "classes" : "' . \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\PluginHelper::getLoopContentClasses() . '",
                                "hasModal" : false
                            });
                        });
                        </script>';
            } // if(\get_post_type() === 'fitting')
        } // if($resultSubDoctrine->have_posts())
    } // foreach($subDoctrines as $subDoctrine)
} // if(\count($subDoctrines) > 0)
