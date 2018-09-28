<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#eft-fitting" aria-controls="eft-fitting" role="tab" data-toggle="tab"><h4><?php echo \__('EFT Import', 'eve-online-fitting-manager'); ?></h4></a>
        </li>

        <?php
        /**
         * Fitting description
         */
        $fittingDescription = \get_the_content();
        if(!empty(\trim($fittingDescription))) {
            ?>
            <li role="presentation">
                <a href="#fitting-description" aria-controls="fitting-description" role="tab" data-toggle="tab"><h4><?php echo \__('Information', 'eve-online-fitting-manager'); ?></h4></a>
            </li>
            <?php
        }

        /**
         * Ship description
         */
        if(isset($pluginSettings['template-detail-parts-settings']['show-ship-description']) && $pluginSettings['template-detail-parts-settings']['show-ship-description'] === 'yes') {
            ?>
            <li role="presentation">
                <a href="#ship-description" aria-controls="ship-description" role="tab" data-toggle="tab"><h4><?php echo \__('Description', 'eve-online-fitting-manager'); ?></h4></a>
            </li>
            <?php
        }
        ?>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active ship-fitting-eft-import" id="eft-fitting">
            <p><?php echo \nl2br($eftFitting); ?></p>
        </div>

        <?php
        /**
         * Fitting description
         */
        if(!empty(\trim($fittingDescription))) {
            ?>
            <div role="tabpanel" class="tab-pane ship-fitting-description" id="fitting-description">
                <?php echo \wpautop($fittingDescription); ?>
            </div>
            <?php
        }

        /**
         * Ship description
         */
        if(isset($pluginSettings['template-detail-parts-settings']['show-ship-description']) && $pluginSettings['template-detail-parts-settings']['show-ship-description'] === 'yes') {
            ?>
            <div role="tabpanel" class="tab-pane ship-description" id="ship-description">
                <?php echo WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\FittingHelper::getItemDescription($shipID); ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>
