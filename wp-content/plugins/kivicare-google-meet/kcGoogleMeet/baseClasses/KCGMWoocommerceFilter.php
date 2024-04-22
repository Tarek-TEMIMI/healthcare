<?php

namespace kcGoogleMeet\baseClasses;

class KCGMWoocommerceFilter
{
    public function __construct()
    {
        add_filter( 'kcgm_change_woocommerce_module_status',function($data) {
            return update_option(KIVI_CARE_GOOGLE_PREFIX.'woocommerce_payment', $data['status']);
        });
        add_filter( 'kcgm_get_woocommerce_module_status',function($data) {
            return get_option(KIVI_CARE_GOOGLE_PREFIX.'woocommerce_payment',true);
        });
        add_filter( 'kcgm_woocommerce_add_to_cart','kivicareWooocommerceAddToCart');
        if(!isKiviCareTelemedActive() || !isKiviCareProActive()){
            add_action('woocommerce_admin_order_data_after_order_details', 'kivicareWoocommerceOrderDataAfterOrderDetails', 10, 1 );
            add_action( 'before_delete_post',                      'kivicareServiceDeleteOnProductDelete');
            add_action( 'woocommerce_update_product',               'kivicareServiceUpdateOnProductUpdated', 10, 1 );
            add_filter( 'woocommerce_get_cart_item_from_session',   'kivicareGetCartItemsFromSession', 1, 3 );
            add_action( 'woocommerce_checkout_update_order_meta',   'kivicareSaveToPostMeta', 10, 1 );
            add_action( 'woocommerce_order_status_changed',         'kivicareWooOrderStatusChangeCustom', 10, 3);
            add_action( 'woocommerce_order_status_completed',       'kivicareWoocommercePaymentComplete', 10, 1 );
            add_filter( 'woocommerce_product_data_tabs',            'kivicareServiceDetailOnWooProductTabs' );
            add_filter( 'woocommerce_product_data_panels',          'kivicareServiceWooProductTabContent' );
            add_action( 'woocommerce_thankyou', 'kivicareCheckoutRedirectWidgetPayment');
        }
    }

}