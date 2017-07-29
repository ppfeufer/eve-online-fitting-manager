<?php
/**
 * WordPress Settings API
 * by H.-Peter Pfeufer
 * Inspired by: http://www.wp-load.com/register-settings-api/
 *
 * Usage:
 *		$settingsApi = new SettingsApi($settingsFilterName, $defaultOptions);
 *		$settingsApi->init();
 *
 * @version 0.1
 */

namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

class SettingsApi {
	/**
	 * Settings Arguments
	 *
	 * @var array
	 */
	private $args = null;

	/**
	 * Settings Array
	 *
	 * @var array
	 */
	private $settingsArray = null;

	/**
	 * Settings Filter Name
	 *
	 * @var string
	 */
	private $settingsFilter = null;

	/**
	 * Default Options
	 *
	 * @var array
	 */
	private $optionsDefault = null;

	/**
	 * Constructor
	 *
	 * @param string $settingsFilter The name of your settings filter
	 * @param array $defaultOptions Your default options array
	 */
	public function __construct($settingsFilter, $defaultOptions) {
		$this->settingsFilter = $settingsFilter;
		$this->optionsDefault = $defaultOptions;
	} // END public function __construct($settingsFilter, $defaultOptions)

	/**
	 * Initializing all actions
	 */
	public function init() {
		\add_action('init', array($this, 'initSettings'));
		\add_action('admin_menu', array($this, 'menuPage'));
		\add_action('admin_init', array($this, 'registerFields'));
		\add_action('admin_init', array($this, 'registerCallback'));
		\add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
		\add_action('admin_enqueue_scripts', array($this, 'enqueueStyles'));
	} // END public function init()

	/**
	 * Init settings runs before admin_init
	 * Put $settingsArray to private variable
	 * Add admin_head for needed inline scripts
	 */
	public function initSettings() {
		if(\is_admin()) {
			$this->settingsArray = \apply_filters($this->settingsFilter, array());

			if($this->isSettingsPage() === true) {
				\add_action('admin_head', array($this, 'adminScripts'));
			} // END if($this->isSettingsPage() === true)
		} // END if(is_admin())
	} // END public function initSettings()

	/**
	 * Creating pages and menus from the settingsArray
	 */
	public function menuPage() {
		foreach($this->settingsArray as $menu_slug => $options) {
			if(!empty($options['page_title']) && !empty($options['menu_title']) && !empty($options['option_name'])) {
				/**
				 * Set capabilities
				 * If none is set, 'manage_options' will be default
				 */
				$options['capability'] = (!empty($options['capability']) ) ? $options['capability'] : 'manage_options';

				/**
				 * Set type
				 * If none is set, 'plugin' will be default
				 *
				 * Supported types:
				 *		theme	=> Adds the options page to Appearance menu
				 *		plugin	=> Adds the options page to Settings menu
				 */
				$options['type'] = (!empty($options['type']) ) ? $options['type'] : 'plugin';

				switch($options['type']) {
					// Adding theme settings page
					case 'theme':
						\add_theme_page(
							$options['page_title'],
							$options['menu_title'],
							$options['capability'],
							$menu_slug,
							array(
								$this,
								'renderOptions'
							)
						);
						break;

					// Adding plugin settings page
					case 'plugin':
						\add_options_page(
							$options['page_title'],
							$options['menu_title'],
							$options['capability'],
							$menu_slug,
							array(
								$this,
								'renderOptions'
							)
						);
						break;
				} // END switch($options['type'])
			} // END if(!empty($options['page_title']) && !empty($options['menu_title']) && !empty($options['option_name']))
		} // END foreach($this->settingsArray as $menu_slug => $options)
	} // END public function menuPage()

	/**
	 * Register all fields and settings bound to it from the settingsArray
	 */
	public function registerFields() {
		foreach($this->settingsArray as $pageId => $settings) {
			if(!empty($settings['tabs']) && \is_array($settings['tabs'])) {
				foreach($settings['tabs'] as $tabId => $item) {
					$sanitizedTabId = \sanitize_title($tabId);
					$tab_description = (!empty($item['tab_description']) ) ? $item['tab_description'] : '';
					$this->section_id = $sanitizedTabId;
					$settingArgs = array(
						'option_group' => 'section_page_' . $pageId . '_' . $sanitizedTabId,
						'option_name' => $settings['option_name']
					);

					\register_setting($settingArgs['option_group'], $settingArgs['option_name']);

					$sectionArgs = array(
						'id' => 'section_id_' . $sanitizedTabId,
						'title' => $tab_description,
						'callback' => 'callback',
						'menu_page' => $pageId . '_' . $sanitizedTabId
					);

					\add_settings_section(
						$sectionArgs['id'], $sectionArgs['title'], array($this, $sectionArgs['callback']), $sectionArgs['menu_page']
					);

					if(!empty($item['fields']) && \is_array($item['fields'])) {
						foreach($item['fields'] as $fieldId => $field) {
							if(\is_array($field)) {
								$sanitizedFieldId = \sanitize_title($fieldId);
								$title = (!empty($field['title']) ) ? $field['title'] : '';
								$field['field_id'] = $sanitizedFieldId;
								$field['option_name'] = $settings['option_name'];
								$fieldArgs = array(
									'id' => 'field' . $sanitizedFieldId,
									'title' => $title,
									'callback' => 'renderFields',
									'menu_page' => $pageId . '_' . $sanitizedTabId,
									'section' => 'section_id_' . $sanitizedTabId,
									'args' => $field
								);

								\add_settings_field(
									$fieldArgs['id'], $fieldArgs['title'], array($this, $fieldArgs['callback']), $fieldArgs['menu_page'], $fieldArgs['section'], $fieldArgs['args']
								);
							} // END if(is_array($field))
						} // END foreach($item['fields'] as $field_id => $field)
					} // END if(!empty($item['fields']) && is_array($item['fields']))
				} // END foreach($settings['tabs'] as $tab_id => $item)
			} // END if(!empty($settings['tabs']) && is_array($settings['tabs']))
		} // END foreach($this->settingsArray as $page_id => $settings)
	} // END public function registerFields()

	/**
	 * Register callback is used for the button field type when user click the button
	 * For now only works with plugin settings
	 */
	public function registerCallback() {
		if($this->isSettingsPage() === true) {
			$callbackFiltered = \filter_input(INPUT_GET, 'callback');

			if(!empty($callbackFiltered)) {
				$wpNonce = \filter_input(INPUT_GET, '_wpnonce');
				$nonce = \wp_verify_nonce($wpNonce);

				if(!empty($nonce)) {
					$callbackFunction = \filter_input(INPUT_GET, 'callback');
					if(function_exists($callbackFunction)) {
						$message = \call_user_func($callbackFunction);
						\update_option('rsa-message', $message);

						$page = \filter_input(INPUT_GET, 'page');
						$url = \admin_url('options-general.php?page=' . $page);
						\wp_redirect($url);

						die;
					} // END if(function_exists($callbackFunction))
				} // END if(!empty($nonce))
			} // END if(!empty($callbackFunction))
		} // END if($this->isSettingsPage() === true)
	} // END public function registerCallback()

	/**
	 * Check if the current page is a settings page
	 *
	 * @return boolean
	 */
	public function isSettingsPage() {
		$menus = array();
		$getPageFiltered = \filter_input(INPUT_GET, 'page');
		$getPage = (!empty($getPageFiltered) ) ? $getPageFiltered : '';

		foreach($this->settingsArray as $menu => $page) {
			$menus[] = $menu;
		} // END foreach($this->settingsArray as $menu => $page)

		if(\in_array($getPage, $menus)) {
			return true;
		} else {
			return false;
		} // END if(in_array($get_page, $menus))
	} // END public function isSettingsPage()

	/**
	 * Return an array for the choices in a select field type
	 *
	 * @return array
	 */
	public function selectChoices() {
		$items = array();

		if(!empty($this->args['choices']) && \is_array($this->args['choices'])) {
			foreach($this->args['choices'] as $slug => $choice) {
				$items[$slug] = $choice;
			} // END foreach($this->args['choices'] as $slug => $choice)
		} // END if(!empty($this->args['choices']) && is_array($this->args['choices']))

		return $items;
	} // END public function selectChoices()

	/**
	 * Get values from built in WordPress functions
	 *
	 * @return array
	 */
	public function get() {
		if(!empty($this->args['get'])) {
			$itemArray = \call_user_func_array(array($this, 'get' . ucfirst(EveOnlineFittingManager\Helper\StringHelper::camelCase($this->args['get']))), array($this->args));
		} elseif(!empty($this->args['choices'])) {
			$itemArray = $this->selectChoices($this->args);
		} else {
			$itemArray = array();
		} // END if(!empty($this->args['get']))

		return $itemArray;
	} // END public function get()

	/**
	 * Get users from WordPress, used by the select field type
	 *
	 * @return array
	 */
	public function getUsers() {
		$items = array();
		$args = (!empty($this->args['args'])) ? $this->args['args'] : null;
		$users = \get_users($args);

		foreach($users as $user) {
			$items[$user->ID] = $user->display_name;
		} // END foreach($users as $user)

		return $items;
	} // END public function get_users()

	/**
	 * Get menus from WordPress, used by the select field type
	 *
	 * @return array
	 */
	public function getMenus() {
		$items = array();
		$menus = \get_registered_nav_menus();

		if(!empty($menus)) {
			foreach($menus as $location => $description) {
				$items[$location] = $description;
			} // END foreach($menus as $location => $description)
		} // END if(!empty($menus))

		return $items;
	} // END public function get_menus()

	/**
	 * Get posts from WordPress, used by the select field type
	 *
	 * @global type $post
	 * @return array
	 */
	public function getPosts() {
		$items = null;

		if($this->args['get'] === 'posts' && !empty($this->args['post_type'])) {
			$args = array(
				'category' => 0,
				'post_type' => 'post',
				'post_status' => 'publish',
				'orderby' => 'post_date',
				'order' => 'DESC',
				'suppress_filters' => true
			);

			$theQuery = new \WP_Query($args);

			if($theQuery->have_posts()) {
				while($theQuery->have_posts()) {
					$theQuery->the_post();

					global $post;

					$items[$post->ID] = \get_the_title();
				} // END while($the_query->have_posts())
			} // END if($the_query->have_posts())

			\wp_reset_postdata();
		} // END if($this->args['get'] === 'posts' && !empty($this->args['post_type']))

		return $items;
	} // END public function get_posts()

	/**
	 * Get terms from WordPress, used by the select field type
	 *
	 * @return array
	 */
	public function getTerms() {
		$items = array();
		$taxonomies = (!empty($this->args['taxonomies']) ) ? $this->args['taxonomies'] : null;
		$args = (!empty($this->args['args'])) ? $this->args['args'] : null;
		$terms = \get_terms($taxonomies, $args);

		if(!empty($terms)) {
			foreach($terms as $key => $term) {
				$items[$term->term_id] = $term->name;
			} // END foreach($terms as $key => $term)
		} // END if(!empty($terms))

		return $items;
	} // END public function get_terms()

	/**
	 * Get taxonomies from WordPress, used by the select field type
	 *
	 * @return array
	 */
	public function getTaxonomies() {
		$items = array();
		$args = (!empty($this->args['args'])) ? $this->args['args'] : null;
		$taxonomies = \get_taxonomies($args, 'objects');

		if(!empty($taxonomies)) {
			foreach($taxonomies as $taxonomy) {
				$items[$taxonomy->name] = $taxonomy->label;
			} // END foreach($taxonomies as $taxonomy)
		} // END if(!empty($taxonomies))

		return $items;
	} // END public function get_taxonomies()

	/**
	 * Get sidebars from WordPress, used by the select field type
	 *
	 * @global type $wp_registered_sidebars
	 * @return array
	 */
	public function getSidebars() {
		$items = array();

		global $wp_registered_sidebars;

		if(!empty($wp_registered_sidebars)) {
			foreach($wp_registered_sidebars as $sidebar) {
				$items[$sidebar['id']] = $sidebar['name'];
			} // END foreach($wp_registered_sidebars as $sidebar)
		} // END if(!empty($wp_registered_sidebars))

		return $items;
	} // END public function get_sidebars()

	/**
	 * Get themes from WordPress, used by the select field type
	 *
	 * @return array
	 */
	public function getThemes() {
		$items = array();
		$args = (!empty($this->args['args'])) ? $this->args['args'] : null;
		$themes = \wp_get_themes($args);

		if(!empty($themes)) {
			foreach($themes as $key => $theme) {
				$items[$key] = $theme->get('Name');
			} // END foreach($themes as $key => $theme)
		} // END if(!empty($themes))

		return $items;
	} // END public function get_themes()

	/**
	 * Get plugins from WordPress, used by the select field type
	 *
	 * @return array
	 */
	public function getPlugins() {
		$items = array();
		$args = (!empty($this->args['args'])) ? $this->args['args'] : null;
		$plugins = \get_plugins($args);

		if(!empty($plugins)) {
			foreach($plugins as $key => $plugin) {
				$items[$key] = $plugin['Name'];
			} // END foreach($plugins as $key => $plugin)
		} // END if(!empty($plugins))

		return $items;
	} // END public function get_plugins()

	/**
	 * Get post_types from WordPress, used by the select field type
	 *
	 * @return array
	 */
	public function getPostTypes() {
		$items = array();
		$args = (!empty($this->args['args'])) ? $this->args['args'] : null;
		$postTypes = \get_post_types($args, 'objects');

		if(!empty($postTypes)) {
			foreach($postTypes as $key => $post_type) {
				$items[$key] = $post_type->name;
			} // END foreach($post_types as $key => $post_type)
		} // END if(!empty($post_types))

		return $items;
	} // END public function get_post_types()

	/**
	 * Find a selected value in select or multiselect field type
	 *
	 * @param type $key
	 * @return string
	 */
	public function selected($key) {
		if($this->valueType() == 'array') {
			return $this->multiselectedValue($key);
		} else {
			return $this->selectedValue($key);
		} // END if($this->valueType() == 'array')
	} // END public function selected($key)

	/**
	 * Return selected html if the value is selected in select field type
	 *
	 * @param type $key
	 * @return string
	 */
	public function selectedValue($key) {
		$result = '';

		if($this->value($this->options, $this->args) === $key) {
			$result = ' selected="selected"';
		} // END if($this->value($this->options, $this->args) === $key)

		return $result;
	} // END public function selectedValue($key)

	/**
	 * Return selected html if the value is selected in multiselect field type
	 *
	 * @param type $key
	 * @return string
	 */
	public function multiselectedValue($key) {
		$result = '';
		$value = $this->value($this->options, $this->args, $key);

		if(\is_array($value) && \in_array($key, $value)) {
			$result = ' selected="selected"';
		} // END if(is_array($value) && in_array($key, $value))

		return $result;
	} // END public function multiselectedValue($key)

	/**
	 * Return checked html if the value is checked in radio or checkboxes
	 *
	 * @param type $slug
	 * @return string
	 */
	public function checked($slug) {
		$value = $this->value();

		if($this->valueType() === 'array') {
			$checked = (!empty($value) && \in_array($slug, $this->value())) ? ' checked="checked"' : '';
		} else {
			$checked = (!empty($value) && $slug == $this->value()) ? ' checked="checked"' : '';
		} // END if($this->valueType() == 'array')

		return $checked;
	} // END public function checked($slug)

	/**
	 * Return the value. If the value is not saved the default value is used if
	 * exists in the settingsArray.
	 *
	 * @param type $key
	 * @return string|array
	 */
	public function value($key = null) {
		$value = '';

		if($this->valueType() === 'array') {
			$default = (!empty($this->args['default']) && \is_array($this->args['default'])) ? $this->args['default'] : array();
		} else {
			$default = (!empty($this->args['default'])) ? $this->args['default'] : '';
		} // END if($this->valueType() == 'array')

		$value = (isset($this->options[$this->args['field_id']])) ? $this->options[$this->args['field_id']] : $default;

		return $value;
	} // END public function value($key = null)

	/**
	 * Check if the current value type is a single value or a multiple value
	 * field type, return string or array
	 *
	 * @return string
	 */
	public function valueType() {
		$defaultSingle = array(
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
		);
		$defaultMultiple = array('multiselect', 'checkbox');
		$value = '';

		if(\in_array($this->args['type'], $defaultSingle)) {
			return 'string';
		} elseif(\in_array($this->args['type'], $defaultMultiple)) {
			return 'array';
		} // END if(in_array($this->args['type'], $default_single))
	} // END public function valueType()

	/**
	 * Check if a checkbox has items
	 *
	 * @return boolean
	 */
	public function hasItems() {
		if(!empty($this->args['choices']) && \is_array($this->args['choices'])) {
			return true;
		} // END if(!empty($this->args['choices']) && is_array($this->args['choices']))

		return false;
	} // END public function hasItems()

	/**
	 * Return the html name of the field
	 *
	 * @param string $slug
	 * @return string
	 */
	public function name($slug = '') {
		$optionName = \sanitize_title($this->args['option_name']);

		if($this->valueType() == 'array') {
			return $optionName . '[' . $this->args['field_id'] . '][' . $slug . ']';
		} else {
			return $optionName . '[' . $this->args['field_id'] . ']';
		} // END if($this->valueType() == 'array')
	} // END public function name($slug = '')

	/**
	 * Return the size of a multiselect type. If not set it will calculate it
	 *
	 * @param array $items
	 * @return string
	 */
	public function size($items) {
		$size = '';

		if($this->args['type'] == 'multiselect') {
			if(!empty($this->args['size'])) {
				$count = $this->args['size'];
			} else {
				$itemCount = \count($items);
				$count = (!empty($this->args['empty'])) ? $itemCount + 1 : $itemCount;
			} // END if(!empty($this->args['size']))

			$size = ' size="' . $count . '"';
		} // END if($this->args['type'] == 'multiselect')

		return $size;
	} // END public function size($items)

	/**
	 * All the field types in html
	 *
	 * @param array $args
	 */
	public function renderFields($args) {
		$args['field_id'] = \sanitize_title($args['field_id']);
		$this->args = $args;

		$options = \get_option($args['option_name'], $this->optionsDefault);
		$this->options = $options;

//		$screen = \get_current_screen();
//		$callbackBase = \admin_url() . $screen->parent_file;

		$optionName = \sanitize_title($args['option_name']);
		$out = '';

		if(!empty($args['type'])) {
			switch($args['type']) {
				case 'info':
					if(!empty($args['infotext'])) {
						$out .= '<div class="notice notice-warning"><p>' . $args['infotext'] . '</p></div>';
					} // END if(!empty($args['infotext']))
					break;

				case 'select':
				case 'multiselect':
					$multiple = ($args['type'] == 'multiselect') ? ' multiple' : '';
					$items = $this->get($args);
					$out .= '<select' . $multiple . ' name="' . $this->name() . '"' . $this->size($items) . '>';

					if(!empty($args['empty'])) {
						$out .= '<option value="" ' . $this->selected('') . '>' . $args['empty'] . '</option>';
					} // END if(!empty($args['empty']))

					foreach($items as $key => $choice) {
						$key = \sanitize_title($key);
						$out .= '<option value="' . $key . '" ' . $this->selected($key) . '>' . $choice . '</option>';
					} // END foreach($items as $key => $choice)

					$out .= '</select>';
					break;

				case 'radio':
				case 'checkbox':
					if($this->hasItems()) {
						$horizontal = (isset($args['align']) && (string) $args['align'] == 'horizontal') ? ' class="horizontal"' : '';

						$out .= '<ul class="settings-group settings-type-' . $args['type'] . '">';

						foreach($args['choices'] as $slug => $choice) {
							$checked = $this->checked($slug);

							$out .= '<li' . $horizontal . '><label>';
							$out .= '<input value="' . $slug . '" type="' . $args['type'] . '" name="' . $this->name($slug) . '"' . $checked . '>';
							$out .= $choice;
							$out .= '</label></li>';
						} // END foreach($args['choices'] as $slug => $choice)

						$out .= '</ul>';
					} // END if($this->hasItems())
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
					$rows = (isset($args['rows'])) ? $args['rows'] : 5;
					$out .= '<textarea rows="' . $rows . '" class="large-text" name="' . $this->name() . '">' . $this->value() . '</textarea>';
					break;

				case 'tinymce':
					$rows = (isset($args['rows'])) ? $args['rows'] : 5;
					$tinymceSettings = array(
						'textarea_rows' => $rows,
						'textarea_name' => $optionName . '[' . $args['field_id'] . ']',
					);

					\wp_editor($this->value(), $args['field_id'], $tinymceSettings);
					break;

				case 'image':
					$imageObject = (!empty($options[$args['field_id']])) ? \wp_get_attachment_image_src($options[$args['field_id']], 'thumbnail') : '';
					$image = (!empty($imageObject)) ? $imageObject[0] : '';
					$uploadStatus = (!empty($imageObject)) ? ' style="display: none"' : '';
					$removeStatus = (!empty($imageObject)) ? '' : ' style="display: none"';
					$value = (!empty($options[$args['field_id']])) ? $options[$args['field_id']] : '';
					?>
					<div data-id="<?php echo $args['field_id']; ?>">
						<div class="upload" data-field-id="<?php echo $args['field_id']; ?>"<?php echo $uploadStatus; ?>>
							<span class="button upload-button">
								<a href="#">
									<i class="fa fa-upload"></i>
									<?php echo \__('Upload'); ?>
								</a>
							</span>
						</div>
						<div class="image">
							<img class="uploaded-image" src="<?php echo $image; ?>" id="<?php echo $args['field_id']; ?>" />
						</div>
						<div class="remove"<?php echo $removeStatus; ?>>
							<span class="button upload-button">
								<a href="#">
									<i class="fa fa-trash"></i>
									<?php echo \__('Remove'); ?>
								</a>
							</span>
						</div>
						<input type="hidden" class="attachment_id" value="<?php echo $value; ?>" name="<?php echo $optionName; ?>[<?php echo $args['field_id']; ?>]">
					</div>
					<?php
					break;

				case 'file':
					$fileUrl = (!empty($options[$args['field_id']])) ? \wp_get_attachment_url($options[$args['field_id']]) : '';
					$uploadStatus = (!empty($fileUrl)) ? ' style="display: none"' : '';
					$removeStatus = (!empty($fileUrl)) ? '' : ' style="display: none"';
					$value = (!empty($options[$args['field_id']])) ? $options[$args['field_id']] : '';
					?>
					<div data-id="<?php echo $args['field_id']; ?>">
						<div class="upload" data-field-id="<?php echo $args['field_id']; ?>"<?php echo $uploadStatus; ?>>
							<span class="button upload-button">
								<a href="#">
									<i class="fa fa-upload"></i>
									<?php echo \__('Upload'); ?>
								</a>
							</span>
						</div>
						<div class="url">
							<code class="uploaded-file-url" title="Attachment ID: <?php echo $value; ?>" data-field-id="<?php echo $args['field_id']; ?>">
								<?php echo $fileUrl; ?>
							</code>
						</div>
						<div class="remove"<?php echo $removeStatus; ?>>
							<span class="button upload-button">
								<a href="#">
									<i class="fa fa-trash"></i>
									<?php echo \__('Remove'); ?>
								</a>
							</span>
						</div>
						<input type="hidden" class="attachment_id" value="<?php echo $value; ?>" name="<?php echo $optionName; ?>[<?php echo $args['field_id']; ?>]">
					</div>
					<?php
					break;

				case 'button':
					$warningMessage = (!empty($args['warning-message'])) ? $args['warning-message'] : 'Unsaved settings will be lost. Continue?';
					$warning = (!empty($args['warning'])) ? ' onclick="return confirm(' . "'" . $warningMessage . "'" . ')"' : '';
					$label = (!empty($args['label'])) ? $args['label'] : '';
					$pageFiltered = \filter_input(INPUT_GET, 'page');
					$callbackFiltered = \filter_input(INPUT_GET, 'callback');
					$completeUrl = \wp_nonce_url(\admin_url('options-general.php?page=' . $pageFiltered . '&callback=' . $callbackFiltered));
					?>
					<a href="<?php echo $completeUrl; ?>" class="button button-secondary"<?php echo $warning; ?>><?php echo $label; ?></a>
					<?php
					break;

				case 'custom':
					$value = (!empty($options[$args['field_id']])) ? $options[$args['field_id']] : null;
					$data = array(
						'value' => $value,
						'name' => $this->name(),
						'args' => $args
					);

					if($args['content'] !== null) {
						echo $args['content'];
					} // END if($args['content'] !== null)

					if($args['callback'] !== null) {
						\call_user_func($args['callback'], $data);
					} // END if($args['callback'] !== null)
					break;
			} // END switch($args['type'])
		} // END if(!empty($args['type']))

		echo $out;

		if(!empty($args['description'])) {
			echo '<p class="description">' . $args['description'] . '</div>';
		} // END if(!empty($args['description']))
	} // END public function renderFields($args)

	/**
	 * Callback for field registration. It's required by WordPress but not used by this API
	 */
	public function callback() {
	} // END public function callback()

	/**
	 * Final output on the settings page
	 */
	public function renderOptions() {
		$page = \filter_input(INPUT_GET, 'page');
		$settings = $this->settingsArray[$page];
		$message = \get_option('rsa-message');

		if(!empty($settings['tabs']) && \is_array($settings['tabs'])) {
			$tab_count = \count($settings['tabs']);
			?>
			<div class="wrap">
				<?php
				if(!empty($settings['before_tabs_text'])) {
					echo $settings['before_tabs_text'];
				} // END if(!empty($settings['before_tabs_text']))
				?>
				<form action='options.php' method='post'>
					<?php
					if($tab_count > 1) {
						?>
						<h2 class="nav-tab-wrapper">
						<?php
						$i = 0;
						foreach($settings['tabs'] as $settingsId => $section) {
							$sanitizedId = \sanitize_title($settingsId);
							$tabTitle = (!empty($section['tab_title'])) ? $section['tab_title'] : $sanitizedId;
							$active = ($i == 0) ? ' nav-tab-active' : '';

							echo '<a class="nav-tab nav-tab-' . $sanitizedId . $active . '" href="#tab-content-' . $sanitizedId . '">' . $tabTitle . '</a>';

							$i++;
						} // END foreach($settings['tabs'] as $settings_id => $section)
						?>
						</h2>

						<?php
						if(!empty($message)) {
							?>
							<div class="updated settings-error">
								<p><strong><?php echo $message; ?></strong></p>
							</div>
							<?php
							\update_option('rsa-message', '');
						} // END if(!empty($message))
					} // END if($tab_count > 1)

					$i = 0;
					$pageFiltered = \filter_input(INPUT_GET, 'page');
					foreach($settings['tabs'] as $settingsId => $section) {
						$sanitizedId = \sanitize_title($settingsId);
						$pageId = $pageFiltered . '_' . $sanitizedId;

						$display = ($i == 0) ? ' style="display: block;"' : ' style="display:none;"';

						echo '<div class="tab-content" id="tab-content-' . $sanitizedId . '"' . $display . '>';
						echo \settings_fields('section_page_' . $pageFiltered . '_' . $sanitizedId);

						\do_settings_sections($pageId);

						echo '</div>';

						$i++;
					} // END foreach($settings['tabs'] as $settings_id => $section)

//					$completeUrl = \wp_nonce_url(\admin_url('options-general.php?page=' . $pageFiltered. '&callback=rsa_delete_settings'));

					\submit_button();
					?>
				</form>
				<?php
				if(!empty($settings['after_tabs_text'])) {
					echo $settings['after_tabs_text'];
				} // END if(!empty($settings['after_tabs_text']))
				?>
			</div>
			<?php
		} // END if(!empty($settings['tabs']) && is_array($settings['tabs']))
	} // END public function renderOptions()

	/**
	 * Register scripts
	 */
	public function enqueueScripts() {
		if($this->isSettingsPage() === true) {
//			\wp_enqueue_media();
//			\wp_enqueue_script('wp-color-picker');
//			\wp_enqueue_script('jquery-ui-datepicker');
			\wp_enqueue_script(
				'settings-api',
				(\WP_DEBUG === true) ? EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('js/settings-api.js') : EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('js/settings-api.min.js')
			);
		} // END if($this->isSettingsPage() === true)
	} // END public function enqueueScripts()

	/**
	 * Register styles
	 */
	public function enqueueStyles() {
		if($this->isSettingsPage() === true) {
//			\wp_enqueue_style('wp-color-picker');
//			\wp_enqueue_style('jquery-ui', EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('css/jquery-ui.min.css'));
//			\wp_enqueue_style(
//				'font-awesome',
//				(\WP_DEBUG === true) ? EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('css/font-awesome.css') : EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('css/font-awesome.min.css')
//			);
			\wp_enqueue_style(
				'settings-api',
				(\WP_DEBUG === true) ? EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('css/settings-api.css') : EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('css/settings-api.min.css')
			);
		} // END if($this->isSettingsPage() === true)
	} // END public function enqueueStyles()

	/**
	 * Register Adimin Scripts
	 */
	public function adminScripts() {
		if($this->isSettingsPage() === true) {
			?>
			<script>
			jQuery(document).ready(function($) {
				<?php
				$settingsArray = $this->settingsArray;

				foreach($settingsArray as $page) {
					foreach($page['tabs'] as $tab) {
						foreach($tab['fields'] as $fieldKey => $field) {
							if($field['type'] == 'datepicker') {
								$wpDateFormat = \get_option('date_format');
								if(empty($wpDateFormat)) {
									$wpDateFormat = 'yy-mm-dd';
								} // END if(empty($wpDateFormat))

								$dateFormat = (!empty($field['format']) ) ? $field['format'] : $wpDateFormat;
								?>
								$('[data-id="<?php echo $fieldKey; ?>"]').datepicker({
									dateFormat: '<?php echo $dateFormat; ?>'
								});
								<?php
							} // END if($field['type'] == 'datepicker')
						} // END foreach($tab['fields'] as $field_key => $field)
					} // END foreach($page['tabs'] as $tab)
				} // END foreach($settingsArray as $page)
				?>
			});
			</script>
			<?php
		} // END if($this->isSettingsPage() === true)
	} // END public function adminScripts()
} // END class SettingsApi
