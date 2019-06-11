<?php

/*
 * Copyright (C) 2017 ppfeufer
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\PluginHelper;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\TemplateHelper;

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
        }

        echo '<div class="gallery-row row">';
        echo '<ul class="bootstrap-post-loop-fittings bootstrap-post-loop-fittings-' . $uniqueID . ' clearfix">';
    }

    while($resultMainDoctrine->have_posts()) {
        $resultMainDoctrine->the_post();

        if(\get_post_type() === 'fitting') {
            echo '<li>';
        }

        TemplateHelper::getInstance()->getTemplate('content-fitting');

        if(\get_post_type() === 'fitting') {
            echo '</li>';
        }
    }

    \wp_reset_postdata();

    if(\get_post_type() === 'fitting') {
        echo '</ul>';
        echo '</div>';

        echo '<script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery("ul.bootstrap-post-loop-fittings-' . $uniqueID . '").bootstrapGallery({
                        "classes" : "' . PluginHelper::getInstance()->getLoopContentClasses() . '",
                        "hasModal" : false
                    });
                });
                </script>';
    }
}

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
            }

            while($resultSubDoctrine->have_posts()) {
                $resultSubDoctrine->the_post();

                if(\get_post_type() === 'fitting') {
                    echo '<li>';
                }

                TemplateHelper::getInstance()->getTemplate('content-fitting');

                if(\get_post_type() === 'fitting') {
                    echo '</li>';
                }
            }

            \wp_reset_postdata();

            if(\get_post_type() === 'fitting') {
                echo '</ul>';
                echo '</div>';

                echo '<script type="text/javascript">
                        jQuery(document).ready(function() {
                            jQuery("ul.bootstrap-post-loop-fittings-' . $uniqueID . '").bootstrapGallery({
                                "classes" : "' . PluginHelper::getInstance()->getLoopContentClasses() . '",
                                "hasModal" : false
                            });
                        });
                        </script>';
            }
        }
    }
}
