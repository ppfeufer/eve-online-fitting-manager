<?php

/*
 * Copyright (C) 2020 ppfeufer
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

?>
<div class="fitting-ship-insurance-details">
    <h4>
        <?php echo __('Ship Insurance Details', 'eve-online-fitting-manager'); ?>
    </h4>

    <div class="row">
        <div class="col-sm-6 col-md-6 col-lg-12 col-xl-6">
            <div class="table-responsive">
                <table class="table table-condensed table-fitting-marketdata">
                    <tr>
                        <th><?php echo __('Insurance Level', 'eve-online-fitting-manager'); ?></th>
                        <th class="text-align-center"><?php echo __('Cost', 'eve-online-fitting-manager'); ?></th>
                        <th class="text-align-center"><?php echo __('Payout', 'eve-online-fitting-manager'); ?></th>
                    </tr>
                    <?php
                    foreach($insurance->getLevels() as $insurancanceLevel) {
                        ?>
                        <tr>
                            <td><?php echo $insurancanceLevel->getName(); ?></td>
                            <td class="text-align-right"><?php echo number_format($insurancanceLevel->getCost(), 2, ',', '.'); ?> ISK</td>
                            <td class="text-align-right"><?php echo number_format($insurancanceLevel->getPayout(), 2, ',', '.'); ?> ISK</td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
