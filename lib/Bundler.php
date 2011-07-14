<?php

class Bundler {
	public static $build_profiles = array();
	
	// Properties
	protected $asset_path_prefix;
	protected $bundles = array();
	protected $enabled;
	
	// Constructor
	protected function __construct($my_path_prefix) {
		$this->enabled = true;
		$this->asset_path_prefix = self::add_trailing_slash($my_path_prefix);
	}
	
	// Factory
	public function create($my_path_prefix='') {
		$ins = new Bundler($my_path_prefix);
		self::$build_profiles[] = $ins;
		
		return $ins;
	}
	
	public function push($bundle) {
		$this->bundles[] = $bundle;
	}
	
	public function get_bundles() {
		return $this->bundles;
	}
	public function get_path_prefix() {
		return $asset_path_prefix;
	}
	public function get_bundled_paths() {
		foreach ($this->bundles as $bundle) {
			$my_array[$bundle->get_bundle_key] = $this->get_full_path($bundle->get_bundled_path());
		}
		return $my_array;
	}
	public function get_original_paths() {
		foreach ($this->bundles as $bundle) {
			foreach($bundle->get_original_paths() as $original_bundle_key => $original_bundle_path) {
				$my_array[$original_bundle_key] = $this->get_full_path($original_bundle_path);
			}
		}
		return $my_array;
	}
	
	// Utility Functions
	private static function add_trailing_slash($my_path) {
		return (substr($my_path, -1)!='/')?$my_path.'/':$my_path;
	}
	public function get_full_path($my_path_suffix) {
		return $this->asset_path_prefix . $my_path_suffix;
	}
}

class Bundle {
	protected $output_path;
	protected $bundle_items = array();
	protected $meta = array();
	
	public function __construct($my_path) {
		$this->output_path = $my_path;
	}
	
	public function add($bundle_item_key, $my_path) {
		$this->bundle_items[$bundle_item_key] = new BundleItem($bundle_item_key, $my_path, $my_replacements = array());
		return $this;
	}
	public function get_bundle_key() {
		$keys = array();
		foreach($this->bundle_items as $bundle_item) {
			$key[]=$bundle_item->get_key();
		}
		return implode('-', $key);
	}
	public function get_bundle_items() {
		return $this->bundle_items;
	}
	public function get_bundled_path() {
		return $this->output_path;
	}
	public function get_original_paths() {
		foreach($this->bundle_items as $bundle_item) {
			$my_array[$bundle_item->get_key()] = $bundle_item->get_path();
		}
		
		return $my_array;
	}
	public function set_language($language) {
		return $this->set_meta('language', $language);
	}
	public function get_language() {
		return($this->meta['language'])?$this->meta['language']:'';
	}
	
	public function set_meta($key, $value) {
		$this->meta[$key] = $value;
		return $this;
	}
	public function get_meta($key) {
		return $this->meta[$key];
	}
}

class BundleItem {
	protected $key;
	protected $path;
	protected $replacements = array();
	
	public function __construct($my_key, $my_path, $my_replacements = array()) {
		$this->key = $my_key;
		$this->path = $my_path;
		$this->replacements = $my_replacements;
	}
	
	public function add_replacement($find, $replace) {
		$this->replacements[$find] = $replace;
	}
	
	public function get_key() {
		return $this->key;
	}
	public function get_path() {
		return $this->path;
	}
	public function get_replacements() {
		return $this->replacements;
	}
}



// /* Combine files together. Hooray! */
// class Bundler {
// 	public $asset_path;
// 	public $bundles = array();
// 	protected static $build_profiles;
// 	
// 	public static function create($asset_path_prefix = '') {
// 		$ins = new Bundler($asset_path_prefix);
// 		self::$build_profiles[] = $ins;
// 		return $ins;
// 	}
// 	
// 	/**
// 	 * @param string $asset_path_prefix a path that asset paths should be prefixed with in order to find them on the file system.
// 	 */
// 	protected function __construct($asset_path_prefix = '') {
// 		$this->asset_path = $asset_path_prefix;
// 	}
// 	
// 	/**
// 	 * We need an easy way to send an instances of Bundler to the build script.
// 	 * This function, when run, will set the current instance as the instance to be built by the build script.
// 	 */
// 	public function add_to_build_profiles() {
// 		self::$build_profiles[] = $this;
// 	}
// 	/**
// 	 * Static function used by the build script to get bundles to build.
// 	 */
// 	public static function get_build_profiles() {
// 		return self::$build_profiles;
// 	}
// 
// 	/**
// 	 * Define a file bundle
// 	 * @param string $built_file the file name of the resulting built file
// 	 * @param array $files the array of file names you want to combine into the built file
// 	 */
// 	public function define($bundle, $built_file) {
// 		$this->bundles[$bundle]['build'] = $built_file;
// 	}
// 	
// 	/**
// 	 * Add a file to a bundle
// 	 * @param string $bundle Bundle key
// 	 * @param string $file File URL
// 	 * @param array $replacements string replacements for the file
// 	 */
// 	public function add_to($bundle, $file, $replacements = array()) {
// 		$this->bundles[$bundle]['src'][$file]['replacements'] = $replacements;
// 	}
// 	
// 	public function get_bundled_file($bundle) {
// 		$bundle = $this->bundles[$bundle];
// 		if (!isset($bundle)) {
// 			throw new Exception('Bundle "'.$bundle.'" doesn\'t exist', 1);
// 		}
// 		return $bundle['build'];
// 	}
// 	
// 	public function get_original_files($bundle = '') {
// 		$keys = array();
// 		$bundle = $this->bundles[$bundle];
// 		if (!isset($bundle)) {
// 			throw new Exception('Uh-oh! Bundle "'.$bundle.'" doesn\'t exist', 1);
// 		}
// 
// 		foreach ($bundle['src'] as $file => $info) {
// 			$keys[] = $file;
// 		}
// 
// 		return $keys;
// 	}
// }
?>