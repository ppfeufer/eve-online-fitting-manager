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

use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\ {
    PluginHelper,
    TemplateHelper
};

defined('ABSPATH') or die();

\get_header();

$taxonomy = 'fitting-ships';
$doctrineData = \get_queried_object();
?>

<div class="container main template-archive-ship" data-doctrine="<?php echo $doctrineData->slug; ?>">
    <div class="main-content clearfix">
        <div class="<?php echo PluginHelper::getInstance()->getMainContentColClasses(); ?>">
            <div class="content content-archive doctrine-list">
                <header class="page-title">
                    <h2>
                        <?php echo \__('Ship:', 'eve-online-fitting-manager') . ' ' . $doctrineData->name; ?>
                    </h2>
                </header>

                <?php
                // Show an optional category description
                if(!empty($doctrineData->description)) {
                    echo \apply_filters('category_archive_meta', '<div class="category-archive-meta">' . \do_shortcode(\wpautop($doctrineData->description)) . '</div>');
                }

                TemplateHelper::getInstance()->getTemplate('archive/archive-loop', [
                    'taxonomy' => $taxonomy,
                    'doctrineData' => $doctrineData
                ]);
                ?>
            </div> <!-- /.content -->
        </div> <!-- /.col -->

        <?php
        if(PluginHelper::getInstance()->hasSidebar('sidebar-fitting-manager')) {
            ?>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3 sidebar-wrapper">
                <?php
                TemplateHelper::getInstance()->getTemplate('sidebar-fitting-manager');
                ?>
            </div><!--/.col -->
            <?php
        }
        ?>
    </div> <!--/.row -->
</div><!-- container -->

<?php
\get_footer();
