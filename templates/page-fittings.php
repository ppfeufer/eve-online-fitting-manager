<?php
/**
 * Template Name: Fittings
 */
get_header();
?>

<div class="container main template-page-fittings">
    <?php
    if(\have_posts()) {
        while(\have_posts()) {
            \the_post();
            ?>
            <!--<div class="row main-content">-->
            <div class="main-content clearfix">
                <div class="<?php echo \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\PluginHelper::getMainContentColClasses(); ?> content-wrapper">
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
                            } // END if(\is_front_page())
                            ?>
                        </header>
                        <article class="post clearfix" id="post-<?php \the_ID(); ?>">
                            <?php
                            if(!empty(\get_query_var('fitting_search'))) {
                                $query = \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\FittingHelper::searchFittings();

                                if($query->have_posts()) {
                                    $uniqueID = \uniqid();

                                    echo '<div class="gallery-row row">';
                                    echo '<ul class="bootstrap-post-loop-fittings bootstrap-post-loop-fittings-' . $uniqueID . ' clearfix">';

                                    while($query->have_posts()) {
                                        $query->the_post();

                                        echo '<li>';
                                        \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('content-fitting');
                                        echo '</li>';
                                    }

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
                                }
                            } else {
                                echo \the_content();
                            }
                            ?>
                        </article>
                    </div> <!-- /.content -->
                </div> <!-- /.col -->

                <?php
                if(\WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\PluginHelper::hasSidebar('sidebar-fitting-manager')) {
                    ?>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-3 sidebar-wrapper">
                        <?php
                        \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('sidebar-fitting-manager');
                        ?>
                    </div><!--/.col -->
                    <?php
                } // END if(\WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\PluginHelper::hasSidebar('sidebar-fitting-manager'))
                ?>
            </div> <!--/.row -->
            <?php
        } // END while(\have_posts())
    } // END if(have_posts())
    ?>
</div><!-- /.container -->

<?php get_footer(); ?>
