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

namespace WordPress\Plugins\EveOnlineFittingManager\Libs\ResourceLoader;

use WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\PluginHelper;
use WordPress\Plugins\EveOnlineFittingManager\Libs\Interfaces\AssetsInterface;
use WordPress\Plugins\EveOnlineFittingManager\Libs\PostType;

defined('ABSPATH') or die();

/**
 * JavaScript Loader
 */
class JavascriptLoader implements AssetsInterface
{
    /**
     * Initialize the loader
     */
    public function init(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue'], 99);
    }

    /**
     * Load the JavaScript
     */
    public function enqueue(): void
    {
        /**
         * Only in Frontend
         */
        if (!is_admin()) {
            if (get_post_type() === 'fitting' || is_page(PostType::getInstance()->getPosttypeSlug('fittings'))) {
                wp_enqueue_script('bootstrap-js', PluginHelper::getInstance()->getPluginUri('bootstrap/js/bootstrap.min.js'), ['jquery'], '', true);
                wp_enqueue_script('bootstrap-gallery-js', PluginHelper::getInstance()->getPluginUri('js/jquery.bootstrap-gallery.min.js'), ['jquery', 'bootstrap-js'], '', true);
                wp_enqueue_script('copy-to-clipboard-js', PluginHelper::getInstance()->getPluginUri('js/copy-to-clipboard.min.js'), ['jquery'], '', true);
                wp_enqueue_script('eve-online-fitting-manager-js', PluginHelper::getInstance()->getPluginUri('js/eve-online-fitting-manager.min.js'), ['jquery'], '', true);
                wp_localize_script('eve-online-fitting-manager-js', 'fittingManagerL10n', $this->getJavaScriptTranslations());
            }
        }
    }

    /**
     * Getting teh translation array to translate strings in JavaScript
     *
     * @return array
     */
    private function getJavaScriptTranslations(): array
    {
        return [
            'copyToClipboard' => [
                'eft' => [
                    'text' => [
                        'success' => __('EFT data successfully copied', 'eve-online-fitting-manager'),
                        'error' => __('Something went wrong. Nothing copied. Maybe your browser doesn\'t support this function.', 'eve-online-fitting-manager')
                    ]
                ],
                'permalink' => [
                    'text' => [
                        'success' => __('Permalink successfully copied', 'eve-online-fitting-manager'),
                        'error' => __('Something went wrong. Nothing copied. Maybe your browser doesn\'t support this function.', 'eve-online-fitting-manager')
                    ]
                ]
            ],
            'ajax' => [
                'url' => admin_url('admin-ajax.php'),
                'loaderImage' => PluginHelper::getInstance()->getPluginUri('images/loader-sprite.gif')
            ]
        ];
    }
}
