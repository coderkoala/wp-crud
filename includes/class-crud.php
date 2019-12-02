<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/coderkoala
 * @since      1.0.0
 *
 * @package    Crud
 * @subpackage Crud/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Crud
 * @subpackage Crud/includes
 * @author     Nobel Dahal <iamtribulation@outlook.com>
 */
class Crud {

    private static $instance = null;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Crud_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'CRUD_VERSION' ) ) {
			$this->version = CRUD_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'crud';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/*  Singleton class routine
     *   @return  Crud
     */
	public static function getInstance(){
	    if(self::$instance == null)
	        self::$instance = new Crud();
	    return self::$instance;
    }

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Crud_Loader. Orchestrates the hooks of the plugin.
	 * - Crud_i18n. Defines internationalization functionality.
	 * - Crud_Admin. Defines all hooks for the admin area.
	 * - Crud_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-crud-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-crud-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-crud-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-crud-public.php';

		$this->loader = new Crud_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Crud_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Crud_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function fetchFromData(){
	    print_r($_POST['name']);
	    die();
    }

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Crud_Admin( $this->get_plugin_name(), $this->get_version() );
        // adds post data interception upon form submission
        add_action('admin_menu', array(&$this, 'addBackendTab'));
//        Currently no plans to use ajax, might use later
//        add_action('admin_post_crud', array(&$this, 'fetchFromData'));
//        add_action('admin_post_nopriv_crud', array(&$this, 'fetchFromData'));
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	function enqueue_styles(){
        wp_register_style(
                "my-test-style",
                plugins_url() . '/crud/assets/css/datatables.min.css',
                array(),
                1.0,
                true
        );
    }

	function enqueue_scripts(){
        wp_enqueue_script(
            "my_test_script",
            plugins_url().'/crud/assets/js/datatables.min.js',
            array( 'jquery' ),
            1.0,
            true
        );
    }

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Crud_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	public function view_students(){
	    global $wpdb;
	    if($_POST){
	        $tuple = array(
	                'name' => '',
                    'age' => ''
            );
            $dbData = shortcode_atts($tuple, $_REQUEST);
            ?>
            <div id="message" class="updated"><p>Successfully Added new Student</p></div>
            <?php
            $wpdb->insert($wpdb->prefix. 'crud_students', $dbData);
        }
	    ;?>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="text-muted text-center pt-3 pb-5"><h4>Students CRUD</h4><hr></div>
                </div>
            </div>
            <form id="form" method="POST">
<!--            <form action="--><?php //echo esc_url( admin_url('admin-post.php') ); ?><!--">-->
                <input type="hidden" name="action" value="crud">
                <div class="form-group row">
                    <label for="text1" class="col-4 col-form-label">Name</label>
                    <div class="col-8">
                        <input id="name" name="name" placeholder="Please place your name here" type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="text" class="col-4 col-form-label">Age</label>
                    <div class="col-8">
                        <input id="age" name="age" placeholder="Please place your age here" type="number" class="form-control">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <div class="offset-4 col-8">
                        <button name="submit" type="submit" class="button action" value="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }

	public function addBackendTab(){
        add_menu_page( __('Students CRUD'), __('Students CRUD'), 'manage_options', 'crud_students', array(&$this,'view_students'));
    }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
    }

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Crud_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
