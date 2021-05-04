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

/**
 * WordPress Settings API
 * by H.-Peter Pfeufer
 * Inspired by: http://www.wp-load.com/register-settings-api/
 *
 * Usage:
 *      $settingsApi = new SettingsApi($settingsFilterName, $defaultOptions);
 *      $settingsApi->init();
 *
 * @version 0.1
 */

namespace WordPress\Plugins\EveOnlineFittingManager\Libs;

use WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\PluginHelper;
use WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\StringHelper;
use WP_Query;

defined('ABSPATH') or die();

class SettingsApi
{
    /**
     * Settings Arguments
     *
     * @var array
     */
    private array $args;

    /**
     * Settings Array
     *
     * @var array
     */
    private array $settingsArray;

    /**
     * Settings Filter Name
     *
     * @var string
     */
    private string $settingsFilter;

    /**
     * Default Options
     *
     * @var array
     */
    private array $optionsDefault;

    /**
     * @var false|mixed|void
     */
    private $options;

    /**
     * Constructor
     *
     * @param string $settingsFilter The name of your settings filter
     * @param array $defaultOptions Your default options array
     */
    public function __construct(string $settingsFilter, array $defaultOptions)
    {
        $this->settingsFilter = $settingsFilter;
        $this->optionsDefault = $defaultOptions;
    }

    /**
     * Initializing all actions
     */
    public function init(): void
    {
        add_action('init', [$this, 'initSettings']);
        add_action('admin_menu', [$this, 'menuPage']);
        add_action('admin_init', [$this, 'registerFields']);
        add_action('admin_init', [$this, 'registerCallback']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueStyles']);
    }

    /**
     * Init settings runs before admin_init
     * Put $settingsArray to private variable
     * Add admin_head for needed inline scripts
     */
    public function initSettings(): void
    {
        if (is_admin()) {
            $this->settingsArray = apply_filters($this->settingsFilter, []);

            if ($this->isSettingsPage() === true) {
                add_action('admin_head', [$this, 'adminScripts']);
            }
        }
    }

    /**
     * Check if the current page is a settings page
     *
     * @return boolean
     */
    public function isSettingsPage(): bool
    {
        $menus = [];
        $getPageFiltered = filter_input(INPUT_GET, 'page');
        $getPage = (!empty($getPageFiltered)) ? $getPageFiltered : '';

        foreach ($this->settingsArray as $menu => $page) {
            $menus[] = $menu;
        } // END foreach($this->settingsArray as $menu => $page)

        if (in_array($getPage, $menus, true)) {
            return true;
        }

        return false;
    }

    /**
     * Creating pages and menus from the settingsArray
     */
    public function menuPage(): void
    {
        foreach ($this->settingsArray as $menu_slug => $options) {
            if (!empty($options['page_title']) && !empty($options['menu_title']) && !empty($options['option_name'])) {
                /**
                 * Set capabilities
                 * If none is set, 'manage_options' will be default
                 */
                $options['capability'] = (!empty($options['capability'])) ? $options['capability'] : 'manage_options';

                /**
                 * Set type
                 * If none is set, 'plugin' will be default
                 *
                 * Supported types:
                 *      theme   => Adds the options page to Appearance menu
                 *      plugin  => Adds the options page to Settings menu
                 */
                $options['type'] = (!empty($options['type'])) ? $options['type'] : 'plugin';

                switch ($options['type']) {
                    // Adding theme settings page
                    case 'theme':
                        add_theme_page(
                            $options['page_title'],
                            $options['menu_title'],
                            $options['capability'],
                            $menu_slug,
                            [
                                $this,
                                'renderOptions'
                            ]
                        );
                        break;

                    // Adding plugin settings page
                    case 'plugin':
                        add_options_page(
                            $options['page_title'],
                            $options['menu_title'],
                            $options['capability'],
                            $menu_slug,
                            [
                                $this,
                                'renderOptions'
                            ]
                        );
                        break;
                }
            }
        }
    }

    /**
     * Register all fields and settings bound to it from the settingsArray
     */
    public function registerFields(): void
    {
        foreach ($this->settingsArray as $pageId => $settings) {
            if (!empty($settings['tabs']) && is_array($settings['tabs'])) {
                foreach ($settings['tabs'] as $tabId => $item) {
                    $sanitizedTabId = sanitize_title($tabId);
                    $tab_description = (!empty($item['tab_description'])) ? $item['tab_description'] : '';
                    $settingArgs = [
                        'option_group' => 'section_page_' . $pageId . '_' . $sanitizedTabId,
                        'option_name' => $settings['option_name']
                    ];

                    register_setting($settingArgs['option_group'], $settingArgs['option_name']);

                    $sectionArgs = [
                        'id' => 'section_id_' . $sanitizedTabId,
                        'title' => $tab_description,
                        'callback' => 'callback',
                        'menu_page' => $pageId . '_' . $sanitizedTabId
                    ];

                    add_settings_section(
                        $sectionArgs['id'],
                        $sectionArgs['title'],
                        [
                            $this, $sectionArgs['callback']
                        ],
                        $sectionArgs['menu_page']
                    );

                    if (!empty($item['fields']) && is_array($item['fields'])) {
                        foreach ($item['fields'] as $fieldId => $field) {
                            if (is_array($field)) {
                                $sanitizedFieldId = sanitize_title($fieldId);
                                $title = (!empty($field['title'])) ? $field['title'] : '';
                                $field['field_id'] = $sanitizedFieldId;
                                $field['option_name'] = $settings['option_name'];
                                $fieldArgs = [
                                    'id' => 'field' . $sanitizedFieldId,
                                    'title' => $title,
                                    'callback' => 'renderFields',
                                    'menu_page' => $pageId . '_' . $sanitizedTabId,
                                    'section' => 'section_id_' . $sanitizedTabId,
                                    'args' => $field
                                ];

                                add_settings_field(
                                    $fieldArgs['id'], $fieldArgs['title'],
                                    [
                                        $this,
                                        $fieldArgs['callback']
                                    ],
                                    $fieldArgs['menu_page'],
                                    $fieldArgs['section'],
                                    $fieldArgs['args']
                                );
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Register callback is used for the button field type when user click the button
     * For now only works with plugin settings
     */
    public function registerCallback(): void
    {
        if ($this->isSettingsPage() === true) {
            $callbackFiltered = filter_input(INPUT_GET, 'callback');

            if (!empty($callbackFiltered)) {
                $wpNonce = filter_input(INPUT_GET, '_wpnonce');
                $nonce = wp_verify_nonce($wpNonce);

                if (!empty($nonce)) {
                    $callbackFunction = filter_input(INPUT_GET, 'callback');

                    if (function_exists($callbackFunction)) {
                        $message = $callbackFunction();
                        update_option('rsa-message', $message);

                        $page = filter_input(INPUT_GET, 'page');
                        $url = admin_url('options-general.php?page=' . $page);
                        wp_redirect($url);

                        die;
                    }
                }
            }
        }
    }

    /**
     * Get users from WordPress, used by the select field type
     *
     * @return array
     */
    public function getUsers(): array
    {
        $items = [];
        $args = (!empty($this->args['args'])) ? $this->args['args'] : null;
        $users = get_users($args);

        foreach ($users as $user) {
            $items[$user->ID] = $user->display_name;
        }

        return $items;
    }

    /**
     * Get menus from WordPress, used by the select field type
     *
     * @return array
     */
    public function getMenus(): array
    {
        $items = [];
        $menus = get_registered_nav_menus();

        if (!empty($menus)) {
            foreach ($menus as $location => $description) {
                $items[$location] = $description;
            }
        }

        return $items;
    }

    /**
     * Get posts from WordPress, used by the select field type
     *
     * @return array
     */
    public function getPosts(): array
    {
        $items = null;

        if ($this->args['get'] === 'posts' && !empty($this->args['post_type'])) {
            $args = [
                'category' => 0,
                'post_type' => 'post',
                'post_status' => 'publish',
                'orderby' => 'post_date',
                'order' => 'DESC',
                'suppress_filters' => true
            ];

            $theQuery = new WP_Query($args);

            if ($theQuery->have_posts()) {
                while ($theQuery->have_posts()) {
                    $theQuery->the_post();

                    global $post;

                    $items[$post->ID] = get_the_title();
                }
            }

            wp_reset_postdata();
        }

        return $items;
    }

    /**
     * Get terms from WordPress, used by the select field type
     *
     * @return array
     */
    public function getTerms(): array
    {
        $items = [];
        $taxonomies = (!empty($this->args['taxonomies'])) ? $this->args['taxonomies'] : null;
        $args = (!empty($this->args['args'])) ? $this->args['args'] : null;
        $terms = get_terms($taxonomies, $args);

        if (!empty($terms)) {
            foreach ($terms as $key => $term) {
                $items[$term->term_id] = $term->name;
            }
        }

        return $items;
    }

    /**
     * Get taxonomies from WordPress, used by the select field type
     *
     * @return array
     */
    public function getTaxonomies(): array
    {
        $items = [];
        $args = (!empty($this->args['args'])) ? $this->args['args'] : null;
        $taxonomies = get_taxonomies($args, 'objects');

        if (!empty($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                $items[$taxonomy->name] = $taxonomy->label;
            }
        }

        return $items;
    }

    /**
     * Get sidebars from WordPress, used by the select field type
     *
     * @return array
     * @global array $wp_registered_sidebars
     */
    public function getSidebars(): array
    {
        $items = [];

        global $wp_registered_sidebars;

        if (!empty($wp_registered_sidebars)) {
            foreach ($wp_registered_sidebars as $sidebar) {
                $items[$sidebar['id']] = $sidebar['name'];
            }
        }

        return $items;
    }

    /**
     * Get themes from WordPress, used by the select field type
     *
     * @return array
     */
    public function getThemes(): array
    {
        $items = [];
        $args = (!empty($this->args['args'])) ? $this->args['args'] : null;
        $themes = wp_get_themes($args);

        if (!empty($themes)) {
            foreach ($themes as $key => $theme) {
                $items[$key] = $theme->get('Name');
            }
        }

        return $items;
    }

    /**
     * Get plugins from WordPress, used by the select field type
     *
     * @return array
     */
    public function getPlugins(): array
    {
        $items = [];
        $args = (!empty($this->args['args'])) ? $this->args['args'] : null;
        $plugins = get_plugins($args);

        if (!empty($plugins)) {
            foreach ($plugins as $key => $plugin) {
                $items[$key] = $plugin['Name'];
            }
        }

        return $items;
    }

    /**
     * Get post_types from WordPress, used by the select field type
     *
     * @return array
     */
    public function getPostTypes(): array
    {
        $items = [];
        $args = (!empty($this->args['args'])) ? $this->args['args'] : null;
        $postTypes = get_post_types($args, 'objects');

        if (!empty($postTypes)) {
            foreach ($postTypes as $key => $post_type) {
                $items[$key] = $post_type->name;
            }
        }

        return $items;
    }

    /**
     * All the field types in html
     *
     * @param array $args
     */
    public function renderFields(array $args): void
    {
        $args['field_id'] = sanitize_title($args['field_id']);
        $this->args = $args;

        $options = get_option($args['option_name'], $this->optionsDefault);
        $this->options = $options;

        $optionName = sanitize_title($args['option_name']);
        $out = '';

        if (!empty($args['type'])) {
            switch ($args['type']) {
                case 'info':
                    if (!empty($args['infotext'])) {
                        $out .= '<div class="notice notice-warning"><p>' . $args['infotext'] . '</p></div>';
                    }
                    break;

                case 'select':
                case 'multiselect':
                    $multiple = ($args['type'] === 'multiselect') ? ' multiple' : '';
                    $items = $this->get();
                    $out .= '<select' . $multiple . ' name="' . $this->name() . '"' . $this->size($items) . '>';

                    if (!empty($args['empty'])) {
                        $out .= '<option value="" ' . $this->selected('') . '>' . $args['empty'] . '</option>';
                    }

                    foreach ($items as $key => $choice) {
                        $key = sanitize_title($key);
                        $out .= '<option value="' . $key . '" ' . $this->selected($key) . '>' . $choice . '</option>';
                    }

                    $out .= '</select>';
                    break;

                case 'radio':
                    if ($this->hasItems()) {
                        $horizontal = (isset($args['align']) && (string)$args['align'] === 'horizontal') ? ' class="horizontal"' : '';

                        $out .= '<ul class="settings-group settings-type-' . $args['type'] . '">';

                        foreach ($args['choices'] as $slug => $choice) {
                            $checked = $this->checked($slug);

                            $out .= '<li' . $horizontal . '><label>';
                            $out .= '<input value="' . $slug . '" type="' . $args['type'] . '" name="' . $this->name($slug) . '"' . $checked . '>';
                            $out .= $choice;
                            $out .= '</label></li>';
                        }

                        $out .= '</ul>';
                    }
                    break;

                case 'checkbox':
                    if ($this->hasItems()) {
                        $horizontal = (isset($args['align']) && (string)$args['align'] === 'horizontal') ? ' class="horizontal"' : '';

                        $out .= '<ul class="settings-group settings-type-' . $args['type'] . '">';

                        foreach ($args['choices'] as $slug => $choice) {
                            $checked = $this->checked($slug);

                            $out .= '<li' . $horizontal . '><label>';
                            $out .= '<input value="yes" type="' . $args['type'] . '" name="' . $this->name($slug) . '"' . $checked . '>';
                            $out .= $choice;
                            $out .= '</label></li>';
                        }

                        $out .= '</ul>';
                    }
                    break;

                case 'text':
                case 'email':
                case 'url':
                case 'color':
                case 'date':
                case 'number':
                case 'password':
                case 'colorpicker':
                case 'datepicker':
                    $out = '<input type="' . $args['type'] . '" value="' . $this->value() . '" name="' . $this->name() . '" class="' . $args['type'] . '" data-id="' . $args['field_id'] . '">';
                    break;

                case 'textarea':
                    $rows = $args['rows'] ?? 5;
                    $out .= '<textarea rows="' . $rows . '" class="large-text" name="' . $this->name() . '">' . $this->value() . '</textarea>';
                    break;

                case 'tinymce':
                    $rows = $args['rows'] ?? 5;
                    $tinymceSettings = [
                        'textarea_rows' => $rows,
                        'textarea_name' => $optionName . '[' . $args['field_id'] . ']',
                    ];

                    wp_editor($this->value(), $args['field_id'], $tinymceSettings);
                    break;

                case 'image':
                    $imageObject = (!empty($options[$args['field_id']])) ? wp_get_attachment_image_src($options[$args['field_id']]) : '';
                    $image = (!empty($imageObject)) ? $imageObject[0] : '';
                    $uploadStatus = (!empty($imageObject)) ? ' style="display: none"' : '';
                    $removeStatus = (!empty($imageObject)) ? '' : ' style="display: none"';
                    $value = (!empty($options[$args['field_id']])) ? $options[$args['field_id']] : '';
                    ?>
                    <div data-id="<?php echo $args['field_id']; ?>">
                        <div class="upload"
                             data-field-id="<?php echo $args['field_id']; ?>"<?php echo $uploadStatus; ?>>
                            <span class="button upload-button">
                                <a href="#">
                                    <i class="fa fa-upload"></i>
                                    <?php echo __('Upload'); ?>
                                </a>
                            </span>
                        </div>
                        <div class="image">
                            <img class="uploaded-image" src="<?php echo $image; ?>" id="<?php echo $args['field_id']; ?>" alt="<?php __('Uploaded Image', 'eve-online-fitting-manager'); ?>">
                        </div>
                        <div class="remove"<?php echo $removeStatus; ?>>
                            <span class="button upload-button">
                                <a href="#">
                                    <i class="fa fa-trash"></i>
                                    <?php echo __('Remove'); ?>
                                </a>
                            </span>
                        </div>
                        <input type="hidden" class="attachment_id" value="<?php echo $value; ?>"
                               name="<?php echo $optionName; ?>[<?php echo $args['field_id']; ?>]">
                    </div>
                    <?php
                    break;

                case 'file':
                    $fileUrl = (!empty($options[$args['field_id']])) ? wp_get_attachment_url($options[$args['field_id']]) : '';
                    $uploadStatus = (!empty($fileUrl)) ? ' style="display: none"' : '';
                    $removeStatus = (!empty($fileUrl)) ? '' : ' style="display: none"';
                    $value = (!empty($options[$args['field_id']])) ? $options[$args['field_id']] : '';
                    ?>
                    <div data-id="<?php echo $args['field_id']; ?>">
                        <div class="upload"
                             data-field-id="<?php echo $args['field_id']; ?>"<?php echo $uploadStatus; ?>>
                            <span class="button upload-button">
                                <a href="#">
                                    <i class="fa fa-upload"></i>
                                    <?php echo __('Upload'); ?>
                                </a>
                            </span>
                        </div>
                        <div class="url">
                            <code class="uploaded-file-url" title="Attachment ID: <?php echo $value; ?>"
                                  data-field-id="<?php echo $args['field_id']; ?>">
                                <?php echo $fileUrl; ?>
                            </code>
                        </div>
                        <div class="remove"<?php echo $removeStatus; ?>>
                            <span class="button upload-button">
                                <a href="#">
                                    <i class="fa fa-trash"></i>
                                    <?php echo __('Remove'); ?>
                                </a>
                            </span>
                        </div>
                        <input type="hidden" class="attachment_id" value="<?php echo $value; ?>"
                               name="<?php echo $optionName; ?>[<?php echo $args['field_id']; ?>]">
                    </div>
                    <?php
                    break;

                case 'button':
                    $warningMessage = (!empty($args['warning-message'])) ? $args['warning-message'] : 'Unsaved settings will be lost. Continue?';
                    $warning = (!empty($args['warning'])) ? ' onclick="return confirm(' . "'" . $warningMessage . "'" . ')"' : '';
                    $label = (!empty($args['label'])) ? $args['label'] : '';
                    $pageFiltered = filter_input(INPUT_GET, 'page');
                    $callbackFiltered = filter_input(INPUT_GET, 'callback');
                    $completeUrl = wp_nonce_url(admin_url('options-general.php?page=' . $pageFiltered . '&callback=' . $callbackFiltered));
                    ?>
                    <a href="<?php echo $completeUrl; ?>"
                       class="button button-secondary"<?php echo $warning; ?>><?php echo $label; ?></a>
                    <?php
                    break;

                case 'custom':
                    $value = (!empty($options[$args['field_id']])) ? $options[$args['field_id']] : null;
                    $data = [
                        'value' => $value,
                        'name' => $this->name(),
                        'args' => $args
                    ];

                    if ($args['content'] !== null) {
                        echo $args['content'];
                    }

                    if ($args['callback'] !== null) {
                        call_user_func($args['callback'], $data);
                    }
                    break;
            }
        }

        echo $out;

        if (!empty($args['description'])) {
            echo '<p class="description">' . $args['description'] . '</div>';
        }
    }

    /**
     * Get values from built in WordPress functions
     *
     * @return array
     */
    public function get(): array
    {
        if (!empty($this->args['get'])) {
            $itemArray = $this->{'get' . ucfirst(StringHelper::getInstance()->camelCase($this->args['get']))}($this->args);
        } elseif (!empty($this->args['choices'])) {
            $itemArray = $this->selectChoices();
        } else {
            $itemArray = [];
        }

        return $itemArray;
    }

    /**
     * Return an array for the choices in a select field type
     *
     * @return array
     */
    public function selectChoices(): array
    {
        $items = [];

        if (!empty($this->args['choices']) && is_array($this->args['choices'])) {
            foreach ($this->args['choices'] as $slug => $choice) {
                $items[$slug] = $choice;
            }
        }

        return $items;
    }

    /**
     * Return the html name of the field
     *
     * @param string $slug
     * @return string
     */
    public function name(string $slug = ''): string
    {
        $optionName = sanitize_title($this->args['option_name']);

        $returnValue = $optionName . '[' . $this->args['field_id'] . ']';

        if ($this->valueType() === 'array') {
            $returnValue = $optionName . '[' . $this->args['field_id'] . '][' . $slug . ']';
        }

        return $returnValue;
    }

    /**
     * Check if the current value type is a single value or a multiple value
     * field type, return string or array
     *
     * @return string
     */
    public function valueType(): ?string
    {
        $defaultSingle = [
            'select',
            'radio',
            'text',
            'email',
            'url',
            'color',
            'date',
            'number',
            'password',
            'colorpicker',
            'textarea',
            'datepicker',
            'tinymce',
            'image',
            'file'
        ];

        $defaultMultiple = [
            'multiselect',
            'checkbox'
        ];

        $valueType = null;

        if (in_array($this->args['type'], $defaultSingle, true)) {
            $valueType = 'string';
        }

        if (in_array($this->args['type'], $defaultMultiple, true)) {
            $valueType = 'array';
        }

        return $valueType;
    }

    /**
     * Return the size of a multiselect type. If not set it will calculate it
     *
     * @param array $items
     * @return string
     */
    public function size(array $items): string
    {
        $size = '';

        if ($this->args['type'] === 'multiselect') {
            if (!empty($this->args['size'])) {
                $count = $this->args['size'];
            } else {
                $itemCount = count($items);
                $count = (!empty($this->args['empty'])) ? $itemCount + 1 : $itemCount;
            }

            $size = ' size="' . $count . '"';
        }

        return $size;
    }

    /**
     * Find a selected value in select or multiselect field type
     *
     * @param array|string $key
     * @return string
     */
    public function selected($key): string
    {
        if ($this->valueType() === 'array') {
            return $this->multiselectedValue($key);
        }

        return $this->selectedValue($key);
    }

    /**
     * Return selected html if the value is selected in multiselect field type
     *
     * @param string $key
     * @return string
     */
    public function multiselectedValue(string $key): string
    {
        $result = '';
        $value = $this->value();

        if (is_array($value) && in_array($key, $value, true)) {
            $result = ' selected="selected"';
        }

        return $result;
    }

    /**
     * Return the value. If the value is not saved the default value is used if
     * exists in the settingsArray.
     *
     * @return string|array
     */
    public function value()
    {
        if ($this->valueType() === 'array') {
            $default = (!empty($this->args['default']) && is_array($this->args['default'])) ? $this->args['default'] : [];
        } else {
            $default = (!empty($this->args['default'])) ? $this->args['default'] : '';
        }

        return $this->options[$this->args['field_id']] ?? $default;
    }

    /**
     * Return selected html if the value is selected in select field type
     *
     * @param string $key
     * @return string
     */
    public function selectedValue(string $key): string
    {
        $result = '';

        if ($this->value() === $key) {
            $result = ' selected="selected"';
        }

        return $result;
    }

    /**
     * Check if a checkbox has items
     *
     * @return boolean
     */
    public function hasItems(): bool
    {
        $returnValue = false;

        if (!empty($this->args['choices']) && is_array($this->args['choices'])) {
            $returnValue = true;
        }

        return $returnValue;
    }

    /**
     * Return checked html if the value is checked in radio or checkboxes
     *
     * @param string $slug
     * @return string
     */
    public function checked(string $slug): string
    {
        $value = $this->value();

        if ($this->valueType() === 'array') {
            $checked = (!empty($value[$slug]) && $value[$slug] === 'yes') ? ' checked="checked"' : '';
        } else {
            $checked = (!empty($value) && $slug === $value) ? ' checked="checked"' : '';
        }

        return $checked;
    }

    /**
     * Callback for field registration. It's required by WordPress but not used by this API
     */
    public function callback(): void
    {

    }

    /**
     * Final output on the settings page
     */
    public function renderOptions(): void
    {
        $page = filter_input(INPUT_GET, 'page');
        $settings = $this->settingsArray[$page];
        $message = get_option('rsa-message');

        if (!empty($settings['tabs']) && is_array($settings['tabs'])) {
            $tab_count = count($settings['tabs']);
            ?>
            <div class="wrap">
                <?php
                if (!empty($settings['before_tabs_text'])) {
                    echo $settings['before_tabs_text'];
                } // END if(!empty($settings['before_tabs_text']))
                ?>
                <form action='options.php' method='post'>
                    <?php
                    if ($tab_count > 1) {
                        ?>
                        <h2 class="nav-tab-wrapper">
                            <?php
                            $i = 0;
                            foreach ($settings['tabs'] as $settingsId => $section) {
                                $sanitizedId = sanitize_title($settingsId);
                                $tabTitle = (!empty($section['tab_title'])) ? $section['tab_title'] : $sanitizedId;
                                $active = ($i === 0) ? ' nav-tab-active' : '';

                                echo '<a class="nav-tab nav-tab-' . $sanitizedId . $active . '" href="#tab-content-' . $sanitizedId . '">' . $tabTitle . '</a>';

                                $i++;
                            } // END foreach($settings['tabs'] as $settings_id => $section)
                            ?>
                        </h2>

                        <?php
                        if (!empty($message)) {
                            ?>
                            <div class="updated settings-error">
                                <p><strong><?php echo $message; ?></strong></p>
                            </div>
                            <?php
                            update_option('rsa-message', '');
                        }
                    }

                    $i = 0;
                    $pageFiltered = filter_input(INPUT_GET, 'page');

                    foreach ($settings['tabs'] as $settingsId => $section) {
                        $sanitizedId = sanitize_title($settingsId);
                        $pageId = $pageFiltered . '_' . $sanitizedId;

                        $display = ($i === 0) ? ' style="display: block;"' : ' style="display:none;"';

                        echo '<div class="tab-content" id="tab-content-' . $sanitizedId . '"' . $display . '>';

                        settings_fields('section_page_' . $pageFiltered . '_' . $sanitizedId);
                        do_settings_sections($pageId);

                        echo '</div>';

                        $i++;
                    }

                    submit_button();
                    ?>
                </form>
                <?php
                if (!empty($settings['after_tabs_text'])) {
                    echo $settings['after_tabs_text'];
                } // END if(!empty($settings['after_tabs_text']))
                ?>
            </div>
            <?php
        }
    }

    /**
     * Register scripts
     */
    public function enqueueScripts(): void
    {
        if ($this->isSettingsPage() === true) {
//            \wp_enqueue_media();
//            \wp_enqueue_script('wp-color-picker');
//            \wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script(
                'settings-api', (WP_DEBUG === true) ? PluginHelper::getInstance()->getPluginUri('js/settings-api.js') : PluginHelper::getInstance()->getPluginUri('js/settings-api.min.js')
            );
        }
    }

    /**
     * Register styles
     */
    public function enqueueStyles(): void
    {
        if ($this->isSettingsPage() === true) {
//            \wp_enqueue_style('wp-color-picker');
//            \wp_enqueue_style('jquery-ui', Helper\PluginHelper::getInstance()->getPluginUri('css/jquery-ui.min.css'));
//            \wp_enqueue_style(
//                'font-awesome',
//                (\WP_DEBUG === true) ? Helper\PluginHelper::getInstance()->getPluginUri('css/font-awesome.css') : Helper\PluginHelper::getInstance()->getPluginUri('css/font-awesome.min.css')
//            );
            wp_enqueue_style(
                'settings-api', (WP_DEBUG === true) ? PluginHelper::getInstance()->getPluginUri('css/settings-api.css') : PluginHelper::getInstance()->getPluginUri('css/settings-api.min.css')
            );
        }
    }

    /**
     * Register Adimin Scripts
     */
    public function adminScripts(): void
    {
        if ($this->isSettingsPage() === true) {
            ?>
            <script>
                jQuery(document).ready(function ($) {
                    <?php
                    $settingsArray = $this->settingsArray;

                    foreach($settingsArray as $page) {
                        foreach($page['tabs'] as $tab) {
                            foreach($tab['fields'] as $fieldKey => $field) {
                                if($field['type'] === 'datepicker') {
                                    $wpDateFormat = get_option('date_format');
                                    if (empty($wpDateFormat)) {
                                        $wpDateFormat = 'yy-mm-dd';
                                    }

                                    $dateFormat = (!empty($field['format'])) ? $field['format'] : $wpDateFormat;
                                    ?>
                                    $('[data-id="<?php echo $fieldKey; ?>"]').datepicker({
                                        dateFormat: '<?php echo $dateFormat; ?>'
                                    });
                                    <?php
                                }
                            }
                        }
                    }
                    ?>
                });
            </script>
            <?php
        }
    }
}
