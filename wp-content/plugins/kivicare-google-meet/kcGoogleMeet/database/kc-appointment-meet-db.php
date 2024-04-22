<?php
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');


global $wpdb;
$charset_collate = $wpdb->get_charset_collate();


$table_name = $wpdb->prefix . 'kc_appointment_google_meet_mappings'; // do not forget about tables prefix

$sql = "CREATE TABLE `{$table_name}` ( 
  id int NOT NULL AUTO_INCREMENT,
  event_id varchar(255) NOT NULL,
  appointment_id int NOT NULL,
  url varchar(255) NOT NULL,
  password varchar(255) DEFAULT NULL,
  event_url varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
)  $charset_collate;";

maybe_create_table($table_name,$sql);
