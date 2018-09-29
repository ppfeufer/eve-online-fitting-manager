<?php
defined('ABSPATH') or die();

\get_header();

$taxonomy = 'fitting-ships';
$doctrineData = \get_queried_object();
?>

<div class="container main template-archive-ship" data-doctrine="<?php echo $doctrineData->slug; ?>">
    <div class="main-content clearfix">
        <div class="<?php echo \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\PluginHelper::getMainContentColClasses(); ?>">
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

                \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('archive/archive-loop', [
                    'taxonomy' => $taxonomy,
                    'doctrineData' => $doctrineData
                ]);
                ?>
            </div> <!-- /.content -->
        </div> <!-- /.col -->

        <?php
        if(\WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\PluginHelper::hasSidebar('sidebar-fitting-manager')) {
            ?>
            <div class="col-lg-3 col-md-3 col-sm-3 col-3 sidebar-wrapper">
                <?php
                \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('sidebar-fitting-manager');
                ?>
            </div><!--/.col -->
            <?php
        }
        ?>
    </div> <!--/.row -->
</div><!-- container -->

<?php
\get_footer();
