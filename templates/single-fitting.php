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

$contentBootstrapClasses = '';
if(PluginHelper::getInstance()->hasSidebar('sidebar-fitting-manager')) {
    $contentBootstrapClasses = 'col-lg-9 col-md-9 col-sm-9 col-9 ';
}

defined('ABSPATH') or die();

\get_header();
?>

<div class="container main template-single-fitting">
    <div class="main-content clearfix">
        <div class="<?php echo $contentBootstrapClasses; ?>content-wrapper">
            <div class="content content-inner content-single content-fitting">
                <?php
                if(\have_posts()) {
                    while(\have_posts()) {
                        \the_post();
                        TemplateHelper::getInstance()->getTemplate('content-single-fitting');
                    }
                }
                ?>
            </div> <!-- /.content -->
        </div> <!-- /.col-lg-9 /.col-md-9 /.col-sm-9 /.col-9 -->
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
    </div> <!-- /.row -->
</div> <!-- /.container -->

<?php
\get_footer();
