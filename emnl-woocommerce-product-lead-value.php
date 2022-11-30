<?php 
/**
 * Plugin Name: WooCommerce Product Lead Value
 * Description: This plugin places the value of WooCommerce Products into a DataLayer variable named "ProductLeadValue" for Google Tag Manager to process.
 * Author: Erik Molenaar
 * Author URI: https://erikmolenaar.nl
 * Version: 1.01
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


// Add JS file
add_action ( 'wp_enqueue_scripts', 'emnl_woocommerce_product_lead_value_js' );
function emnl_woocommerce_product_lead_value_js() {

    wp_register_script ( 'emnl_woocommerce_product_lead_value', plugin_dir_url( __FILE__ ) . '/emnl-woocommerce-product-lead-value.js', array ( 'jquery' ), '1.0.0', true );
    wp_enqueue_script ( 'emnl_woocommerce_product_lead_value' );

}

// Define global multiplier for lead value generation
$lead_value_percentage = 20; // in %
define ( "LEAD_VALUE_MULTIPLIER", $lead_value_percentage / 100 );


// Outputs the HTML script with the DataLayer variable
function outputLeadValueDataLayer ( $leadValue ) {
    
    echo '<script type="text/javascript">
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                "ProductLeadValue" : ' . $leadValue . '  
            });
          </script>';
    
}


// SIMPLE Products
add_action ( 'woocommerce_single_product_summary', 'lead_value_simple_product', 30 );
function lead_value_simple_product() {

    global $product;

    if ( $product->is_type ( 'simple' ) ) {

        // Check if a regular price OR a sales price is available
        if ( $product->get_regular_price() || $product->get_sale_price() ) {

            $leadValue = '';

            if ( ! $product->get_sale_price() || $product->get_sale_price() == '' ) {

                $leadValue = $product->get_regular_price() * LEAD_VALUE_MULTIPLIER;

            } else {

                $leadValue = $product->get_sale_price() * LEAD_VALUE_MULTIPLIER;

            }

            outputLeadValueDataLayer ( $leadValue );

        }

    }

}


// VARIABLE products
add_action ( 'woocommerce_single_variation' , 'lead_value_variable_product', 5);
function lead_value_variable_product() {

    global $product;
    $salePrice = $product->get_variation_sale_price ( 'min' );

    $leadValue = $salePrice * LEAD_VALUE_MULTIPLIER;
    outputLeadValueDataLayer( $leadValue );

    echo '<input type="hidden" id="leadValueMultiplier" name="leadValueMultiplier" value="' . LEAD_VALUE_MULTIPLIER . '">';

}