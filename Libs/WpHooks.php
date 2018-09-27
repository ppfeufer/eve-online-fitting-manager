<?php

/**
 * Copyright (C) 2017 Rounon Dax
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

\defined('ABSPATH') or die();

class WpHooks {
    /**
     * Path to the plugin main file
     *
     * @var string
     */
    private $pluginFile = null;

    /**
     * New database version
     *
     * @var string
     */
    private $newDatabaseVersion = null;

    /**
     * Current database version
     *
     * @var string
     */
    private $currentDatabaseVersion = null;

    /**
     * Constructor
     *
     * @param array $parameter array with parameters
     */
    public function __construct() {
        $this->pluginFile = Helper\PluginHelper::getPluginPath('eve-online-fitting-manager.php');
        $this->newDatabaseVersion = Helper\PluginHelper::getNewPluginDatabaseVersion();
        $this->currentDatabaseVersion = Helper\PluginHelper::getPluginDatabaseVersion();

        $this->init();
    }

    /**
     * Initialize all the needed hooks, filter, actions and so on
     */
    public function init() {
        $this->initHooks();
        $this->initActions();
        $this->initFilter();
    }

    /**
     * Initialize our hooks
     */
    public function initHooks() {
        \register_activation_hook($this->pluginFile, [$this, 'checkDatabaseForUpdates']);
        \register_activation_hook($this->pluginFile, [$this, 'flushRewriteRulesOnActivation']);

        \register_deactivation_hook($this->pluginFile, [$this, 'flushRewriteRulesOnDeactivation']);
    }

    /**
     * Initialize our actions
     */
    public function initActions() {
        /**
         * in case of plugin update this need to be fired
         * since the activation doesn't fire on update
         * thx wordpress for removing update hooks ...
         */
        \add_action('plugins_loaded', [$this, 'checkDatabaseForUpdates']);

        /**
         * Adding some query vars for the fitting search
         */
        \add_action('pre_get_posts', [$this, 'customPageQueryVars']);

        /**
         * Adding our own thumbnail sizes
         */
        \add_action('init', [$this, 'setThumbnailsSizes']);
    }

    /**
     * Initializing our filter
     */
    public function initFilter() {
        \add_filter('plugin_row_meta', [$this, 'addPluginRowMeta'], 10, 2);
        \add_filter('plugin_action_links_eve-online-fitting-manager/eve-online-fitting-manager.php', [$this, 'addPluginSettingsLink'], 10, 2 );
        \add_filter('network_admin_plugin_action_links_eve-online-fitting-manager/eve-online-fitting-manager.php', [$this, 'addPluginSettingsLink'], 10, 2 );
        \add_filter('query_vars', [$this, 'addQueryVarsFilter']);
    }

    /**
     * Ading some links to the plugin row meta data
     *
     * @param array $links
     * @param string $file
     * @return array
     */
    public function addPluginRowMeta($links, $file) {
        if(\strpos($file, 'eve-online-fitting-manager.php') !== false) {
            $new_links = [
                'issue_tracker' => '<a href="https://github.com/ppfeufer/eve-online-fitting-manager/issues" target="_blank">GitHub Issue Tracker</a>',
                'support_discord' => '<a href="https://discord.gg/YymuCZa" target="_blank">Support Discord</a>'
            ];

            $links = \array_merge($links, $new_links);
        }

        return $links;
    }

    public function addPluginSettingsLink(array $linksArray) {
        \array_unshift($linksArray, '<a href="' . \admin_url('options-general.php?page=eve-online-fittings-manager') . '">' . \__('Settings', 'General') . '</a>');

        return $linksArray;
    }

    /**
     * Hook: checkDatabaseForUpdates
     * Fired on: register_activation_hook
     */
    public function checkDatabaseForUpdates() {
        if($this->currentDatabaseVersion !== $this->newDatabaseVersion) {
            Helper\PluginHelper::updateDatabase();
        }
    }

    /**
     * Customized query vars for our search function
     *
     * @param \WP_Query $query
     * @return \WP_Query
     */
    public function customPageQueryVars(\WP_Query $query) {
        if(!\is_admin() && $query->is_main_query()) {
            if(isset($query->tax_query->queries['0']['taxonomy']) && $query->tax_query->queries['0']['taxonomy'] === 'fitting-doctrines') {
                $query->set('posts_per_page', 9999);
                $query->set('orderby', 'title');
                $query->set('order', 'ASC');
            }

            if(isset($query->tax_query->queries['0']['taxonomy']) && $query->tax_query->queries['0']['taxonomy'] === 'fitting-ships') {
                $query->set('posts_per_page', 9999);
                $query->set('orderby', 'title');
                $query->set('order', 'ASC');
            }
        }

        return $query;
    }

    /**
     * Add our own seach key to the search query vars
     *
     * @param array $queryVars
     * @return array
     */
    public function addQueryVarsFilter($queryVars) {
        $queryVars[] = 'fitting_search';

        return $queryVars;
    }

    /**
     * Set thumbnail sizes
     */
    public function setThumbnailsSizes() {
        /**
         * Thumbnails used for the plugin
         * Compatibilty with Fly Dynamic Image Resizer plugin
         */
        if(\function_exists('\fly_add_image_size')) {
            \fly_add_image_size('header-image', 1680, 500, true);
            \fly_add_image_size('fitting-helper-post-loop-thumbnail', 768, 432, true);
        } else {
            \add_image_size('header-image', 1680, 500, true);
            \add_image_size('fitting-helper-post-loop-thumbnail', 768, 432, true);
        }
    }

    /**
     * Hook: flushRewriteRulesOnActivation
     * Fired on: register_activation_hook
     */
    public function flushRewriteRulesOnActivation() {
        PostType::customPostType();

        \flush_rewrite_rules();
    }

    /**
     * Hook: flushRewriteRulesOnDeactivation
     * Fired on: register_deactivation_hook
     */
    public function flushRewriteRulesOnDeactivation() {
        \flush_rewrite_rules();
    }
}
