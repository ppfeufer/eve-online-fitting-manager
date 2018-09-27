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

/**
 * Managing the custom post type
 */
class PostType {
    /**
     * Constructor
     */
    public function __construct() {
        \add_action('init', [$this, 'customPostType']);

        \add_filter('template_include', [$this, 'templateLoader']);
        \add_filter('page_template', [$this, 'registerPageTemplate']);
    }

    /**
     * Registering the custom post type
     */
    public static function customPostType() {
        $var_sSlug = self::getPosttypeSlug('fittings');

        $labelsDoctrine = [
            'name' => \__('Doctrines', 'eve-online-fitting-manager'),
            'singular_name' => \__('Doctrine', 'eve-online-fitting-manager'),
            'search_items' => \__('Search Doctrines', 'eve-online-fitting-manager'),
            'popular_items' => \__('Popular Doctrines', 'eve-online-fitting-manager'),
            'all_items' => \__('All Doctrines', 'eve-online-fitting-manager'),
            'parent_item' => \__('Parent Doctrine', 'eve-online-fitting-manager'),
            'edit_item' => \__('Edit Doctrine', 'eve-online-fitting-manager'),
            'update_item' => \__('Update Doctrine', 'eve-online-fitting-manager'),
            'add_new_item' => \__('Add New Doctrine', 'eve-online-fitting-manager'),
            'new_item_name' => \__('New Doctrine', 'eve-online-fitting-manager'),
            'separate_items_with_commas' => \__('Separate Doctrines with commas', 'eve-online-fitting-manager'),
            'add_or_remove_items' => \__('Add or remove Doctrine', 'eve-online-fitting-manager'),
            'choose_from_most_used' => \__('Choose from most used Doctrines', 'eve-online-fitting-manager')
        ];

        $labelsShip = [
            'name' => \__('Ship Types', 'eve-online-fitting-manager'),
            'singular_name' => \__('Ship Type', 'eve-online-fitting-manager'),
            'search_items' => \__('Search Ship Types', 'eve-online-fitting-manager'),
            'popular_items' => \__('Popular Ship Types', 'eve-online-fitting-manager'),
            'all_items' => \__('All Ship Types', 'eve-online-fitting-manager'),
            'parent_item' => \__('Parent Ship Type', 'eve-online-fitting-manager'),
            'edit_item' => \__('Edit Ship Type', 'eve-online-fitting-manager'),
            'update_item' => \__('Update Ship Type', 'eve-online-fitting-manager'),
            'add_new_item' => \__('Add New Ship Type', 'eve-online-fitting-manager'),
            'new_item_name' => \__('New Ship Type', 'eve-online-fitting-manager'),
            'separate_items_with_commas' => \__('Separate Ship Types with commas', 'eve-online-fitting-manager'),
            'add_or_remove_items' => \__('Add or remove Ship Type', 'eve-online-fitting-manager'),
            'choose_from_most_used' => \__('Choose from most used Ship Types', 'eve-online-fitting-manager')
        ];

        $argsTaxDoctrine = [
            'label' => \__('Doctrines', 'eve-online-fitting-manager'),
            'labels' => $labelsDoctrine,
            'public' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'args' => [
                'orderby' => 'term_order'
            ],
            'rewrite' => [
                'slug' => $var_sSlug . '/doctrine',
                'with_front' => true
            ],
            'query_var' => true
        ];

        $argsTaxShip = [
            'label' => \__('Ship Types', 'eve-online-fitting-manager'),
            'labels' => $labelsShip,
            'public' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'args' => [
                'orderby' => 'term_order'
            ],
            'rewrite' => [
                'slug' => $var_sSlug . '/ship',
                'with_front' => true
            ],
            'query_var' => true
        ];

        \register_taxonomy('fitting-doctrines', ['fitting'], $argsTaxDoctrine);
        \register_taxonomy('fitting-ships', ['fitting'], $argsTaxShip);

        \register_post_type('fitting', [
            'labels' => [
                'name' => \__('Fittings', 'eve-online-fitting-manager'),
                'singular_name' => \__('Fitting', 'eve-online-fitting-manager')
            ],
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'supports' => [
                'title',
                'editor',
                'author',
                'thumbnail',
                'revisions',
                'custom-fields'
            ],
            'rewrite' => [
                'slug' => $var_sSlug,
                'with_front' => true
            ]
        ]);
    }

    /**
     * Getting the slug for the custom post type
     *
     * If the pages for looping the custom post types have not the same name
     * as the custom post type, we need to find its slug to get it working.
     *
     * @param string $postType
     */
    public static function getPosttypeSlug($postType) {
        global $wpdb;

        $returnValue = $postType;

        $var_qry = '
            SELECT
                ' . $wpdb->posts . '.post_name as post_name
            FROM
                ' . $wpdb->posts . ',
                ' . $wpdb->postmeta . '
            WHERE
                ' . $wpdb->postmeta . '.meta_key = "_wp_page_template"
                AND ' . $wpdb->postmeta . '.meta_value = "../templates/page-' . $postType . '.php"
                AND ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id;';

        $slugData = $wpdb->get_var($var_qry);

        /**
         * Returning the slug or, if not found the name of custom post type
         */
        if(!empty($slugData)) {
            $returnValue = $slugData;
        }

        return $returnValue;
    }

    /**
     * Template loader.
     *
     * The template loader will check if WP is loading a template
     * for a specific Post Type and will try to load the template
     * from out 'templates' directory.
     *
     * @since 1.0.0
     *
     * @param	string	$template	Template file that is being loaded.
     * @return	string				Template file that should be loaded.
     */
    public function templateLoader($template) {
        $templateFile = null;

        if(\is_singular('fitting')) {
            $templateFile = 'single-fitting.php';
        } elseif(\is_archive() && \is_tax('fitting-doctrines')) {
            $templateFile = 'archive-fitting.php';
        } elseif(\is_archive() && \is_tax('fitting-ships')) {
            $templateFile = 'archive-ship.php';
        } // END if(\is_singular('fitting'))

        if($templateFile !== null) {
            if(\file_exists(Helper\TemplateHelper::locateTemplate($templateFile))) {
                $template = Helper\TemplateHelper::locateTemplate($templateFile);
            } // END if(\file_exists(Helper\TemplateHelper::locateTemplate($file)))
        } // END if($templateFile !== null)

        return $template;
    }

    /**
     * Registering a page template
     *
     * @param string $pageTemplate
     * @return string
     */
    public function registerPageTemplate($pageTemplate) {
        if(\is_page(self::getPosttypeSlug('fittings'))) {
            $pageTemplate = Helper\PluginHelper::getPluginPath('templates/page-fittings.php');
        }

        return $pageTemplate;
    }

    /**
     * Determine the type of the edit page in backend
     *
     * @global array $pagenow
     * @param string $newEdit
     * @return boolean
     */
    public static function isEditPage($newEdit = null) {
        global $pagenow;

        //make sure we are on the backend
        if(!\is_admin()) {
            return false;
        }

        switch($newEdit) {
            case 'edit':
                return \in_array($pagenow, ['post.php']);
                break;

            case 'new':
                return \in_array($pagenow, ['post-new.php']);
                break;

            default:
                return \in_array($pagenow, ['post.php', 'post-new.php']);
                break;
        }
    }
}
