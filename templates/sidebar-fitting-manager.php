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

defined('ABSPATH') or die();
?>

<section class="sidebar-fitting-manager">
    <?php
    if(\function_exists('dynamic_sidebar')) {
        dynamic_sidebar('sidebar-fitting-manager');
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(function() {
                var doctrineSlug = $('.container.main').data('doctrine');
                var currentDoctrine = $('.sidebar-doctrine-list').find('[data-doctrine="' + doctrineSlug + '"]');

                currentDoctrine.addClass('doctrine-current');
                currentDoctrine.parent().parent().addClass('doctrine-active');
                currentDoctrine.parent().parent().parent().parent().addClass('doctrine-active');
            });
        });
        </script>
        <?php
    }
    ?>
</section>
