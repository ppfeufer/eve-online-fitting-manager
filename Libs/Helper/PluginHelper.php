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

namespace WordPress\Plugins\EveOnlineFittingManager\Libs\Helper;

use WordPress\Plugins\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton;

defined('ABSPATH') or die();

class PluginHelper extends AbstractSingleton
{
    /**
     * Getting the Plugin Path
     *
     * @param string $file
     * @return string
     */
    public function getPluginPath(string $file = ''): string
    {
        return WP_PLUGIN_DIR . '/' . $this->getPluginDirName() . '/' . $file;
    }

    /**
     * Get the plugins directory base name
     *
     * @return string
     */
    public function getPluginDirName(): string
    {
//        return dirname(dirname(dirname(plugin_basename(__FILE__))));
        return dirname(plugin_basename(__FILE__), 3);
    }

    /**
     * Getting the Plugin URI
     *
     * @param string $file
     * @return string
     */
    public function getPluginUri(string $file = ''): string
    {
        return WP_PLUGIN_URL . '/' . $this->getPluginDirName() . '/' . $file;
    }

    /**
     * Getting the Plugin's settings
     *
     * @param boolean $merged Merge with default settings (true/false)
     * @return array
     */
    public function getPluginSettings(bool $merged = true): array
    {
        if ($merged === true) {
            $pluginSettings = get_option(UpdateHelper::getInstance()->getOptionFieldName(), $this->getPluginDefaultSettings());
        } else {
            $pluginSettings = get_option(UpdateHelper::getInstance()->getOptionFieldName());
        }

        return $pluginSettings;
    }

    /**
     * Returning the default settings for this plugin
     *
     * @return array
     */
    public function getPluginDefaultSettings(): array
    {
        return [
            'market-data-api' => 'eve-marketer',
            'template-image-settings' => [
                'show-ship-images-in-loop' => '',
            ],
            'template-detail-parts-settings' => [
                'show-visual-fitting' => 'yes',
                'show-ship-description' => 'yes',
                'show-copy-eft' => 'yes',
                'show-copy-permalink' => 'yes',
                'show-market-data' => 'yes',
                'show-insurance-details' => 'yes',
                'show-doctrines' => 'yes',
            ]
        ];
    }

    public function getMainContentColClasses($echo = false): string
    {
        $contentColClass = 'col-lg-12 col-md-12 col-sm-12 col-12';

        if ($this->hasSidebar('sidebar-fitting-manager')) {
            $contentColClass = 'col-lg-9 col-md-9 col-sm-9 col-9';
        }

        if ($echo === true) {
            echo $contentColClass;
        } else {
            return $contentColClass;
        }
    }

    /**
     * Alias for is_active_sidebar()
     *
     * @param string $sidebarPosition
     * @return boolean
     * @uses is_active_sidebar() Whether a sidebar is in use.
     */
    public function hasSidebar($sidebarPosition): bool
    {
        return is_active_sidebar($sidebarPosition);
    }

    public function getLoopContentClasses($echo = false): string
    {
        $contentColClass = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';

        if ($this->hasSidebar('sidebar-fitting-manager')) {
            $contentColClass = 'col-lg-4 col-md-6 col-sm-12 col-xs-12';
        }

        if ($echo === true) {
            echo $contentColClass;
        } else {
            return $contentColClass;
        }
    }
}
