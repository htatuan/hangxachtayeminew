<?php
/**
 * Plugin Name: Tawea
 * Plugin URI: https://www.tawea.com/#/sdk
 * Description: Facebook Chat for your wordpress.
 * Version: 1.1
 * Author: Tawea Technologies SL
 * Author URI: https://www.tawea.com
 * License: GPL2
 */

add_action('wp_head','add_tawea');

function add_tawea() {

	$options = get_option( 'option_tawea' );
	printf('<script type="text/javascript">         
var _taw = _taw || [];
_taw.push(["_color", "%s"]);
(function(d) {var taw = d.createElement("script"),id = "tawea-extension";
if (d.getElementById(id)) {return;}
taw.type = "text/javascript";taw.id=id;taw.async=true;
taw.src ="https://tawea.com/extension/js/sdk.min.js";
(document.head||document.documentElement).appendChild(taw);})(document);
</script>',
        isset( $options['tawea_color'] ) ? esc_attr( $options['tawea_color']) : '#008db1'
    );

}


class TaweaSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Tawea Facebook Chat Settings', 
            'Facebook Chat', 
            'manage_options', 
            'tawea-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'option_tawea' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Tawea Facebook Chat Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'option_tawea_group' );   
                do_settings_sections( 'tawea-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'option_tawea_group', // Option group
            'option_tawea', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'tawea_color_section', // ID
            'Style Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'tawea-setting-admin' // Page
        );  

        add_settings_field(
            'tawea_color', // ID
            'Toolbar color', // Title 
            array( $this, 'tawea_color_callback' ), // Callback
            'tawea-setting-admin', // Page
            'tawea_color_section' // Section           
        );      


    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();

        if( isset( $input['tawea_color'] ) )
            $new_input['tawea_color'] = sanitize_text_field( $input['tawea_color'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function tawea_color_callback()
    {
        printf(
            '<input type="text" id="tawea_color" name="option_tawea[tawea_color]" value="%s" />',
            isset( $this->options['tawea_color'] ) ? esc_attr( $this->options['tawea_color']) : '#008db1'
        );
    }

}

if( is_admin() )
    $my_settings_page = new TaweaSettingsPage();

?>