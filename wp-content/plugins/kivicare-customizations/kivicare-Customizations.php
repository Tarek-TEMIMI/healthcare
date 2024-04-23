<?php
/*
Plugin Name: KiviCare Customizations
Plugin URI: http://votresite.com
Description: Customizations for KiviCare plugin.
Version: 1.0
Author: Votre Nom
Author URI: http://votresite.com
*/
function check_appointment_activation_time($appointment_id) {
    $appointment = get_appointment($appointment_id);  // Assume get_appointment est une fonction qui récupère les détails
    $created_at = new DateTime($appointment['created_at']);
    $now = new DateTime();
    $diff = $now->diff($created_at);

    if ($diff->h < 2 && $diff->days == 0) {
        // Ne pas activer le rendez-vous
        return false;
    }
    return true;
}
add_filter('activate_appointment', 'check_appointment_activation_time');


function deactivate_old_appointments() {
    global $wpdb; // Cela permet d'utiliser la base de données WordPress
    $current_time = current_time('mysql', 1); // Heure actuelle avec ajustement GMT
    $two_hours_ago = date('Y-m-d H:i:s', strtotime($current_time) - 2 * HOUR_IN_SECONDS); // Calcul pour trouver l'heure il y a deux heures

    // Sélectionner les rendez-vous non activés créés il y a plus de deux heures
    $appointments = $wpdb->get_results("
        SELECT id FROM {$wpdb->prefix}appointments
        WHERE status = 'pending' AND created_at <= '{$two_hours_ago}'
    ");

    // Boucle à travers les résultats et mise à jour du statut
    foreach ($appointments as $appointment) {
        $wpdb->update(
            "{$wpdb->prefix}appointments",
            ['status' => 'inactive'], // Vous pourriez avoir besoin de remplacer 'inactive' par le statut approprié
            ['id' => $appointment->id]
        );
    }
}
if (!wp_next_scheduled('deactivate_old_appointments_hook')) {
    wp_schedule_event(time(), 'hourly', 'deactivate_old_appointments_hook');
}
add_action('deactivate_old_appointments_hook', 'deactivate_old_appointments');
