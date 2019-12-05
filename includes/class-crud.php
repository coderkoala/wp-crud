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

		$this->addBackendTab();
		$this->load_dependencies();
		$this->set_locale();
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

    private function formRender(&$message){
	    echo $message;
	    ?>
        <br><form id="form" method="POST">
            <input type="hidden" name="action" value="crud">
            <label for="text1" class="col-4 col-form-label"><small>Name</small></label>
            <input id="name" name="name" placeholder="name" type="text" class="form-control">
            <label for="text" class="col-4 col-form-label"><small>Age</small></label>
            <input id="age" name="age" placeholder="age" type="number" class="form-control">
            <button name="submit" type="submit" class="button action" value="submit">Submit</button>
        </form>
        <?php
    }

    private function tableRender(&$table){
	    global $wpdb;
	    ?>
        <br>
        <table id="myTable" class="display" cellspacing="0" width="50%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Age</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $data = $wpdb->get_results("SELECT name, age FROM `{$table}`");
        foreach ($data as $dataTuple){
            echo "<tr>
                  <td>{$dataTuple->name}</td>
                  <td>{$dataTuple->age}</td>
                  </tr>";
        }
        echo"</tbody></table>

        <script defer>
        $(document).ready(function() {
          var table = $('#myTable').DataTable({ 
                select: false,
            });
        
          $('#myTable tbody').on( 'click', 'tr', function () {
           alert(table.row( this ).data()[0]);
        
        } );
        });
        $('#message').click(()=>{
            $('#message').remove();
        })
        </script>
    ";
    }

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		add_action( 'wp_enqueue_styles', array($this, 'enqueue_styles') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
	}

	function enqueue_styles(){
		$path = ABSPATH . 'wp-content/plugins/'.$this->plugin_name.'/admin/css/crud-admin.css';
		$registered = wp_register_style(
			"my_test_style",
			$path,
			array(),
			$this->get_version(),
			true
		);
	}

	function enqueue_scripts(){
		$path = ABSPATH . 'wp-content/plugins/'.$this->plugin_name.'/admin/js/crud-admin.js';
		$registered = wp_enqueue_script(
			"my_test_script",
			$path,
			array( 'jquery' ),
			1.0,
			true
		);
	}

	public function view_students(){
        ;?>
        <div class="row">
            <div class="col">
                <div class="text-muted text-center pt-3 pb-5"><h4>Students CRUD</h4><hr></div>
            </div>
        </div>
        <?php
	    global $wpdb;
        $message = '';
        $table = $wpdb->prefix. 'crud_students';
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $message = "<div id='message' class='updated'><p>Successfully Added new Student</p></div>";
	        $tuple = array(
	                'name' => '',
                    'age' => ''
            );
            $dbData = shortcode_atts($tuple, $_REQUEST);
            $wpdb->insert($table, $dbData);
        }

        self::formRender($message);
	    self::tableRender($table);
    }

	function addBackendTab(){
        add_menu_page( __('Crud'), __('Crud'), 'manage_options', 'crud', array(&$this,'view_students'));
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
