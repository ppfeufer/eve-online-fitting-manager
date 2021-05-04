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
use WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\TemplateHelper;
use WordPress\Plugins\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton;
use WP_Post;

defined('ABSPATH') or die();

/**
 * Managing the custom post type
 */
class PostType extends AbstractSingleton
{
    /**
     * Registering the custom post type
     */
    public function registerCustomPostType(): void
    {
        $var_sSlug = $this->getPosttypeSlug('fittings');

        $labelsDoctrine = [
            'name' => __('Doctrines', 'eve-online-fitting-manager'),
            'singular_name' => __('Doctrine', 'eve-online-fitting-manager'),
            'search_items' => __('Search Doctrines', 'eve-online-fitting-manager'),
            'popular_items' => __('Popular Doctrines', 'eve-online-fitting-manager'),
            'all_items' => __('All Doctrines', 'eve-online-fitting-manager'),
            'parent_item' => __('Parent Doctrine', 'eve-online-fitting-manager'),
            'edit_item' => __('Edit Doctrine', 'eve-online-fitting-manager'),
            'update_item' => __('Update Doctrine', 'eve-online-fitting-manager'),
            'add_new_item' => __('Add New Doctrine', 'eve-online-fitting-manager'),
            'new_item_name' => __('New Doctrine', 'eve-online-fitting-manager'),
            'separate_items_with_commas' => __('Separate Doctrines with commas', 'eve-online-fitting-manager'),
            'add_or_remove_items' => __('Add or remove Doctrine', 'eve-online-fitting-manager'),
            'choose_from_most_used' => __('Choose from most used Doctrines', 'eve-online-fitting-manager')
        ];

        $labelsFleetRole = [
            'name' => __('Fleet Roles', 'eve-online-fitting-manager'),
            'singular_name' => __('Fleet Role', 'eve-online-fitting-manager'),
            'search_items' => __('Search Fleet Roles', 'eve-online-fitting-manager'),
            'popular_items' => __('Popular Fleet Roles', 'eve-online-fitting-manager'),
            'all_items' => __('All Fleet Roles', 'eve-online-fitting-manager'),
            'parent_item' => __('Parent Fleet Role', 'eve-online-fitting-manager'),
            'edit_item' => __('Edit Fleet Role', 'eve-online-fitting-manager'),
            'update_item' => __('Update Fleet Role', 'eve-online-fitting-manager'),
            'add_new_item' => __('Add New Fleet Role', 'eve-online-fitting-manager'),
            'new_item_name' => __('New Fleet Role', 'eve-online-fitting-manager'),
            'separate_items_with_commas' => __('Separate Fleet Roles with commas', 'eve-online-fitting-manager'),
            'add_or_remove_items' => __('Add or remove Fleet Role', 'eve-online-fitting-manager'),
            'choose_from_most_used' => __('Choose from most used Fleet Roles', 'eve-online-fitting-manager')
        ];

        $labelsShip = [
            'name' => __('Ship Types', 'eve-online-fitting-manager'),
            'singular_name' => __('Ship Type', 'eve-online-fitting-manager'),
            'search_items' => __('Search Ship Types', 'eve-online-fitting-manager'),
            'popular_items' => __('Popular Ship Types', 'eve-online-fitting-manager'),
            'all_items' => __('All Ship Types', 'eve-online-fitting-manager'),
            'parent_item' => __('Parent Ship Type', 'eve-online-fitting-manager'),
            'edit_item' => __('Edit Ship Type', 'eve-online-fitting-manager'),
            'update_item' => __('Update Ship Type', 'eve-online-fitting-manager'),
            'add_new_item' => __('Add New Ship Type', 'eve-online-fitting-manager'),
            'new_item_name' => __('New Ship Type', 'eve-online-fitting-manager'),
            'separate_items_with_commas' => __('Separate Ship Types with commas', 'eve-online-fitting-manager'),
            'add_or_remove_items' => __('Add or remove Ship Type', 'eve-online-fitting-manager'),
            'choose_from_most_used' => __('Choose from most used Ship Types', 'eve-online-fitting-manager')
        ];

        $argsTaxDoctrine = [
            'label' => __('Doctrines', 'eve-online-fitting-manager'),
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

        $argsTaxFleetRole = [
            'label' => __('Fleet Role', 'eve-online-fitting-manager'),
            'labels' => $labelsFleetRole,
            'public' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'args' => [
                'orderby' => 'term_order'
            ],
            'rewrite' => [
                'slug' => $var_sSlug . '/fleet-role',
                'with_front' => true
            ],
            'query_var' => true
        ];

        $argsTaxShip = [
            'label' => __('Ship Types', 'eve-online-fitting-manager'),
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

        register_taxonomy('fitting-doctrines', ['fitting'], $argsTaxDoctrine);
        register_taxonomy('fitting-fleet-roles', ['fitting'], $argsTaxFleetRole);
        register_taxonomy('fitting-ships', ['fitting'], $argsTaxShip);

        // add defaults
        $this->insertFleetRoles();
        $this->insertShipTypes();

        register_post_type('fitting', [
            'labels' => [
                'name' => __('Fittings', 'eve-online-fitting-manager'),
                'singular_name' => __('Fitting', 'eve-online-fitting-manager')
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
     * @return string
     */
    public function getPosttypeSlug(string $postType): string
    {
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
        if (!empty($slugData)) {
            $returnValue = $slugData;
        }

        return $returnValue;
    }

    /**
     * Insert fleet roles tax terms
     */
    private function insertFleetRoles(): void
    {
        $fleetRoles = [
            ['name' => 'Anti-Tackle'],
            ['name' => 'Bait'],
            ['name' => 'Booster'],
            ['name' => 'Bridge'],
            ['name' => 'Bubbler'],
            ['name' => 'Capital'],
            ['name' => 'Command'],
            ['name' => 'Cyno'],
            ['name' => 'DPS'],
            ['name' => 'E-War'],
            ['name' => 'Hauler'],
            ['name' => 'Hunter-Killer'],
            ['name' => 'Logistics'],
            ['name' => 'Miner'],
            ['name' => 'Recon'],
            ['name' => 'Scout'],
            ['name' => 'Support'],
            ['name' => 'Tackle']
        ];

        $this->insertTaxonomyTerms('fitting-fleet-roles', $fleetRoles);
    }

    /**
     * Insert taxonomy terms
     */
    private function insertTaxonomyTerms(string $taxonomy, array $terms): void
    {
        foreach ($terms as $term) {
            $arguments = [
                'slug' => sanitize_title($term['name']),
                'parent' => 0
            ];

            if (isset($term['parent'])) {
                $parent = get_term_by('name', $term['parent'], $taxonomy);

                if (!$parent === false) {
                    $arguments['parent'] = $parent->term_id;
                }
            }

            wp_insert_term($term['name'], $taxonomy, $arguments);
        }
    }

    private function insertShipTypes(): void
    {
        $shipTypes = [
            ['name' => 'Battlecruisers'],
            ['name' => 'Battleships'],
            ['name' => 'Carriers'],
            ['name' => 'Dreadnoughts'],
            ['name' => 'Force Auxiliaries'],
            ['name' => 'Supercarriers'],
            ['name' => 'Titans'],
            ['name' => 'Corvettes'],
            ['name' => 'Cruisers'],
            ['name' => 'Destroyers'],
            ['name' => 'Frigates'],
            ['name' => 'Industrial Ships'],
            ['name' => 'Mining Barges'],
            ['name' => 'Shuttles'],
            ['name' => 'Special Edition Ships']
        ];

        $this->insertTaxonomyTerms('fitting-ships', $shipTypes);
    }

    /**
     * Fired on plugin deactivation
     */
    public function unregisterCustomPostType(): void
    {
        unregister_post_type('intel');
    }

    /**
     * Template loader.
     *
     * The template loader will check if WP is loading a template
     * for a specific Post Type and will try to load the template
     * from out 'templates' directory.
     *
     * @param string $template Template file that is being loaded.
     * @return string Template file that should be loaded.
     * @since 1.0.0
     *
     */
    public function templateLoader(string $template): string
    {
        $templateFile = null;

        if (is_singular('fitting')) {
            $templateFile = 'single-fitting.php';
        }

        if (is_archive()) {
            if (is_tax('fitting-doctrines')) {
                $templateFile = 'archive-fitting.php';
            }

            if (is_tax('fitting-ships')) {
                $templateFile = 'archive-ship.php';
            }
        }

        if (($templateFile !== null) && file_exists(TemplateHelper::getInstance()->locateTemplate($templateFile))) {
            $template = TemplateHelper::getInstance()->locateTemplate($templateFile);
        }

        return $template;
    }

    /**
     * Registering a page template
     *
     * @param string $pageTemplate
     * @return string
     */
    public function registerPageTemplate(string $pageTemplate): string
    {
        if (is_page($this->getPosttypeSlug('fittings'))) {
            $pageTemplate = PluginHelper::getInstance()->getPluginPath('templates/page-fittings.php');
        }

        return $pageTemplate;
    }

    /**
     * Determine the type of the edit page in backend
     *
     * @param string|null $newEdit
     * @return bool
     * @global array $pagenow
     */
    public function isEditPage(string $newEdit = null): bool
    {
        global $pagenow;

        //make sure we are on the backend
        if (!is_admin()) {
            return false;
        }

        switch ($newEdit) {
            case 'edit':
                return $pagenow === 'post.php';

            case 'new':
                return $pagenow === 'post-new.php';

            default:
                return in_array($pagenow, ['post.php', 'post-new.php']);
        }
    }

    /**
     * Remove the automagically created meta box.
     * We build our own later ...
     */
    public function removeWpFlettRolesMetaBox(): void
    {
        remove_meta_box('fitting-fleet-rolesdiv', 'fitting', 'normal');
    }

    /**
     * Creating our own meta box for fleet roles, because we need it to be
     * single select. A ship can only fill one fleet role.
     *
     * @param WP_Post $post
     */
    public function createFleetRolesMetaBox(WP_Post $post): void
    {
        add_meta_box(
            'my-meta-box',
            __('Fleet Role', 'eve-online-fitting-manager'),
            [$this, 'renderFleetRolesMetaBox'],
            'fitting',
            'side',
            'default'
        );
    }

    /**
     * Render our fleet roles meta box
     *
     * @param WP_Post $post
     */
    public function renderFleetRolesMetaBox(WP_Post $post): void
    {
        // Get taxonomy and terms
        $taxonomy = 'fitting-fleet-roles';

        //Set up the taxonomy object and get terms
        $tax = get_taxonomy($taxonomy);
        $terms = get_terms($taxonomy, [
            'hide_empty' => 0
        ]);

        // Name of the form
        $name = 'tax_input[' . $taxonomy . '][]';

        //Get current and popular terms
        $popular = get_terms($taxonomy, [
            'orderby' => 'count',
            'order' => 'DESC',
            'number' => 10,
            'hierarchical' => false
        ]);

        $postTerms = get_the_terms($post->ID, $taxonomy);
        $current = ($postTerms) ? array_pop($postTerms) : false;
        $currentPostTerm = $current->term_id ?? 0;
        ?>

        <div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
            <!-- Display tabs-->
            <ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
                <li class="tabs"><a href="#<?php echo $taxonomy; ?>-all"
                                    tabindex="3"><?php echo $tax->labels->all_items; ?></a></li>
                <li class="hide-if-no-js"><a href="#<?php echo $taxonomy; ?>-pop"
                                             tabindex="3"><?php _e('Most Used'); ?></a></li>
            </ul>

            <!-- Display taxonomy terms -->
            <div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
                <ul id="<?php echo $taxonomy; ?>checklist"
                    class="list:<?php echo $taxonomy; ?> categorychecklist form-no-clear">
                    <?php
                    foreach ($terms as $term) {
                        $id = $taxonomy . '-' . $term->term_id;

                        echo '<li id=' . $id . '>';
                        echo '<label class="selectit select-fleet-role">';
                        echo '<input type="radio" id="in-' . $id . '" name="' . $name . '" ' . checked($currentPostTerm, $term->term_id, false) . ' value="' . $term->term_id . '" />' . $term->name;
                        echo '</label>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>

            <!-- Display popular taxonomy terms -->
            <div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">
                <ul id="<?php echo $taxonomy; ?>checklist-pop" class="categorychecklist form-no-clear">
                    <?php
                    foreach ($popular as $term) {
                        $id = 'popular-' . $taxonomy . '-' . $term->term_id;

                        echo '<li id=' . $id . '>';
                        echo '<label class="selectit select-fleet-role">';
                        echo '<input type="radio" id="in-' . $id . '" ' . checked($currentPostTerm, $term->term_id, false) . ' value="' . $term->term_id . '" />' . $term->name;
                        echo '</label>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Add our JS to the fleet role meta box.
     */
    public function fleetRolesTaxonomyJavaScript(): void
    {
        wp_register_script('radiotaxonomy', PluginHelper::getInstance()->getPluginUri('/js/radiotaxonomy.min.js'), ['jquery'], null, true);
        wp_enqueue_script('radiotaxonomy');
    }
}
