<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('Rigid_Transfer_Content')) {

	/**
	 * Singelton class to manage import/export functionality
	 *
	 * @author aatanasov
	 */
	class Rigid_Transfer_Content {

		/**
		 * Current theme options
		 *
		 * @var String
		 */
		private $theme_options;

		/**
		 * Location of demo files
		 *
		 * @var String
		 */
		private $demo_location;

		/**
		 * Export file name
		 *
		 * @var String
		 */
		public $export_filename;

		/**
		 * Delimiter for separating different settings in the file
		 *
		 * @var String
		 */
		private $delimiter;

		/**
		 * Returns the *Rigid_Transfer_Content* instance of this class.
		 *
		 * @staticvar Singleton $instance The *Rigid_Transfer_Content* instances of this class.
		 *
		 * @return Rigid_Transfer_Content The *Rigid_Transfer_Content* instance.
		 */
		public static function getInstance() {
			static $instance = null;
			if ($instance === null) {
				$instance = new Rigid_Transfer_Content();
			}

			return $instance;
		}

		/**
		 * Protected constructor to prevent creating a new instance of the
		 * *Rigid_Transfer_Content* via the `new` operator from outside of this class.
		 */
		protected function __construct() {

			$this->setDelimiter('|||');
			$this->export_filename = get_template_directory() . '/store/settings/' . get_bloginfo('name') . '_settings_' . date('Y_m_d') . '.txt';
			$this->demo_location = get_template_directory() . '/store/demo/';

			global $wp_filesystem;

			if (empty($wp_filesystem)) {
				require_once (ABSPATH . '/wp-admin/includes/file.php');
				WP_Filesystem();
			}
		}

		/**
		 * Private clone method to prevent cloning of the instance of the
		 * *Rigid_Transfer_Content* instance.
		 *
		 * @return void
		 */
		private function __clone() {

		}

		/**
		 * Private unserialize method to prevent unserializing of the *Rigid_Transfer_Content*
		 * instance.
		 *
		 * @return void
		 */
		private function __wakeup() {

		}

		/**
		 * Set delimiter
		 *
		 * @return String
		 */
		public function getDelimiter() {
			return $this->delimiter;
		}

		/**
		 * Get delimiter
		 *
		 * @param String $delimiter
		 */
		public function setDelimiter($delimiter) {
			$this->delimiter = $delimiter;
		}

		/**
		 * Get demo location
		 *
		 * @return String
		 */
		public function getDemoLocation() {
			return $this->demo_location;
		}

		/**
		 * Gets the theme name from the stylesheet (lowercase and without spaces)
		 *
		 * @return String
		 */
		private function getCurrentThemeDirToLower() {
			// This gets the theme name from the stylesheet (lowercase and without spaces)
			$themename_orig = get_option('stylesheet');
			$themename = preg_replace("/\W/", "_", strtolower($themename_orig));

			return $themename;
		}

		/**
		 * Get theme options
		 *
		 * @return String theme options
		 */
		private function getThemeOptions() {
			if (!$this->theme_options) {
				$this->theme_options = get_option($this->getCurrentThemeDirToLower());
			}

			return $this->theme_options;
		}

		/**
		 * Encodes the options and stores the export.
		 *
		 * @return int Result
		 */
		protected function getEncodedOptions() {

			$encodedOptions = json_encode($this->getThemeOptions());

			return $encodedOptions;
		}

		/**
		 * Stores given file.
		 *
		 * @param String $filename Filename of the export file
		 * @param String $data Data to be stored
		 * @return int Result
		 */
		protected function storeFile($filename, $data) {

			/**
			 * @global WP_Filesystem_Base $wp_filesystem subclass
			 */
			global $wp_filesystem;

			return $wp_filesystem->put_contents($filename, $data);
		}

		/**
		 * Decodes settings during Import
		 *
		 * @param String $encoded_settings Encoded settings
		 * @return mixed
		 */
		protected function decodeSettings($encoded_settings) {
			return json_decode($encoded_settings, TRUE);
		}

		/**
		 * Imports encoded options
		 *
		 * @param String $encoded_options Encoded options
		 */
		protected function importOptions($encoded_options) {
			$options = $this->decodeSettings($encoded_options);

			update_option($this->getCurrentThemeDirToLower(), $options);
		}

		/**
		 * Import WP content
		 */
		public function importWPContent($file) {

			global $wpdb;

			add_filter("http_request_args", array(&$this, "setHttpRequestTimeout"), 10, 1);

			if (class_exists('RigidImport')) {

				$wp_import = new RigidImport();
				$wp_import->fetch_attachments = true;
				$wp_import->import($file);
			}

			remove_filter("http_request_args", array(&$this, "setHttpRequestTimeout"));
		}

		public function setHttpRequestTimeout($req) {
			$req["timeout"] = 360;
			return $req;
		}

		protected function getEncodedWidgets() {

			$sidebars_array = get_option('sidebars_widgets');
			$widget_types = array();
			$all_widgets_options = array();

			// get all registered widget types
			foreach ($sidebars_array as $sidebar_name => $widgets) {
				if ('wp_inactive_widgets' !== $sidebar_name && is_array($widgets)) {
					foreach ($widgets as $widget_index) {
						$widget_types[] = trim(substr($widget_index, 0, strrpos($widget_index, '-')));
					}
				}
			}

			// remove duplicates
			array_unique($widget_types);

			// get widget values for each type
			foreach ($widget_types as $widget_type) {
				$all_widgets_options['widget_' . $widget_type] = get_option('widget_' . $widget_type);
			}

			$sidebars_and_widgets = array('sidebars' => $sidebars_array, 'widgets' => $all_widgets_options);
			$encodedWidgets = json_encode($sidebars_and_widgets);

			return $encodedWidgets;
		}

		public function exportSettings() {

			$encodedOptions = $this->getEncodedOptions();
			$encodedWidgets = $this->getEncodedWidgets();

			return $this->storeFile($this->export_filename, $encodedOptions . $this->getDelimiter() . $encodedWidgets);
		}

		public function importSettings($filename, $widget_menu_1, $widget_menu_2, $widget_menu_3) {
			/**
			 * @global WP_Filesystem_Base $wp_filesystem subclass
			 */
			global $wp_filesystem;

			$file_error = false;
			$data = $wp_filesystem->get_contents($filename);

			if ($data) {
				$settings_array = explode($this->getDelimiter(), $data);

				if ( is_array( $settings_array ) && ! empty( $settings_array ) ) {
					$options              = $this->decodeSettings( $settings_array[0] );
					$sidebars_and_widgets = $this->decodeSettings( $settings_array[1] );

					update_option( $this->getCurrentThemeDirToLower(), $options );
					update_option( 'sidebars_widgets', $sidebars_and_widgets['sidebars'] );

					foreach ( $sidebars_and_widgets['widgets'] as $widget_option_name => $widget_options ) {

						if ( $widget_option_name == 'widget_nav_menu' ) {
							foreach ( $widget_options as $key => $option ) {
								if ( strcasecmp($option['title'], 'Information') == 0 && $widget_menu_1 ) {
									$widget_options[ $key ]['nav_menu'] = $widget_menu_1->term_id;
								} elseif ( strcasecmp($option['title'], 'Extras') == 0 && $widget_menu_2 ) {
									$widget_options[ $key ]['nav_menu'] = $widget_menu_2->term_id;
								} elseif ( strcasecmp($option['title'], 'The Shop') == 0 && $widget_menu_3 ) {
									$widget_options[ $key ]['nav_menu'] = $widget_menu_3->term_id;
								}
							}

						}
						update_option( $widget_option_name, $widget_options );


					}
				} else {
					$file_error = true;
				}
			}

			if ($file_error) {
				return new WP_Error('settings_import_file_error', esc_html__('There was error with settings file.', 'rigid'));
			}
		}

		/**
		 * Import all revolution sliders
		 *
		 * @global type $wpdb
		 * @param type $demo_name
		 * @return boolean
		 */
		public function importRevSliders($demo_name = 'one') {
			if (!class_exists('RevSliderFunctions')) {
				return false;
			}

			global $wpdb;
			global $wp_filesystem;

			$updateStatic = "true";
			$is_template = false;
			$single_slide = true;
			$updateAnim = true;
			$rev_directory = $this->getDemoLocation() . $demo_name . '/revsliders/';

			foreach (glob($rev_directory . '*.zip') as $filename) { // get all files from revsliders data dir
				$filename = basename($filename);
				$rev_files[] = $rev_directory . $filename;
			}

			if (!isset($rev_files) || !is_array($rev_files)) {
				return false;
			}

			foreach ($rev_files as $rev_file) { // finally import rev slider data files
				$sliderID = substr(substr($rev_file, 0, -4), strrpos($rev_file, '_') + 1);

				$filepath = $rev_file;

				$importZip = false;

				$d_path = $rev_directory;
				$unzipfile = unzip_file($filepath, $d_path);

				if (is_wp_error($unzipfile)) {
					new WP_Error('rigid_import_rev_zip_error', esc_html__('Revolution Slider extract error.', 'rigid'));
				}

				if (!is_wp_error($unzipfile)) {
					$importZip = true;
					$db = new RevSliderDB();

					//read all files needed
					$content = ( $wp_filesystem->exists($d_path . 'slider_export.txt') ) ? $wp_filesystem->get_contents($d_path . 'slider_export.txt') : '';
					if ($content == '') {
						dmp(__("slider_export.txt not found", "rigid"));
						return;
					}
					$animations = ( $wp_filesystem->exists($d_path . 'custom_animations.txt') ) ? $wp_filesystem->get_contents($d_path . 'custom_animations.txt') : '';
					$dynamic = ( $wp_filesystem->exists($d_path . 'dynamic-captions.css') ) ? $wp_filesystem->get_contents($d_path . 'dynamic-captions.css') : '';
					$static = ( $wp_filesystem->exists($d_path . 'static-captions.css') ) ? $wp_filesystem->get_contents($d_path . 'static-captions.css') : '';
					$navigations = ( $wp_filesystem->exists($d_path . 'navigation.txt') ) ? $wp_filesystem->get_contents($d_path . 'navigation.txt') : '';

					//update/insert custom animations
					$animations = @unserialize($animations);
					if (!empty($animations)) {
						foreach ($animations as $key => $animation) { //$animation['id'], $animation['handle'], $animation['params']
							$exist = $db->fetch(RevSliderGlobals::$table_layer_anims, "handle = '" . $animation['handle'] . "'");
							if (!empty($exist)) { //update the animation, get the ID
								if ($updateAnim == "true") { //overwrite animation if exists
									$arrUpdate = array();
									$arrUpdate['params'] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));
									$db->update(RevSliderGlobals::$table_layer_anims, $arrUpdate, array('handle' => $animation['handle']));

									$anim_id = $exist['0']['id'];
								} else { //insert with new handle
									$arrInsert = array();
									$arrInsert["handle"] = 'copy_' . $animation['handle'];
									$arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));

									$anim_id = $db->insert(RevSliderGlobals::$table_layer_anims, $arrInsert);
								}
							} else { //insert the animation, get the ID
								$arrInsert = array();
								$arrInsert["handle"] = $animation['handle'];
								$arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));

								$anim_id = $db->insert(RevSliderGlobals::$table_layer_anims, $arrInsert);
							}

							//and set the current customin-oldID and customout-oldID in slider params to new ID from $id
							$content = str_replace(array('customin-' . $animation['id'] . '"', 'customout-' . $animation['id'] . '"'), array('customin-' . $anim_id . '"', 'customout-' . $anim_id . '"'), $content);
						}
						dmp(__("animations imported!", "rigid"));
					} else {
						dmp(__("no custom animations found, if slider uses custom animations, the provided export may be broken...", "rigid"));
					}

					//overwrite/append static-captions.css
					if (!empty($static)) {
						if ($updateStatic == "true") { //overwrite file
							RevSliderOperations::updateStaticCss($static);
						} elseif ($updateStatic == 'none') {
							//do nothing
						} else {//append
							$static_cur = RevSliderOperations::getStaticCss();
							$static = $static_cur . "\n" . $static;
							RevSliderOperations::updateStaticCss($static);
						}
					}
					//overwrite/create dynamic-captions.css
					//parse css to classes
					$dynamicCss = RevSliderCssParser::parseCssToArray($dynamic);

					if (is_array($dynamicCss) && $dynamicCss !== false && count($dynamicCss) > 0) {
						foreach ($dynamicCss as $class => $styles) {
							//check if static style or dynamic style
							$class = trim($class);

							if (strpos($class, ',') !== false && strpos($class, '.tp-caption') !== false) { //we have something like .tp-caption.redclass, .redclass
								$class_t = explode(',', $class);
								foreach ($class_t as $k => $cl) {
									if (strpos($cl, '.tp-caption') !== false)
										$class = $cl;
								}
							}

							if ((strpos($class, ':hover') === false && strpos($class, ':') !== false) || //before, after
											strpos($class, " ") !== false || // .tp-caption.imageclass img or .tp-caption .imageclass or .tp-caption.imageclass .img
											strpos($class, ".tp-caption") === false || // everything that is not tp-caption
											(strpos($class, ".") === false || strpos($class, "#") !== false) || // no class -> #ID or img
											strpos($class, ">") !== false) { //.tp-caption>.imageclass or .tp-caption.imageclass>img or .tp-caption.imageclass .img
								continue;
							}

							//is a dynamic style
							if (strpos($class, ':hover') !== false) {
								$class = trim(str_replace(':hover', '', $class));
								$arrInsert = array();
								$arrInsert["hover"] = json_encode($styles);
								$arrInsert["settings"] = json_encode(array('hover' => 'true'));
							} else {
								$arrInsert = array();
								$arrInsert["params"] = json_encode($styles);
								$arrInsert["settings"] = '';
							}
							//check if class exists
							$result = $db->fetch(RevSliderGlobals::$table_css, "handle = '" . $class . "'");

							if (!empty($result)) { //update
								$db->update(RevSliderGlobals::$table_css, $arrInsert, array('handle' => $class));
							} else { //insert
								$arrInsert["handle"] = $class;
								$db->insert(RevSliderGlobals::$table_css, $arrInsert);
							}
						}
						dmp(__("dynamic styles imported!", "rigid"));
					} else {
						dmp(__("no dynamic styles found, if slider uses dynamic styles, the provided export may be broken...", "rigid"));
					}
				}

				$content = preg_replace_callback('!s:(\d+):"(.*?)";!', array('RevSlider', 'clear_error_in_string'), $content); //clear errors in string

				$arrSlider = @unserialize($content);
				if (empty($arrSlider)) {
					RevSliderFunctions::throwError("Wrong export slider file format! This could be caused because the ZipArchive extension is not enabled.");
				}

				//update slider params
				$sliderParams = $arrSlider["params"];

				if (isset($sliderParams["background_image"])) {
					$sliderParams["background_image"] = RevSliderFunctionsWP::getImageUrlFromPath($sliderParams["background_image"]);
				}

				$import_statics = true;
				if (isset($sliderParams['enable_static_layers'])) {
					if ($sliderParams['enable_static_layers'] == 'off')
						$import_statics = false;
					unset($sliderParams['enable_static_layers']);
				}

				$json_params = json_encode($sliderParams);

				// delete current slider and slides
				$db->delete(RevSliderGlobals::$table_sliders, "id=" . $sliderID);
				$db->delete(RevSliderGlobals::$table_slides, "slider_id=" . $sliderID);

				// create new

				$arrInsert = array();
				$arrInsert['id'] = $sliderID;
				$arrInsert['params'] = $json_params;
				//check if Slider with title and/or alias exists, if yes change both to stay unique


				$arrInsert['title'] = RevSliderFunctions::getVal($sliderParams, 'title', 'Slider1');
				$arrInsert['alias'] = RevSliderFunctions::getVal($sliderParams, 'alias', 'slider1');

				$talias = $arrInsert['alias'];

				if ($talias !== $arrInsert['alias']) {
					$arrInsert['title'] = $talias;
					$arrInsert['alias'] = $talias;
				}

				$sliderID = $db->insert(RevSliderGlobals::$table_sliders, $arrInsert);


				//-------- Slides Handle -----------
				//create all slides
				$arrSlides = $arrSlider["slides"];

				$alreadyImported = array();

				//wpml compatibility
				$slider_map = array();

				foreach ($arrSlides as $sl_key => $slide) {
					$params = $slide["params"];
					$layers = $slide["layers"];
					$settings = @$slide["settings"];

					//convert params images:
					if ($importZip === true) { //we have a zip, check if exists
						if (isset($params["image"])) {
							$params["image"] = RevSliderBase::check_file_in_zip($d_path, $params["image"], $sliderParams["alias"], $alreadyImported);
							$params["image"] = RevSliderFunctionsWP::getImageUrlFromPath($params["image"]);
						}

						if (isset($params["background_image"])) {
							$params["background_image"] = RevSliderBase::check_file_in_zip($d_path, $params["background_image"], $sliderParams["alias"], $alreadyImported);
							$params["background_image"] = RevSliderFunctionsWP::getImageUrlFromPath($params["background_image"]);
						}

						if (isset($params["slide_thumb"])) {
							$params["slide_thumb"] = RevSliderBase::check_file_in_zip($d_path, $params["slide_thumb"], $sliderParams["alias"], $alreadyImported);
							$params["slide_thumb"] = RevSliderFunctionsWP::getImageUrlFromPath($params["slide_thumb"]);
						}

						if (isset($params["show_alternate_image"])) {
							$params["show_alternate_image"] = RevSliderBase::check_file_in_zip($d_path, $params["show_alternate_image"], $sliderParams["alias"], $alreadyImported);
							$params["show_alternate_image"] = RevSliderFunctionsWP::getImageUrlFromPath($params["show_alternate_image"]);
						}
						if (isset($params['background_type']) && $params['background_type'] == 'html5') {
							if (isset($params['slide_bg_html_mpeg']) && $params['slide_bg_html_mpeg'] != '') {
								$params['slide_bg_html_mpeg'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($d_path, $params["slide_bg_html_mpeg"], $sliderParams["alias"], $alreadyImported, true));
							}
							if (isset($params['slide_bg_html_webm']) && $params['slide_bg_html_webm'] != '') {
								$params['slide_bg_html_webm'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($d_path, $params["slide_bg_html_webm"], $sliderParams["alias"], $alreadyImported, true));
							}
							if (isset($params['slide_bg_html_ogv']) && $params['slide_bg_html_ogv'] != '') {
								$params['slide_bg_html_ogv'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($d_path, $params["slide_bg_html_ogv"], $sliderParams["alias"], $alreadyImported, true));
							}
						}
					}

					//convert layers images:
					foreach ($layers as $key => $layer) {
						//import if exists in zip folder
						if ($importZip === true) { //we have a zip, check if exists
							if (isset($layer["image_url"])) {
								$layer["image_url"] = RevSliderBase::check_file_in_zip($d_path, $layer["image_url"], $sliderParams["alias"], $alreadyImported);
								$layer["image_url"] = RevSliderFunctionsWP::getImageUrlFromPath($layer["image_url"]);
							}
							if (isset($layer['type']) && $layer['type'] == 'video') {

								$video_data = (isset($layer['video_data'])) ? (array) $layer['video_data'] : array();

								if (!empty($video_data) && isset($video_data['video_type']) && $video_data['video_type'] == 'html5') {

									if (isset($video_data['urlPoster']) && $video_data['urlPoster'] != '') {
										$video_data['urlPoster'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($d_path, $video_data["urlPoster"], $sliderParams["alias"], $alreadyImported));
									}

									if (isset($video_data['urlMp4']) && $video_data['urlMp4'] != '') {
										$video_data['urlMp4'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($d_path, $video_data["urlMp4"], $sliderParams["alias"], $alreadyImported, true));
									}
									if (isset($video_data['urlWebm']) && $video_data['urlWebm'] != '') {
										$video_data['urlWebm'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($d_path, $video_data["urlWebm"], $sliderParams["alias"], $alreadyImported, true));
									}
									if (isset($video_data['urlOgv']) && $video_data['urlOgv'] != '') {
										$video_data['urlOgv'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($d_path, $video_data["urlOgv"], $sliderParams["alias"], $alreadyImported, true));
									}
								} elseif (!empty($video_data) && isset($video_data['video_type']) && $video_data['video_type'] != 'html5') { //video cover image
									if (isset($video_data['previewimage']) && $video_data['previewimage'] != '') {
										$video_data['previewimage'] = RevSliderFunctionsWP::getImageUrlFromPath(RevSliderBase::check_file_in_zip($d_path, $video_data["previewimage"], $sliderParams["alias"], $alreadyImported));
									}
								}

								$layer['video_data'] = $video_data;
							}
						}

						$layer['text'] = stripslashes($layer['text']);
						$layers[$key] = $layer;
					}
					$arrSlides[$sl_key]['layers'] = $layers;

					//create new slide
					$arrCreate = array();
					$arrCreate["slider_id"] = $sliderID;
					$arrCreate["slide_order"] = $slide["slide_order"];

					$my_layers = json_encode($layers);
					if (empty($my_layers))
						$my_layers = stripslashes(json_encode($layers));
					$my_params = json_encode($params);
					if (empty($my_params))
						$my_params = stripslashes(json_encode($params));
					$my_settings = json_encode($settings);
					if (empty($my_settings))
						$my_settings = stripslashes(json_encode($settings));



					$arrCreate["layers"] = $my_layers;
					$arrCreate["params"] = $my_params;
					$arrCreate["settings"] = $my_settings;

					$last_id = $db->insert(RevSliderGlobals::$table_slides, $arrCreate);

					if (isset($slide['id'])) {
						$slider_map[$slide['id']] = $last_id;
					}
				}

				//change for WPML the parent IDs if necessary
				if (!empty($slider_map)) {
					foreach ($arrSlides as $sl_key => $slide) {
						if (isset($slide['params']['parentid']) && isset($slider_map[$slide['params']['parentid']])) {
							$update_id = $slider_map[$slide['id']];
							$parent_id = $slider_map[$slide['params']['parentid']];

							$arrCreate = array();

							$arrCreate["params"] = $slide['params'];
							$arrCreate["params"]['parentid'] = $parent_id;
							$my_params = json_encode($arrCreate["params"]);
							if (empty($my_params))
								$my_params = stripslashes(json_encode($arrCreate["params"]));

							$arrCreate["params"] = $my_params;

							$db->update(RevSliderGlobals::$table_slides, $arrCreate, array("id" => $update_id));
						}

						$did_change = false;
						foreach ($slide['layers'] as $key => $value) {
							if (isset($value['layer_action'])) {
								if (isset($value['layer_action']->jump_to_slide) && !empty($value['layer_action']->jump_to_slide)) {
									$value['layer_action']->jump_to_slide = (array) $value['layer_action']->jump_to_slide;
									foreach ($value['layer_action']->jump_to_slide as $jtsk => $jtsval) {
										if (isset($slider_map[$jtsval])) {
											$slide['layers'][$key]['layer_action']->jump_to_slide[$jtsk] = $slider_map[$jtsval];
											$did_change = true;
										}
									}
								}
							}

							$link_slide = RevSliderFunctions::getVal($value, 'link_slide', false);
							if ($link_slide != false && $link_slide !== 'nothing') { //link to slide/scrollunder is set, move it to actions
								if (!isset($slide['layers'][$key]['layer_action']))
									$slide['layers'][$key]['layer_action'] = new stdClass();
								switch ($link_slide) {
									case 'link':
										$link = RevSliderFunctions::getVal($value, 'link');
										$link_open_in = RevSliderFunctions::getVal($value, 'link_open_in');
										$slide['layers'][$key]['layer_action']->action = array('a' => 'link');
										$slide['layers'][$key]['layer_action']->link_type = array('a' => 'a');
										$slide['layers'][$key]['layer_action']->image_link = array('a' => $link);
										$slide['layers'][$key]['layer_action']->link_open_in = array('a' => $link_open_in);

										unset($slide['layers'][$key]['link']);
										unset($slide['layers'][$key]['link_open_in']);
									case 'next':
										$slide['layers'][$key]['layer_action']->action = array('a' => 'next');
										break;
									case 'prev':
										$slide['layers'][$key]['layer_action']->action = array('a' => 'prev');
										break;
									case 'scroll_under':
										$scrollunder_offset = RevSliderFunctions::getVal($value, 'scrollunder_offset');
										$slide['layers'][$key]['layer_action']->action = array('a' => 'scroll_under');
										$slide['layers'][$key]['layer_action']->scrollunder_offset = array('a' => $scrollunder_offset);

										unset($slide['layers'][$key]['scrollunder_offset']);
										break;
									default: //its an ID, so its a slide ID
										$slide['layers'][$key]['layer_action']->action = array('a' => 'jumpto');
										$slide['layers'][$key]['layer_action']->jump_to_slide = array('a' => $slider_map[$link_slide]);
										break;
								}
								$slide['layers'][$key]['layer_action']->tooltip_event = array('a' => 'click');

								unset($slide['layers'][$key]['link_slide']);

								$did_change = true;
							}


							if ($did_change === true) {

								$arrCreate = array();
								$my_layers = json_encode($slide['layers']);
								if (empty($my_layers))
									$my_layers = stripslashes(json_encode($layers));

								$arrCreate['layers'] = $my_layers;

								$db->update(RevSliderGlobals::$table_slides, $arrCreate, array("id" => $slider_map[$slide['id']]));
							}
						}
					}
				}

				//check if static slide exists and import
				if (isset($arrSlider['static_slides']) && !empty($arrSlider['static_slides']) && $import_statics) {
					$static_slide = $arrSlider['static_slides'];
					foreach ($static_slide as $slide) {

						$params = $slide["params"];
						$layers = $slide["layers"];
						$settings = @$slide["settings"];


						//convert params images:
						if (isset($params["image"])) {
							//import if exists in zip folder
							if (strpos($params["image"], 'http') !== false) {

							} else {
								if (trim($params["image"]) !== '') {
									if ($importZip === true) { //we have a zip, check if exists
										$image = $zip->getStream('images/' . $params["image"]);
										if (!$image) {
											echo esc_html($params["image"]) . esc_html__(' not found!<br>', 'rigid');
										} else {
											if (!isset($alreadyImported['zip://' . $filepath . "#" . 'images/' . $params["image"]])) {
												$importImage = RevSliderFunctionsWP::import_media('zip://' . $filepath . "#" . 'images/' . $params["image"], $sliderParams["alias"] . '/');

												if ($importImage !== false) {
													$alreadyImported['zip://' . $filepath . "#" . 'images/' . $params["image"]] = $importImage['path'];

													$params["image"] = $importImage['path'];
												}
											} else {
												$params["image"] = $alreadyImported['zip://' . $filepath . "#" . 'images/' . $params["image"]];
											}
										}
									}
								}
								$params["image"] = RevSliderFunctionsWP::getImageUrlFromPath($params["image"]);
							}
						}

						//convert layers images:
						foreach ($layers as $key => $layer) {
							if (isset($layer["image_url"])) {
								//import if exists in zip folder
								if (trim($layer["image_url"]) !== '') {
									if (strpos($layer["image_url"], 'http') !== false) {

									} else {
										if ($importZip === true) { //we have a zip, check if exists
											$image_url = $zip->getStream('images/' . $layer["image_url"]);
											if (!$image_url) {
												echo esc_url($layer["image_url"]) . esc_html__(' not found!<br>', 'rigid');
											} else {
												if (!isset($alreadyImported['zip://' . $filepath . "#" . 'images/' . $layer["image_url"]])) {
													$importImage = RevSliderFunctionsWP::import_media('zip://' . $filepath . "#" . 'images/' . $layer["image_url"], $sliderParams["alias"] . '/');

													if ($importImage !== false) {
														$alreadyImported['zip://' . $filepath . "#" . 'images/' . $layer["image_url"]] = $importImage['path'];

														$layer["image_url"] = $importImage['path'];
													}
												} else {
													$layer["image_url"] = $alreadyImported['zip://' . $filepath . "#" . 'images/' . $layer["image_url"]];
												}
											}
										}
									}
								}
								$layer["image_url"] = RevSliderFunctionsWP::getImageUrlFromPath($layer["image_url"]);
								$layer['text'] = stripslashes($layer['text']);
							}

							if (isset($layer['layer_action'])) {
								if (isset($layer['layer_action']->jump_to_slide) && !empty($layer['layer_action']->jump_to_slide)) {
									foreach ($layer['layer_action']->jump_to_slide as $jtsk => $jtsval) {
										if (isset($slider_map[$jtsval])) {
											$layer['layer_action']->jump_to_slide[$jtsk] = $slider_map[$jtsval];
										}
									}
								}
							}

							$link_slide = RevSliderFunctions::getVal($value, 'link_slide', false);
							if ($link_slide != false && $link_slide !== 'nothing') { //link to slide/scrollunder is set, move it to actions
								if (!isset($layer['layer_action']))
									$layer['layer_action'] = new stdClass();

								switch ($link_slide) {
									case 'link':
										$link = RevSliderFunctions::getVal($value, 'link');
										$link_open_in = RevSliderFunctions::getVal($value, 'link_open_in');
										$layer['layer_action']->action = array('a' => 'link');
										$layer['layer_action']->link_type = array('a' => 'a');
										$layer['layer_action']->image_link = array('a' => $link);
										$layer['layer_action']->link_open_in = array('a' => $link_open_in);

										unset($layer['link']);
										unset($layer['link_open_in']);
									case 'next':
										$layer['layer_action']->action = array('a' => 'next');
										break;
									case 'prev':
										$layer['layer_action']->action = array('a' => 'prev');
										break;
									case 'scroll_under':
										$scrollunder_offset = RevSliderFunctions::getVal($value, 'scrollunder_offset');
										$layer['layer_action']->action = array('a' => 'scroll_under');
										$layer['layer_action']->scrollunder_offset = array('a' => $scrollunder_offset);

										unset($layer['scrollunder_offset']);
										break;
									default: //its an ID, so its a slide ID
										$layer['layer_action']->action = array('a' => 'jumpto');
										$layer['layer_action']->jump_to_slide = array('a' => $slider_map[$link_slide]);
										break;
								}
								$layer['layer_action']->tooltip_event = array('a' => 'click');

								unset($layer['link_slide']);

								$did_change = true;
							}

							$layers[$key] = $layer;
						}

						//create new slide
						$arrCreate = array();
						$arrCreate["slider_id"] = $sliderID;

						$my_layers = json_encode($layers);
						if (empty($my_layers))
							$my_layers = stripslashes(json_encode($layers));
						$my_params = json_encode($params);
						if (empty($my_params))
							$my_params = stripslashes(json_encode($params));
						$my_settings = json_encode($settings);
						if (empty($my_settings))
							$my_settings = stripslashes(json_encode($settings));


						$arrCreate["layers"] = $my_layers;
						$arrCreate["params"] = $my_params;
						$arrCreate["settings"] = $my_settings;

						$db->delete(RevSliderGlobals::$table_static_slides, 'slider_id = ' . (int) $sliderID);
						$db->insert(RevSliderGlobals::$table_static_slides, $arrCreate);
					}
				}

				$c_slider = new RevSliderSlider();
				$c_slider->initByID($sliderID);

				//check to convert styles to latest versions
				RevSliderPluginUpdate::update_css_styles(); //set to version 5
				RevSliderPluginUpdate::add_animation_settings_to_layer($c_slider); //set to version 5
				RevSliderPluginUpdate::add_style_settings_to_layer($c_slider); //set to version 5
				RevSliderPluginUpdate::change_settings_on_layers($c_slider); //set to version 5
				RevSliderPluginUpdate::add_general_settings($c_slider); //set to version 5

				$cus_js = $c_slider->getParam('custom_javascript', '');

				if (strpos($cus_js, 'revapi') !== false) {
					if (preg_match_all('/revapi[0-9]*./', $cus_js, $results)) {

						if (isset($results[0]) && !empty($results[0])) {
							foreach ($results[0] as $replace) {
								$cus_js = str_replace($replace, 'revapi' . $sliderID . '.', $cus_js);
							}
						}

						$c_slider->updateParam(array('custom_javascript' => $cus_js));
					}
				}

				if ($is_template !== false) { //duplicate the slider now, as we just imported the "template"
					if ($single_slide !== false) { //add now one Slide to the current Slider
						$mslider = new RevSlider();

						//change slide_id to correct, as it currently is just a number beginning from 0 as we did not have a correct slide ID yet.
						$i = 0;
						$changed = false;
						foreach ($slider_map as $value) {
							if ($i == $single_slide['slide_id']) {
								$single_slide['slide_id'] = $value;
								$changed = true;
								break;
							}
							$i++;
						}

						if ($changed) {
							$return = $mslider->copySlideToSlider($single_slide);
						} else {
							return(array("success" => false, "error" => esc_html__('could not find correct Slide to copy, please try again.', 'rigid'), "sliderID" => $sliderID));
						}
					} else {
						$mslider = new RevSlider();
						$title = RevSliderFunctions::getVal($sliderParams, 'title', 'slider1');
						$talias = $title;

						$mslider->duplicateSliderFromData(array('sliderid' => $sliderID, 'title' => $talias));
					}
				}
			}
		}

		public function doImportDemo($demo_name = 'one') {

			echo 'import started ' . date(DATE_RFC2822) . '<br/>';

			// Delete current menus
			$all_pages_menu_for_del = wp_get_nav_menu_object('Main menu');
			$top_menu_for_del = wp_get_nav_menu_object('Top menu');
			$footer_menu_for_del = wp_get_nav_menu_object('Footer Menu');

			$widget_menu_1_for_del =  wp_get_nav_menu_object('Information');
			$widget_menu_2_for_del =  wp_get_nav_menu_object('Extras');
			$widget_menu_3_for_del =  wp_get_nav_menu_object('The Shop');

			if ($all_pages_menu_for_del) {
				wp_delete_nav_menu('Main menu');
			}
			if ($top_menu_for_del) {
				wp_delete_nav_menu('Top menu');
			}
			if ($footer_menu_for_del) {
				wp_delete_nav_menu('Footer Menu');
			}
			if ($widget_menu_1_for_del) {
				wp_delete_nav_menu('Information');
			}
			if ($widget_menu_2_for_del) {
				wp_delete_nav_menu('Extras');
			}
			if ($widget_menu_3_for_del) {
				wp_delete_nav_menu('The Shop');
			}

			$this->importWPContent($this->getDemoLocation() . '/' . $demo_name . '/demo.xml');

			echo 'rigid_wp_xml_import_success ' . date(DATE_RFC2822) . '<br/>';

			// Get Widget menus IDs so we can pass them to widget import, so we have propper options set
			$widget_menu_1 = wp_get_nav_menu_object('Information');
			$widget_menu_2 = wp_get_nav_menu_object('Extras');
			$widget_menu_3 = wp_get_nav_menu_object('The Shop');

			$settings_imp_result = $this->importSettings($this->getDemoLocation() . '/' . $demo_name . '/demo.txt', $widget_menu_1, $widget_menu_2, $widget_menu_3);
			if (is_wp_error($settings_imp_result)) {
				echo 'rigid_settings_import_error';
				return false;
			}
			echo 'rigid_settings_import_success ' . date(DATE_RFC2822) . '<br/>';

			// Install woocommerce pages
			if (RIGID_IS_WOOCOMMERCE) {
				if (WC_Admin_Notices::has_notice('install')) {
					WC_Install::create_pages();
					// We no longer need to install pages
					delete_option('_wc_needs_pages');
					WC_Admin_Notices::remove_notice('install');
					delete_transient('_wc_activation_redirect');
				}
			}

			global $wp_rewrite;
			$wp_rewrite->set_permalink_structure('/%postname%/');
			$wp_rewrite->flush_rules();

			// Set display wishlist buttons
			update_option('yith_wcwl_use_button', 'yes');

			// Set menus
			$all_pages_menu = wp_get_nav_menu_object('Main menu');
			// Set mega menu on main demo
			if ($demo_name === 'rigid0' && isset($all_pages_menu->term_id)) {
				$menu_items = wp_get_nav_menu_items($all_pages_menu->term_id);

				foreach ($menu_items as $item) {
					if (in_array($item->title, array('Home', 'The Shop', 'Collections', 'The Blog'))) {
						update_post_meta($item->ID, '_rigid-menu-item-is_megamenu', 'active');
					}

					// Menu descriptions
					if(in_array($item->title, array('Category Options', 'Copyright', 'Product Options', 'Shop Options', 'Port_pic1', 'Port_pic2', 'Port_pic3', 'Port_pic4', 'Port_pic5', 'Shop_pic1'))) {
						update_post_meta($item->ID, '_rigid-menu-item-is_description', 'active');
					}
				}
			}

			// Set mega menu on second demo
			if ($demo_name === 'rigid1' && isset($all_pages_menu->term_id)) {
				$menu_items = wp_get_nav_menu_items($all_pages_menu->term_id);

				foreach ($menu_items as $item) {
					if (in_array($item->title, array('Market', 'Vendors'))) {
						update_post_meta($item->ID, '_rigid-menu-item-is_megamenu', 'active');
					}

					// Menu descriptions
					if(in_array($item->title, array('Category Options', 'Pic 11', 'Shop_pic1', 'Product Options'))) {
						update_post_meta($item->ID, '_rigid-menu-item-is_description', 'active');
					}
				}
			}

			$top_menu = wp_get_nav_menu_object('Top menu');
			$footer_menu = wp_get_nav_menu_object('Footer Menu');

			$locations = get_theme_mod('nav_menu_locations');
			if ($all_pages_menu) {
				$locations['primary'] = $all_pages_menu->term_id;
			}
			if ($top_menu) {
				$locations['secondary'] = $top_menu->term_id;
			}
			if ($footer_menu) {
				$locations['tertiary'] = $footer_menu->term_id;
			}
			set_theme_mod('nav_menu_locations', $locations);

			// Set home and blog pages
			$front_page = get_page_by_path('Home');

			if(get_page_by_title('The Blog')) {
				$blog_page = get_page_by_title( 'The Blog' );
			} else {
				$blog_page = get_page_by_title( 'News' );
			}

			if ($front_page) {
				update_option('show_on_front', 'page');
				update_option('page_on_front', $front_page->ID);
			}

			if ($blog_page) {
				update_option('show_on_front', 'page');
				update_option('page_for_posts', $blog_page->ID);
			}

			// import rev sliders
			if (RIGID_IS_REVOLUTION) {
				$this->importRevSliders($demo_name);
			}

			return true;
		}

	}

}