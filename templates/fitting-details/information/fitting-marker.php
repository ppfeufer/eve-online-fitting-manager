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

$isConcept = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_is_concept', true);
$isIdea = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_is_idea', true);
$isUnderDiscussion = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_is_under_discussion', true);

if($isConcept === '1' || $isIdea === '1' || $isUnderDiscussion === '1') {
    ?>
    <div class="bs-callout bs-callout-warning">
        <p class="small">
            <?php
            echo __('This fitting is marked as:', 'eve-online-fitting-manager');

            if($isConcept === '1') {
                echo '<br>» ' . __('Conceptual Fitting', 'eve-online-fitting-manager');
            }

            if($isIdea === '1') {
                echo '<br>» ' . __('Fitting Idea', 'eve-online-fitting-manager');
            }

            if($isUnderDiscussion === '1') {
                echo '<br>» ' . __('Under Discussion', 'eve-online-fitting-manager');
            }
            ?>
        </p>
        <p class="small">
            <?php echo __('This means, this fitting might not be a part of any official doctrine and/or is still under discussion.', 'eve-online-fitting-manager'); ?>
        </p>
    </div>
    <?php
}
