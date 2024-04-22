<?php

namespace kcGoogleMeet\baseClasses;

class KCGMActivate
{

    public static function  activate(){
        self::migrateDatabase();
    }

    public static function migrateDatabase () {
        require KIVI_CARE_GOOGLE_DIR . 'kcGoogleMeet/database/kc-appointment-meet-db.php';
    }

}