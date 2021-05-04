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


$fleetRoles = get_terms([
    'taxonomy' => 'fitting-fleet-roles',
    'orderby' => 'name',
    'order' => 'ASC',
]);

/**
 * Get all ships with a fleet role assigned that are in this doctrine
 */
if(\count($fleetRoles) > 0) {
    $fittingWithFleetRoleId = [];

    foreach($fleetRoles as $fleetRole) {
        $resultWithFleetRoles = new \WP_Query([
            'post_type' => 'fitting',
            'orderby' => 'name',
            'order' => 'ASC',
            'posts_per_page' => -1,
            'tax_query' => [
                'relation' => 'AND',
                // current doctrine
                [
                    'taxonomy' => $taxonomy,
                    'field' => 'id',
                    'terms' => $doctrineData->term_id,
                    'include_children' => true
                ],

                // current fleet role
                [
                    'taxonomy' => 'fitting-fleet-roles',
                    'field' => 'id',
                    'terms' => $fleetRole->term_id,
                    'include_children' => false
                ]
            ]
        ]);

        if($resultWithFleetRoles->have_posts()) {
            if(get_post_type() === 'fitting') {
                $uniqueID = uniqid();

                echo '<header class="entry-header header-doctrine"><h2 class="entry-title header-subdoctrine">' . $fleetRole->name . '</h2></header>';
                echo '<div class="gallery-row row">';
                echo '<ul class="bootstrap-post-loop-fittings bootstrap-post-loop-fittings-' . $uniqueID . ' clearfix">';
            }


            while($resultWithFleetRoles->have_posts()) {
                $resultWithFleetRoles->the_post();

                if(get_post_type() === 'fitting') {
                    echo '<li>';
                }

                TemplateHelper::getInstance()->getTemplate('content-fitting');

                if(get_post_type() === 'fitting') {
                    echo '</li>';
                }

                $fittingWithFleetRoleId[] = get_the_ID();
            }

            wp_reset_postdata();

            if(get_post_type() === 'fitting') {
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

/**
 * Get all ships that don't have a dedicated fleet role assigned in this doctrine
 */
$resultWithoutFleetRoles = new \WP_Query([
    'post_type' => 'fitting',
    'orderby' => 'name',
    'order' => 'ASC',
    'posts_per_page' => -1,
    'post__not_in' => $fittingWithFleetRoleId,
    'tax_query' => [
        [
            'taxonomy' => $taxonomy,
            'field' => 'id',
            'terms' => $doctrineData->term_id,
            'include_children' => true
        ],
    ]
]);

// loop through the main doctrine
if($resultWithoutFleetRoles->have_posts()) {
    if(get_post_type() === 'fitting') {
        $uniqueID = uniqid();

        echo '<header class="entry-header header-doctrine"><h2 class="entry-title header-subdoctrine">' . __('No dedicated fleet role assigned to these ships', 'eve-online-fitting-manager') . '</h2></header>';
        echo '<div class="gallery-row row">';
        echo '<ul class="bootstrap-post-loop-fittings bootstrap-post-loop-fittings-' . $uniqueID . ' clearfix">';
    }

    while($resultWithoutFleetRoles->have_posts()) {
        $resultWithoutFleetRoles->the_post();

        if(get_post_type() === 'fitting') {
            echo '<li>';
        }

        TemplateHelper::getInstance()->getTemplate('content-fitting');

        if(get_post_type() === 'fitting') {
            echo '</li>';
        }
    }

    wp_reset_postdata();

    if(get_post_type() === 'fitting') {
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
