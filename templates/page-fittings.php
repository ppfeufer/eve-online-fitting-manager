<?php

/**
 * Template Name: Fittings
 */

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

use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\FittingHelper;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\PluginHelper;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\TemplateHelper;

\get_header();
?>

<div class="container main template-page-fittings">
    <?php
    if(\have_posts()) {
        while(\have_posts()) {
            \the_post();
            ?>
            <!--<div class="row main-content">-->
            <div class="main-content clearfix">
                <div class="<?php echo PluginHelper::getInstance()->getMainContentColClasses(); ?> content-wrapper">
                    <div class="content content-inner content-full-width content-page doctrine-fittings">
                        <header>
                            <?php
                            if(\is_front_page()) {
                                ?>
                                <h1><?php echo \get_bloginfo('name'); ?></h1>
                                <?php
                            } else {
                                ?>
                                <h1><?php \the_title(); ?></h1>
                                <?php
                            }
                            ?>
                        </header>
                        <article class="post clearfix" id="post-<?php \the_ID(); ?>">
                            <?php
                            if(!empty(\get_query_var('fitting_search'))) {
                                $query = FittingHelper::getInstance()->searchFittings();

                                if($query->have_posts()) {
                                    $uniqueID = \uniqid();

                                    echo '<div class="gallery-row row">';
                                    echo '<ul class="bootstrap-post-loop-fittings bootstrap-post-loop-fittings-' . $uniqueID . ' clearfix">';

                                    while($query->have_posts()) {
                                        $query->the_post();

                                        echo '<li>';
                                        TemplateHelper::getInstance()->getTemplate('content-fitting');
                                        echo '</li>';
                                    }

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
                            } else {
                                echo \the_content();
                            }
                            ?>
                        </article>
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
                } // END if(\WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\PluginHelper::hasSidebar('sidebar-fitting-manager'))
                ?>
            </div> <!--/.row -->
            <?php
        }
    }
    ?>
</div><!-- /.container -->

<?php
\get_footer();
