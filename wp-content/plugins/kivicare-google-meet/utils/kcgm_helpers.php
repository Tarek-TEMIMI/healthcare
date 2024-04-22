<?php
use kcGoogleMeet\baseClasses\KCGMBase;

function kcgmSendGoogleMeetNotification($appointment_id){
    (new KCGMBase());
    apply_filters('kcgm_save_appointment_event_link_send',['appoinment_id' => $appointment_id]);
}