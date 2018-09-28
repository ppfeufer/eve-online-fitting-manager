<?php
defined('ABSPATH') or die();

$pluginOptions = \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\PluginHelper::getPluginSettings();
?>

<article id="post-<?php \the_ID(); ?>" <?php \post_class('clearfix content-single template-content-fitting'); ?>>
    <section class="post-content">
        <div class="entry-content">
            <?php
            if(isset($pluginOptions['template-image-settings']['show-ship-images-in-loop']) && $pluginOptions['template-image-settings']['show-ship-images-in-loop'] === 'yes') {
                ?>
                <a class="fitting-list-item" href="<?php \the_permalink(); ?>" title="<?php \the_title_attribute('echo=0'); ?>">
                    <figure class="post-loop-thumbnail">
                        <?php
                        if(\has_post_thumbnail()) {
                            if(\function_exists('\fly_get_attachment_image')) {
                                echo \fly_get_attachment_image(\get_post_thumbnail_id(), 'post-loop-thumbnail');
                            } else {
                                \the_post_thumbnail('post-loop-thumbnail');
                            }
                        } else {
                            // Load our dummy ...
                            ?>
                            <img width="705" height="395" src="<?php echo \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\PluginHelper::getPluginUri('images/fitting-dummy.jpg'); ?>" class="attachment-post-loop-thumbnail img-responsive" alt="<?php echo \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_ship_type', true); ?>">
                            <?php
                        }
                        ?>
                    </figure>

                    <header class="entry-header">
                        <h2 class="entry-title">
                            <?php echo \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_ship_type', true); ?> -
                            <?php echo \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_name', true); ?>
                        </h2>
                    </header><!--end .entry-header -->
                </a>
                <?php
            } else {
                ?>
                <a class="fitting-list-item" href="<?php echo \get_the_permalink(); ?>">
                    <h3 class="doctrine-shipfitting-header">
                        <span class="doctrine-shipfitting-header-wrapper">
                            <span class="doctrine-shipfitting-header-ship-image">
                                <img src="<?php echo \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\FittingHelper::getShipImageById(\get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_ship_ID', true), 64); ?>">
                            </span>
                            <span class="doctrine-shipfitting-header-fitting-name">
                                <?php echo \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_ship_type', true); ?><br>
                                <small><?php echo \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_name', true); ?></small>
                            </span>
                        </span>
                    </h3>
                </a>
                <?php
            }
            ?>
        </div>
    </section>
</article><!-- /.post-->
