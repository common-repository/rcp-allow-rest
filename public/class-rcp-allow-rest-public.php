<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.maxizone.fr
 * @since      1.0.0
 *
 * @package    Rcp_Allow_Rest
 * @subpackage Rcp_Allow_Rest/public
 */
if (! function_exists ( 'rcp_allow_rest_log' )) {
    function rcp_allow_rest_log($message) {
        if (WP_DEBUG === true) {
            if (is_array ( $message ) || is_object ( $message )) {
                error_log ( print_r ( $message, true ) );
            } else {
                error_log ( $message );
            }
        }
    }
}
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rcp_Allow_Rest
 * @subpackage Rcp_Allow_Rest/public
 * @author     Termel <admin@termel.fr>
 */
class Rcp_Allow_Rest_Public {
    
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;
    
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        rcp_allow_rest_log('Building plugin ' . $this->plugin_name . ' v' . $this->version);
        // if (class_exists('RCP_Member')) {
        if (is_plugin_active('restrict-content-pro/restrict-content-pro.php')) {
            rcp_allow_rest_log('RCP plugin Installed and running :)');
            //$this->tweakRestrictContentPro();
            add_action( 'plugins_loaded', array($this,'rcpar_remove_rcp_filters') );
        } else {
            rcp_allow_rest_log('Please activate RCP plugin');
            return 'Please activate RCP plugin';
        }
        
    }
    
    
    function rcpar_remove_rcp_filters() {
        $this->tweakRestrictContentPro();
    }
    
    
    public function tweakRestrictContentPro() {
        // In order to do that you'd have to unhook our filter on the_content (see rcp_filter_restricted_content() in includes/content-filters.php) and then add your own and bypass the filter if REST_REQUEST is defined.
        // Note that this will allow anyone to view the content if they visit the endpoint directly in their browser. (i.e. yoursite.com/wp-json/wp/v2/posts/ ).
        // add_filter( 'the_content', 'rcp_filter_restricted_content' , 100 );
        
        /*
         * Yes you would also need to remove this one:
        
         add_filter( 'post_password_required', 'rcp_post_password_required_rest_api', 10, 2 );
        
         Both would need to be removed.
        
         If your filters aren't taking effect, you may need to wrap them inside of plugins_loaded so they get removed later on, after RCP has loaded.
        
         Sadly I can't offer more code though. It's not feasible for us to write custom code for all our customers and I already provided more than usual. If you need help from a developer on this we have a list of ones we recommend here: https://restrictcontentpro.com/consultants/
         * */
        
        // remove RCP filter on content:
        rcp_allow_rest_log('remove_filter rcp_filter_restricted_content');
        remove_filter ( 'the_content', 'rcp_filter_restricted_content',100);
        remove_filter( 'post_password_required', 'rcp_post_password_required_rest_api',10);
        
        
        rcp_allow_rest_log('add_filter rcpAllowRest_filter_restricted_content');
        add_filter ( 'the_content', array (
            $this,
            'rcpAllowRest_filter_restricted_content'
        ), 100 );
    }
    function rcpAllowRest_filter_restricted_content($content) {
        
        $result = $content;
        
        
        if( DEFINED( 'REST_REQUEST' ) && REST_REQUEST ) {
            // code in here
            rcp_allow_rest_log ( '---> REST request, leave content without filter' );
            rcp_allow_rest_log ($result);
            // just leave content as is
        } else {
            rcp_allow_rest_log ( 'Standard request' );
            if (function_exists ( 'rcp_filter_restricted_content' )) {
                rcp_allow_rest_log ( '---> FILTERING content with RCP rcp_filter_restricted_content' );
                $result = rcp_filter_restricted_content ( $content );
            } else {
                rcp_allow_rest_log ( 'filter rcp_filter_restricted_content DOES not exists' );
            }
        }
        
        return $result;
    }
    
    
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Rcp_Allow_Rest_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Rcp_Allow_Rest_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rcp-allow-rest-public.css', array(), $this->version, 'all' );
        
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Rcp_Allow_Rest_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Rcp_Allow_Rest_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rcp-allow-rest-public.js', array( 'jquery' ), $this->version, false );
        
    }
    
}
