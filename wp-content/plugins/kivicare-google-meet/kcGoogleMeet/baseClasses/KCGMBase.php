<?php

namespace kcGoogleMeet\baseClasses;

use kcGoogleMeet\baseClasses\KCGMWoocommerceFilter;
use kcGoogleMeet\baseClasses\KCGMGoogleMeetFilters;

class KCGMBase
{
    public $warningMessage = '';
    public function __construct()
    {
        add_action( 'admin_init', [$this, 'checkPluginActive'] );
        add_action( 'plugins_loaded', function (){
            load_plugin_textdomain( 'kc-googlemeet', false, dirname( KIVI_CARE_GOOGLE_BASE_NAME ) . '/languages' );
        });
        add_action('init',function (){
            $this->kcgmInit();
        });
    }

    public function kcgmInit(){
        if (!function_exists('get_plugins')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $kivicare_plugin = 'kivicare-clinic-management-system/kivicare-clinic-management-system.php' ;
        if ( is_plugin_active($kivicare_plugin) ) {
            $plugins = get_plugins();
            if (isset($plugins[$kivicare_plugin]) && !empty($plugins[$kivicare_plugin])) {
                if (version_compare($plugins[$kivicare_plugin]['Version'] , KIVI_CARE_GOOGLE_REQUIRED_PLUGIN_VERSION,'>=')) {
                    (new KCGMGoogleMeetFilters() );
                    (new KCGMWoocommerceFilter() );
                    return;
                }
            }
        }
        deactivate_plugins(  KIVI_CARE_GOOGLE_BASE_NAME);
    }

    public function checkPluginActive(){
        $kivicare_plugin = 'kivicare-clinic-management-system/kivicare-clinic-management-system.php' ;
        if ( is_plugin_active($kivicare_plugin) ) {
            $plugins = get_plugins();
            if(isset($plugins[$kivicare_plugin]) && $plugins[$kivicare_plugin] !== '') {
                if(!(version_compare($plugins[$kivicare_plugin]['Version'], KIVI_CARE_GOOGLE_REQUIRED_PLUGIN_VERSION,'>='))) {
                    $p_version = $plugins[$kivicare_plugin]['Version'];
                    $this->warningMessage = esc_html__('Warning:','kc-googlemeet').'<b><i>'.esc_html('KiviCare - Google Meet','kc-googlemeet').'</i>  </b>'.esc_html__('Requires Plugin Version : ','kc-googlemeet').'<b> <i>'.esc_html__('KiviCare - Clinic & Patient Management System (EHR) V','kc-googlemeet').KIVI_CARE_GOOGLE_REQUIRED_PLUGIN_VERSION.' </i></b>'.esc_html__('your current plugin version is ','kc-googlemeet').'<b>'. $p_version .' </b>';
                    add_action( 'admin_notices', [$this, 'pluginWarning'] );
                    if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
                    deactivate_plugins(  KIVI_CARE_GOOGLE_BASE_NAME);
                }
            } else {
                $this->warningMessage = esc_html__('Warning:','kc-googlemeet').'<b><i>'.esc_html('KiviCare - Google Meet','kc-googlemeet').'</i>  </b>'.esc_html__('Requires ','kc-googlemeet').'<b> <i>'.esc_html__('KiviCare - Clinic & Patient Management System (EHR)','kc-googlemeet').' </i></b>';
                add_action( 'admin_notices', [$this, 'pluginWarning'] );
                if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
                deactivate_plugins(  KIVI_CARE_GOOGLE_BASE_NAME);
            }
        } else {
            $this->warningMessage = esc_html__( 'Warning: KiviCare - Google Meet','kc-googlemeet'). '<b><b>' . esc_html__(' is deactivate Because kivicare-clinic-management-system is not active', 'kc-googlemeet' ).'</b><br> <strong>'.esc_html__('Note:kivicare-clinic-management-system plugin is active still receiving this message then Make sure that kivicare-clinic-management-system plugin folder is same as " kivicare-clinic-management-system" in wp-content/plugins.' ,'kc-googlemeet').'</strong>';
            add_action( 'admin_notices', [$this, 'pluginWarning'] );
            if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
            deactivate_plugins(  KIVI_CARE_GOOGLE_BASE_NAME);
        }
    }

    public function pluginWarning() {
        $class = 'notice notice-warning';
        $message = $this->warningMessage;
        printf( '<div class="error"><p>%2$s</p></div>', esc_attr( $class ), $message );
    }

}