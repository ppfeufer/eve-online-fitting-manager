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

?>
<div class="fitting-copy-to-clipboard copy-eft-to-clipboard<?php echo ' col-xl-' . $columnsPerButton; ?>">
    <ul class="nav nav-pills nav-stacked">
        <li role="presentation">
            <span type="button" class="btn btn-default btn-copy-eft-to-clipboard" data-clipboard-action="copy" data-clipboard-target=".hidden-eft-for-copy"><?php echo \__('Copy EFT Data', 'eve-online-fitting-manager'); ?></span>
        </li>
    </ul>

    <textarea class="hidden-eft-for-copy" style="width: 0; height: 0; border: none; position: absolute; left: -9999px; top:  -9999px;"><?php echo $eftFitting; ?></textarea>
</div>
