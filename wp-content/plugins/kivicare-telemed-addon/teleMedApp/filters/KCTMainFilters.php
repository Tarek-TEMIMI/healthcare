<?php

namespace TeleMedApp\filters;

use Exception;
use TeleMedApp\baseClasses\KCTBaseClass;
use TeleMedApp\baseClasses\KCTHelper;
use WP_REST_Response;
use WP_User;

class KCTMainFilters extends KCTBaseClass
{

    public function __construct()
    {
        add_filter("kct_save_zoom_telemed_oauth_config", [$this, "saveZoomTelemedOauthConfig"]);
        add_filter("kct_get_zoom_telemed_oauth_config", [$this, "getZoomTelemedOauthConfig"]);
        add_filter("kct_generate_doctor_zoom_oauth_token", [$this, "generateDoctorZoomOauthToken"]);
        add_filter("kct_disconnect_doctor_zoom_oauth", [$this, "disconnectDoctorZoomOauth"]);
    }
    public function saveZoomTelemedOauthConfig($data)
    {
        try {
            $config = array(
                'enableCal' => $data['enableCal'],
                'client_id' => $data['client_id'],
                'client_secret' => $data['client_secret'],
                'redirect_url' => $data['redirect_url'],
            );
            update_user_meta(get_current_user_id(), KIVI_CARE_TELEMED_PREFIX . 'zoom_telemed_setting', $config);
            return [
                'message' => esc_html__('Zoom Telemed Setting Saved Successfully', 'kiviCare-telemed-addon')
            ];
        } catch (Exception $e) {
            return [
                'message' => esc_html($e->getMessage())
            ];
        }
    }

    public function generateDoctorZoomOauthToken($data)
    {
        $user = new WP_User(get_current_user_id());
        if (in_array(KIVI_CARE_TELEMED_PREFIX . "doctor", $user->roles)) {
            $doctor_id = get_current_user_id();
        } else {
            $doctor_id = $data['doctor_id'];
            unset($data['doctor_id']);
        }
        $zoom_telemed_setting = get_user_meta($doctor_id, KIVI_CARE_TELEMED_PREFIX . 'zoom_telemed_setting', true);

        $url = 'https://zoom.us/oauth/token';
        $headers = array(
            'Authorization' => 'Basic ' . base64_encode($zoom_telemed_setting['client_id'] . ':' . $zoom_telemed_setting['client_secret']),
            'Content-Type' => 'application/x-www-form-urlencoded',
        );

        if (!doing_filter("kct_get_zoom_telemed_oauth_config")) {
            unset($data['client_id']);
            unset($data['client_secret']);
        }
        $args = array(
            'headers' => $headers,
            'body' => $data,
        );


        $response = wp_remote_post($url, $args);

        if (!is_wp_error($response)) {
            if (wp_remote_retrieve_response_code($response) == 200) {
                $body = json_decode(wp_remote_retrieve_body($response));


                $doctor_result = update_user_meta(
                    $doctor_id,
                    KIVI_CARE_TELEMED_PREFIX . "doctor_zoom_telemed_config",
                    $body
                );
                update_user_meta($doctor_id, KIVI_CARE_TELEMED_PREFIX . 'zoom_telemed_connect', 'on');


                if ($doctor_result > 0) {
                    return ([
                        "message" => __("Doctor is Connected To Zoom Telemed", 'kiviCare-telemed-addon'),
                        "status" => true
                    ]);
                }
                wp_send_json($body);
            }
        }

        return ([
            "message" => __("Some Thing Went Wrong", 'kiviCare-telemed-addon'),
            "status" => false
        ]);
    }
    public function disconnectDoctorZoomOauth()
    {

        $doctor_config =  get_user_meta(
            get_current_user_id(),
            KIVI_CARE_TELEMED_PREFIX . "doctor_zoom_telemed_config",
            true
        );


        $zoom_telemed_setting = get_user_meta(get_current_user_id(), KIVI_CARE_TELEMED_PREFIX . 'zoom_telemed_setting', true);

        $url = 'https://zoom.us/oauth/revoke';
        $headers = array(
            'Authorization' => 'Basic ' . base64_encode($zoom_telemed_setting['client_id'] . ':' . $zoom_telemed_setting['client_secret']),
            'Content-Type' => 'application/x-www-form-urlencoded',
        );

        $args = array(
            'headers' => $headers,
            'body' => [
                "token" => $doctor_config->access_token
            ],
        );


        $response = wp_remote_post($url, $args);


        if (
            delete_user_meta(get_current_user_id(), KIVI_CARE_TELEMED_PREFIX . 'zoom_telemed_connect')
            && delete_user_meta(get_current_user_id(), KIVI_CARE_TELEMED_PREFIX . 'doctor_zoom_telemed_config')
            && isset(json_decode(wp_remote_retrieve_body($response))->status)
            && json_decode(wp_remote_retrieve_body($response))->status == "success"
        ) {
            return ([
                "message" => __("Doctor Disonnected Zoom Telemed Successfully", 'kiviCare-telemed-addon'),
            ]);
        }
        return ([
            "message" => __("SomeThing Went Wrong", 'kiviCare-telemed-addon'),
        ]);
    }
    public function getZoomTelemedOauthConfig()
    {
        $zoom_settings= get_user_meta(get_current_user_id(), KIVI_CARE_TELEMED_PREFIX . 'zoom_telemed_setting', true);

        if( empty($zoom_settings)){
            $zoom_settings=[
                "enableCal"=> false,
                "redirect_url"=> "",
                "client_id"=> "",
                "client_secret"=> ""
            ];
        }
        
        return $zoom_settings;
    }
}
