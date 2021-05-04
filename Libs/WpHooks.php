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

namespace WordPress\Plugins\EveOnlineFittingManager\Libs;

use WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\PluginHelper;
use WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\UpdateHelper;
use WP_Query;

defined('ABSPATH') or die();

class WpHooks
{
    /**
     * Path to the plugin main file
     *
     * @var string
     */
    private $pluginFile;

    /**
     * New database version
     *
     * @var string
     */
    private $newDatabaseVersion;

    /**
     * Current database version
     *
     * @var string
     */
    private $currentDatabaseVersion;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pluginFile = PluginHelper::getInstance()->getPluginPath('EveOnlineFittingManager.php');
        $this->newDatabaseVersion = UpdateHelper::getInstance()->getNewPluginDatabaseVersion();
        $this->currentDatabaseVersion = UpdateHelper::getInstance()->getCurrentDatabaseVersion();

        $this->init();
    }

    /**
     * Initialize all the needed hooks, filter, actions and so on
     */
    public function init(): void
    {
        $this->initHooks();
        $this->initActions();
        $this->initFilter();
        $this->initShortcodes();
    }

    /**
     * Initialize our hooks
     */
    public function initHooks(): void
    {
        /**
         * Hoks fired on plugin activation
         */
        register_activation_hook($this->pluginFile, [UpdateHelper::getInstance(), 'checkDatabaseForUpdates']);
        register_activation_hook($this->pluginFile, [UpdateHelper::getInstance(), 'checkEsiClientForUpdates']);
        register_activation_hook($this->pluginFile, [$this, 'checkPluginOptionsForUpdates']);
        register_activation_hook($this->pluginFile, [$this, 'flushRewriteRulesOnActivation']);

        /**
         * Hooks fired on plugin deactivation
         */
        register_deactivation_hook($this->pluginFile, [$this, 'flushRewriteRulesOnDeactivation']);
        register_deactivation_hook($this->pluginFile, [$this, 'removeDatabaseVersionOnDeactivation']);
    }

    /**
     * Initialize our actions
     */
    public function initActions(): void
    {
        /**
         * in case of plugin update this need to be fired
         * since the activation doesn't fire on update
         * thx wordpress for removing update hooks ...
         */
        add_action('plugins_loaded', [UpdateHelper::getInstance(), 'checkDatabaseForUpdates']);
        add_action('plugins_loaded', [UpdateHelper::getInstance(), 'checkEsiClientForUpdates']);
        add_action('plugins_loaded', [$this, 'checkPluginOptionsForUpdates']);

        /**
         * Adding some query vars for the fitting search
         */
        add_action('pre_get_posts', [$this, 'customPageQueryVars']);

        /**
         * Adding our own thumbnail sizes
         */
        add_action('init', [$this, 'setThumbnailsSizes']);

        /**
         * Adding our custom post type
         */
        add_action('init', [PostType::getInstance(), 'registerCustomPostType']);

        /**
         * Registering our widgets
         */
        add_action('init', [Widgets::getInstance(), 'registerSidebar'], 99);
        add_action('widgets_init', [Widgets::getInstance(), 'registerWidgets']);

        /**
         * Market Data API
         */
        add_action('wp_ajax_nopriv_get-eve-fitting-market-data', [MarketData::getInstance(), 'ajaxGetFittingMarketData']);
        add_action('wp_ajax_get-eve-fitting-market-data', [MarketData::getInstance(), 'ajaxGetFittingMarketData']);

        /**
         * Remove the automagically creates taxonomy box for fleet roles.
         * We establish our own, since a ship can only serve one fleet role
         */
        add_action('admin_menu', [PostType::getInstance(), 'removeWpFlettRolesMetaBox']);
        add_action('add_meta_boxes_fitting', [PostType::getInstance(), 'createFleetRolesMetaBox']);
        add_action('admin_enqueue_scripts', [PostType::getInstance(), 'fleetRolesTaxonomyJavaScript']);
    }

    /**
     * Initializing our filter
     */
    public function initFilter(): void
    {
        add_filter('plugin_row_meta', [$this, 'addPluginRowMeta'], 10, 2);
        add_filter('plugin_action_links_eve-online-fitting-manager/EveOnlineFittingManager.php', [$this, 'addPluginSettingsLink'], 10, 2);
        add_filter('network_admin_plugin_action_links_eve-online-fitting-manager/EveOnlineFittingManager.php', [$this, 'addPluginSettingsLink'], 10, 2);
        add_filter('query_vars', [$this, 'addQueryVarsFilter']);

        add_filter('template_include', [PostType::getInstance(), 'templateLoader']);
        add_filter('page_template', [PostType::getInstance(), 'registerPageTemplate']);
    }

    public function initShortcodes(): void
    {
        add_shortcode('fittings', [Shortcodes::getInstance(), 'shortcodeFittings']);
    }

    /**
     * Ading some links to the plugin row meta data
     *
     * @param array $links
     * @param string $file
     * @return array
     */
    public function addPluginRowMeta(array $links, string $file): array
    {
        if (strpos($file, 'EveOnlineFittingManager.php') !== false) {
            $newLinks = [
                'issue_tracker' => '<a href="https://github.com/ppfeufer/eve-online-fitting-manager/issues" target="_blank">GitHub Issue Tracker</a>',
                'support_discord' => '<a href="https://discord.gg/YymuCZa" target="_blank">Support Discord</a>'
            ];

            $links = array_merge($links, $newLinks);
        }

        return $links;
    }

    public function addPluginSettingsLink(array $linksArray): array
    {
        array_unshift($linksArray, '<a href="' . admin_url('options-general.php?page=eve-online-fittings-manager') . '">' . __('Settings', 'General') . '</a>');

        return $linksArray;
    }

    /**
     * Hook: checkDatabaseForUpdates
     * Fired on: register_activation_hook
     */
    public function checkPluginOptionsForUpdates(): void
    {
        if ($this->currentDatabaseVersion !== $this->newDatabaseVersion) {
            UpdateHelper::getInstance()->updatePluginOptions();
        }
    }

    /**
     * Customized query vars for our search function
     *
     * @param WP_Query $query
     * @return WP_Query
     */
    public function customPageQueryVars(WP_Query $query): WP_Query
    {
        if (!is_admin() && $query->is_main_query()) {
            if (isset($query->tax_query->queries['0']['taxonomy']) && $query->tax_query->queries['0']['taxonomy'] === 'fitting-doctrines') {
                $query->set('posts_per_page', 9999);
                $query->set('orderby', 'title');
                $query->set('order', 'ASC');
            }

            if (isset($query->tax_query->queries['0']['taxonomy']) && $query->tax_query->queries['0']['taxonomy'] === 'fitting-ships') {
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
    public function addQueryVarsFilter(array $queryVars): array
    {
        $queryVars[] = 'fitting_search';

        return $queryVars;
    }

    /**
     * Set thumbnail sizes
     */
    public function setThumbnailsSizes(): void
    {
        /**
         * Thumbnails used for the plugin
         * Compatibilty with Fly Dynamic Image Resizer plugin
         */
        if (function_exists('\fly_add_image_size')) {
            fly_add_image_size('fitting-manager-post-loop-thumbnail', 768, 432, true);
        } else {
            add_image_size('fitting-manager-post-loop-thumbnail', 768, 432, true);
        }
    }

    /**
     * Hook: flushRewriteRulesOnActivation
     * Fired on: register_activation_hook
     */
    public function flushRewriteRulesOnActivation(): void
    {
        PostType::getInstance()->registerCustomPostType();

        flush_rewrite_rules();
    }

    /**
     * Hook: flushRewriteRulesOnDeactivation
     * Fired on: register_deactivation_hook
     */
    public function flushRewriteRulesOnDeactivation(): void
    {
        PostType::getInstance()->unregisterCustomPostType();

        flush_rewrite_rules();
    }

    /**
     * Removing the DB version on plugin decativation
     * Issue: https://github.com/ppfeufer/eve-online-killboard-widget/issues/50
     */
    public function removeDatabaseVersionOnDeactivation(): void
    {
        delete_option(UpdateHelper::getInstance()->getDatabaseVersionFieldName());
    }
}
