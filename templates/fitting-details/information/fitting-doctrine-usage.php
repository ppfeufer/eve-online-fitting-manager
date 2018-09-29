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

$usedInDoctrines = \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\FittingHelper::getShipUsedInDoctrine();
?>

<div class="fitting-used-in">
    <h4>
        <?php echo \__('Doctrines using this fitting', 'eve-online-fitting-manager'); ?>
    </h4>
    <?php
    $fittingUsedInHtml = '<div class="bs-callout bs-callout-info">';
    $fittingUsedInHtml .= '<p class="small">';
    $fittingUsedInHtml .= \__('This fitting is currently not used in any doctrine.', 'eve-online-fitting-manager');
    $fittingUsedInHtml .= '</p>';
    $fittingUsedInHtml .= '</div>';

    if(!empty($usedInDoctrines) && !\is_wp_error($usedInDoctrines)) {
        $fittingUsedInHtml = '<ul class="fitting-used-in-doctrines">';

        foreach($usedInDoctrines as $doctrine) {
            $fittingUsedInHtml .= '<li>» <a href="' . \get_term_link($doctrine) . '">' . $doctrine->name . '</a></li>';
        }

        $fittingUsedInHtml .= '</ul>';
    }

    echo $fittingUsedInHtml;
    ?>
</div>
