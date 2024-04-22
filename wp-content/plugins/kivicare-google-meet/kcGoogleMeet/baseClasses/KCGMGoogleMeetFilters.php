<?php

namespace kcGoogleMeet\baseClasses;

use App\controllers\KCServiceController;
use App\models\KCAppointment;
use App\models\KCClinic;
use App\models\KCServiceDoctorMapping;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use App\models\KCService;

class KCGMGoogleMeetFilters
{


    public function __construct()
    {
        add_filter('kcgm_saved_googlemeet_config', [$this, 'saveGoogleMeetSetting']);
        add_filter('kcgm_edit_googlemeet', [$this, 'editGoogleMeetSetting']);
        add_filter('kcgm_save_googlemeet_event_template', [$this, 'saveGoogleEventTemplate']);
        add_filter('kcgm_save_doctor_googlemeet_data', [$this, 'saveDoctorGooglemeetDataSave']);
        add_filter('kcgm_connect_doctor', [$this, 'connectDoctor']);
        add_filter('kcgm_disconnect_doctor', [$this, 'disConnectDoctor']);
        add_filter('kcgm_save_appointment_event',[$this,'addEventCalender'] );
        add_filter('kcgm_remove_appointment_event', [$this, 'removeEventCalender']);
        add_filter('kcgm_save_appointment_event_link_send',[$this,'sendMeetLink']);
        add_filter('kcgm_save_appointment_event_link_resend',[$this,'resendMeetEmail']);
    }

    public function saveGoogleMeetSetting($setting){
        try{
            if(isset($setting)){
                $config = array(
                    'client_id' =>$setting['data']['client_id'],
                    'client_secret'=>$setting['data']['client_secret'],
                    'app_name'=>$setting['data']['app_name'],
                    'enableCal'=>$setting['data']['enableCal']
                );
                update_option( KIVI_CARE_GOOGLE_PREFIX . 'google_meet_setting', $config );
                return [
                    'status' => true,
                    'message' => esc_html__('Google setting saved successfully', 'kc-googlemeet')
                ];
            }
        }catch (Exception $e) {
            return [
                'status' => false,
                'message' => esc_html__('Google setting not saved', 'kc-googlemeet')
            ];
        }

    }

    public function editGoogleMeetSetting(){
        $get_googlecal_data = get_option(KIVI_CARE_GOOGLE_PREFIX . 'google_meet_setting',true);

        if ( gettype($get_googlecal_data) != 'boolean' ) {
            return [
                'data'=> $get_googlecal_data,
                'status' => true,
            ];
        } else {
            return [
                'data'=> [],
                'status' => false,
            ];
        }
    }

    public function connectDoctor($conn) {
        $client = $this->get_client();
        try{
            if ($client->isAccessTokenExpired()) {

                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                } else {

                    global $wpdb;
                    $auth_code = $client->authenticate(trim($conn['code']));
                    $access_token = $client->getAccessToken();
                    update_user_meta($conn['id'], 'google_meet_access_token',json_encode($access_token) );
                    update_user_meta($conn['id'], KIVI_CARE_GOOGLE_PREFIX.'google_meet_connect','on' );
                    update_user_meta($conn['id'], KIVI_CARE_GOOGLE_PREFIX.'doctor_meet_id' ,$this->get_selected_calendar_id($conn['id']) );
                    update_user_meta($conn['id'], 'telemed_type','googlemeet' );
                    // get telemed service id
//                    $telemed_Service = kcGetTelemedServiceId();

//                    $service_doctor_mapping = new KCServiceDoctorMapping();

//                    $doctor_telemed_service = $service_doctor_mapping->get_by(['service_id' => $telemed_Service, 'doctor_id' => $conn['id']],"=",true);

//                    if (empty($doctor_telemed_service)) {
//                        $service_doctor_mapping->insert([
//                            'service_id' => $telemed_Service,
//                            'clinic_id' => kcGetDefaultClinicId(),
//                            'doctor_id' => $conn['id'],
//                            'charges' => 0
//                        ]);
//                    }

                    // Verify and deactivate zoom
                    $zoom_config_data = get_user_meta($conn['id'], 'zoom_config_data', true);

                    if(!empty($zoom_config_data)) {
                        $zoom_config_data = json_decode($zoom_config_data);
                        $zoom_config_data->enableTeleMed = false;
                        $zoom_config_data = json_encode($zoom_config_data);
                        update_user_meta($conn['id'],'zoom_config_data' , $zoom_config_data);
                    }

                    return [
                        'status' => true,
                        'message' => esc_html__('Google Meet Connected', 'kc-googlemeet')
                    ];
                }
            }

        }catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th
            ];
        }

    }
    public function disConnectDoctor($id) {

        global $wpdb;

        try{
            $get_access_token = get_user_meta($id['id'],'google_meet_access_token',true);
            if($get_access_token ){
                delete_user_meta($id['id'],'google_meet_access_token');
                delete_user_meta($id['id'],KIVI_CARE_GOOGLE_PREFIX.'doctor_meet_id');
                update_user_meta($id['id'], KIVI_CARE_GOOGLE_PREFIX.'google_meet_connect','off' );
                delete_user_meta($id['id'], 'telemed_type');
            }

//            $data['type'] = 'Telemed';
//            $telemed_service = getServiceId($data) ;
//            $telemed_Service_id = $telemed_service[0]->id;
//            $service_table = $wpdb->prefix . 'kc_service_doctor_mapping' ;
//            $delete_doctor_service_query = "DELETE FROM {$service_table} WHERE doctor_id = {$id['id']} AND service_id = $telemed_Service_id ";
//            $wpdb->query($delete_doctor_service_query);

            return [
                'status' => true,
                'message' => esc_html__('Google Meet Disconnected', 'kc-googlemeet')
            ];
        }catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => esc_html__('Google Meet Not Disconnected', 'kc-googlemeet')
            ];
        }

    }

    public function addEventCalender($eventData) {

        global $wpdb;

        $calendar_paremeter = ['conferenceDataVersion' => 1];
        if(kcWoocommercePaymentGatewayEnable() !== 'on'){
            $calendar_paremeter =['sendUpdates'=> 'all','conferenceDataVersion' => 1];
        }

        $eventData['appoinment_id'] = (int)$eventData['appoinment_id'];
        $appointment = (new KCAppointment())->get_by([ 'id' => $eventData['appoinment_id']], '=', true);
        $doctor_enable = get_user_meta($appointment->doctor_id, KIVI_CARE_GOOGLE_PREFIX.'google_meet_connect',true);

        if(!empty($doctor_enable) ) {

            $clinicData = (new KCClinic())->get_by(['id'=> $appointment->clinic_id] ,'=',true);

            $clinicAddress = $clinicData->address.','.$clinicData->city.','.$clinicData->country;

            $calendar_id = $this->get_selected_calendar_id($appointment->doctor_id);
            $client = $this->get_authorized_client_for_doctor($appointment->doctor_id);

            if($client){

                $args['post_name'] = strtolower(KIVI_CARE_GOOGLE_PREFIX.'doctor_gm_event_template');
                $args['post_type'] = strtolower(KIVI_CARE_GOOGLE_PREFIX.'gmeet_tmp') ;

                $check_exist_post = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}posts WHERE `post_name` = '" . $args['post_name'] . "' AND `post_type` = '".$args['post_type']."' AND post_status = 'publish' ", ARRAY_A);
                if (empty($check_exist_post)) {
                    return [
                        'status' => false,
                        'message' => __('template post not exists','kc-googlemeet')
                    ];
                }
                $calender_title = $check_exist_post['post_title'];
                $calender_content = $check_exist_post['post_content'];

                $content_data = kcCommonNotificationData($appointment,[],$eventData['service'],'doctor');
                $calender_content = kcEmailContentKeyReplace($calender_content,  $content_data);
                $calender_title = kcEmailContentKeyReplace($calender_title,  $content_data);
                try {

                    $appointment->status = (string)$appointment->status;
                    if($appointment->status != '0'){
                        $timezone = get_option('timezone_string');
                        date_default_timezone_set($timezone);
                        $format = 'Y-m-d\TH:i:sP';
                        $start = date($format, strtotime( $appointment->appointment_start_date.$appointment->appointment_start_time));
                        $end = date($format, strtotime( $appointment->appointment_end_date.$appointment->appointment_end_time));

                        $event = new Google_Service_Calendar_Event(array(
                            'summary' => $calender_title,
                            'location' => $clinicAddress,
                            'description' =>  $calender_content,
                            'start' => array(
                                'dateTime' => $start,
                                'timeZone' => $timezone,
                            ),
                            'end' => array(
                                'dateTime' => $end,
                                'timeZone' => $timezone,
                            ),
                            "attendees" =>  [
                                [
                                    "email" => isset($content_data['patient_email']) ? $content_data['patient_email'] : '',
                                    'displayName' => isset($content_data['patient_name']) ?$content_data['patient_name'] :'',
                                    'responseStatus' => 'accepted',
                                    'resource' => true
                                ],
                            ],
                            "conferenceData"=> [
                                "createRequest "=> [
                                    "requestId" =>sha1($appointment->id . '|' . uniqid('', true))
                                ]
                            ],
                            'colorId' => $appointment->status == '1' ? 1 : 2
                        ));
                        $google_cal_service = new Google_Service_Calendar($client);
                        $google_calendar_event_id = $this->get_event_key($eventData['appoinment_id']);
                        if(!empty($google_calendar_event_id)){
                            try {
                                $conference = new \Google_Service_Calendar_ConferenceData();
                                $conferenceRequest = new \Google_Service_Calendar_CreateConferenceRequest();
                                $conferenceSolutionKey = new \Google_Service_Calendar_ConferenceSolutionKey();
                                $conferenceSolutionKey->setType("hangoutsMeet");
                                $conferenceRequest->setRequestId('ADGGSH'. time());
                                $conferenceRequest->setConferenceSolutionKey($conferenceSolutionKey);
                                $conference->setCreateRequest($conferenceRequest);
                                $event->setConferenceData($conference);
                                $event = $google_cal_service->events->update($calendar_id, $google_calendar_event_id->event_id, $event,$calendar_paremeter);
                                $this->update_event_key($event,$eventData['appoinment_id']);
                            }catch (\Throwable $th){
                                return [
                                    'status' => false,
                                    'error' => (array)$th
                                ];
                            }

                            // Existing google event
                            return [
                                'status' => true,
                                'message' =>esc_html__('Google Meet Updated','kc-googlemeet'),
                                'join_url' => $event->hangoutLink
                            ];

                        }else{
                            // new event in google cal
                            $conference = new \Google_Service_Calendar_ConferenceData();
                            $conferenceRequest = new \Google_Service_Calendar_CreateConferenceRequest();
                            $conferenceSolutionKey = new \Google_Service_Calendar_ConferenceSolutionKey();
                            $conferenceSolutionKey->setType("hangoutsMeet");
                            $conferenceRequest->setRequestId('ADGGSH'. time());
                            $conferenceRequest->setConferenceSolutionKey($conferenceSolutionKey);
                            $conference->setCreateRequest($conferenceRequest);

                            $event->setConferenceData($conference);
                            $event = $google_cal_service->events->insert($calendar_id, $event,$calendar_paremeter);
                            $this->save_event_key( $event,$eventData['appoinment_id']);

                            return [
                                'status' => true,
                                'message' =>esc_html__('Google Meet Created','kc-googlemeet'),
                                'join_url' => $event->hangoutLink
                            ];
                        }

                    }else{
                        self::removeEventCalender( $eventData);
                    }

                }catch (\Throwable $th) {
                    return [
                        'status' => false,
                        'error' => (array)$th
                    ];
                }
            }
        }

        return [
            'status' => false,
            'message' => esc_html__('Failed To create event','kc-googlemeet')
        ];
    }

    public function removeEventCalender($data){
        try {
            $data['appoinment_id'] = (int)$data['appoinment_id'];
            $appointment = (new KCAppointment())->get_by([ 'id' => $data['appoinment_id']], '=', true);
            $cal_mapping_data = $this->get_event_key($data['appoinment_id']);
            if(!empty($cal_mapping_data)){
                $calendar_id = $this->get_selected_calendar_id($appointment->doctor_id);
                $client = $this->get_authorized_client_for_doctor($appointment->doctor_id);
                if($client){
                    $g_service = new Google_Service_Calendar($client);
                    $g_service->events->delete($calendar_id, $cal_mapping_data->event_id,['sendUpdates'=> 'all']);
                    $this->delete_event_key($data['appoinment_id']);
                }
            }
        }catch (\Throwable $th) {
            return [
                'status' => false,
                'error' => (array)$th
            ];
        }
    }

    public function saveGoogleEventTemplate($template){
        foreach ($template['data'] as $key => $value) {
            wp_update_post($value);
        }

        return [
            'status' => true,
            'message' => esc_html__('GoogleMeet template  saved successfully.', 'kc-googlemeet')
        ];
    }


    public static function get_client(){
        $get_config =   get_option( KIVI_CARE_GOOGLE_PREFIX . 'google_meet_setting',true);
        $gcal_client = new Google_Client();
        $gcal_client->setClientId(trim($get_config['client_id']));
        $gcal_client->setClientSecret(trim($get_config['client_secret']));
        $gcal_client->setAccessType("offline");        // offline access // it give a refersh token
        $gcal_client->setIncludeGrantedScopes(true);   // incremental auth
        $gcal_client->setApprovalPrompt('force');
        $gcal_client->addScope(Google_Service_Calendar::CALENDAR);
        $gcal_client->setRedirectUri('postmessage');
        return $gcal_client;
    }

    public  function get_selected_calendar_id($doctor_id){
        $selected_calendar_id = get_user_meta(KIVI_CARE_GOOGLE_PREFIX.'doctor_meet_id', $doctor_id);
        if(!$selected_calendar_id){
            $selected_calendar_id = 'primary';
            return $selected_calendar_id;
        }

        return $selected_calendar_id;
    }
    public function get_authorized_client_for_doctor($doctor_id){

        $access_token = get_user_meta($doctor_id, 'google_meet_access_token',true );
        if(!$access_token) return false;

        $client = self::get_client();

        $client->setAccessToken($access_token);

        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            $access_token = $client->getAccessToken();
            update_user_meta(KIVI_CARE_GOOGLE_PREFIX.'doctor_meet_id', self::get_selected_calendar_id($doctor_id), $doctor_id);
            update_user_meta('google_meet_access_token', json_encode($access_token), $doctor_id);
        }

        return $client;

    }

    public function save_event_key($event,$appointment_id){
        global $wpdb;
        return $wpdb->insert($wpdb->prefix.'kc_appointment_google_meet_mappings',['event_id'=>$event->getId(),'appointment_id'=>$appointment_id,'url' =>$event->hangoutLink,'password' => '','event_url' => $event->htmlLink ]);
    }

    public function delete_event_key($appointment_id){
        global $wpdb;
        return $wpdb->delete($wpdb->prefix.'kc_appointment_google_meet_mappings',['appointment_id'=>(int)$appointment_id]);
    }

    public function get_event_key($appointment_id){
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM ".$wpdb->prefix.'kc_appointment_google_meet_mappings'." WHERE appointment_id=".(int)$appointment_id);
    }

    public function update_event_key($event,$appointment_id){
        global $wpdb;
        return $wpdb->update($wpdb->prefix.'kc_appointment_google_meet_mappings',['url'=>$event->hangoutLink,'event_url' => $event->htmlLink],['appointment_id'=>(int)$appointment_id]);
    }

    public function sendMeetLink($filterData){
        $status = false;
        if(!empty($filterData) && !empty( $filterData['appoinment_id']) ){
            $filterData['appoinment_id'] = (int)$filterData['appoinment_id'];
            $status =  kcCommonEmailFunction($filterData['appoinment_id'],'Telemed','meet_patient');
            $status2 =  kcCommonEmailFunction($filterData['appoinment_id'],'Telemed','meet_doctor');
            $smsResponse = kcSendAppointmentMeetSms($filterData['appoinment_id']);
            if($status && $status2){
                $status = true;
            }else{
                $status = false;
            }
        }
        return [
            'status' => $status,
            'message' => esc_html__('Meetings has email', 'kct-lang')
        ];
    }

    public function resendMeetEmail($filterData) {
        if(!empty($filterData) && !empty( $filterData['id']) ){
            $filterData['id'] = (int)$filterData['id'];
            $status =  kcCommonEmailFunction($filterData['id'],'Telemed','meet_patient');
            $status2 =  kcCommonEmailFunction($filterData['id'],'Telemed','meet_doctor');
            $smsResponse = kcSendAppointmentMeetSms($filterData['id']);
            if($status && $status2){
                return  true;
            }else{
                return false;
            }
        }
        return false ;
    }

    public function saveDoctorGooglemeetDataSave($data){

        $data = $data['data'];
        (new KCServiceDoctorMapping())->update(['charges' => $data['video_price']],
            ['doctor_id' => $data['doctor_id'],
                'service_id' => $data['telemed_service_id']]);
        return ['status' => true,
            'message' => "Data saved successfully"];
    }
}