<?php
/**
 * Plugin Name: Payment Gateway: POLi for WooCommerce
 * Description: Payment Gateway for WooCommerce to allow payment via Direct Deposit using POLi™
 * Author:      Clough I.T. Solutions
 * Author URI:  https://cloughit.com.au
 * Version:     2017.05.22
 * Text Domain: payment-gateway-poli-for-woocommerce
 * License:     GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action( 'plugins_loaded', 'init_woocommerce_poli' );
function init_woocommerce_poli() {

    class WC_Gateway_WooCommerce_POLi extends WC_Payment_Gateway {

        public function __construct() {
            $this->id = 'woocommerce_poli';
			$this->has_fields = false;
			$this->order_button_text  = __( 'Proceed to ', 'payment-gateway-poli-for-woocommerce' ) . apply_filters( 'pgpfw_poli', 'POLi™' );
			$this->method_title = 'POLi™ Gateway';
			$this->method_description = sprintf( __( '%s%s%s%s%s%sPay with POLi™. Your customers will be transferred to their internet banking.%s%s%sAvailable Banks%s%s' ),
				'<p>',
				'<a target="_blank" href="http://www.polipayments.com/consumer">',
				'<img src="' . plugins_url( 'images/poli.gif', __FILE__ ) . '">',
				'</a>',
				'</p>',
				'<p>',
				'</p>',
				'<p>',
				'<a target="_blank" href="https://transaction.apac.paywithpoli.com/POLiFISupported.aspx?merchantcode=' . $this->get_option( 'merchantcode' ) . '">',
				'</a>',
				'</p>' );
            $this->icon = plugins_url( 'images/poli.gif', __FILE__ );
            $this->title = $this->get_option( 'title' );
			$this->description = sprintf( __( 'Pay via %s with POLi™! You will be transferred to your internet banking. %sAvailable Banks%s%s' ), $this->title, '<a target="_blank" href="https://transaction.apac.paywithpoli.com/POLiFISupported.aspx?merchantcode=' . $this->get_option( 'merchantcode' ) . '">', '</a>', '</p>' );
            $this->init_form_fields();
            $this->init_settings();
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            add_action( 'woocommerce_api_poli_nudge', array( $this, 'result' ) );
        }

        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __( 'Enable / Disable', 'payment-gateway-poli-for-woocommerce' ),
                    'type' => 'checkbox',
                    'label' => __( 'Enable POLi™ Gateway', 'payment-gateway-poli-for-woocommerce' ),
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => __( 'Title', 'payment-gateway-poli-for-woocommerce' ),
                    'type' => 'text',
                    'description' => __( 'This controls the title which the user sees during checkout.', 'payment-gateway-poli-for-woocommerce' ),
                    'default' => __( 'Direct Deposit', 'payment-gateway-poli-for-woocommerce' ),
                    'desc_tip'      => true,
                ),
                'merchantcode' => array(
                    'title' => __( 'Merchant Code', 'payment-gateway-poli-for-woocommerce' ),
                    'type' => 'text',
                    'description' => __( 'Please input your POLi™ Merchant Code', 'payment-gateway-poli-for-woocommerce' ),
                    'desc_tip' => true
                ),
                'authenticationcode' => array(
                    'title' => __( 'Authentication Code', 'woocommerce'),
                    'type' => 'text',
                    'description' => __( 'Please input your POLi™ Authentication Code', 'payment-gateway-poli-for-woocommerce' ),
                    'desc_tip' => true
                )
            );
        }

        public function process_payment( $order_id ) {
            global $woocommerce;
            $order = new WC_Order( $order_id );

            // Mark as on-hold (we're awaiting the cheque)
            $order->update_status( 'on-hold', __( 'Awaiting direct deposit payment', 'payment-gateway-poli-for-woocommerce' ) );

            $mcode = $this->get_option( 'merchantcode' );
            $acode = $this->get_option( 'authenticationcode' );

            $order_array = array(
                'Amount' => $order->get_total(),
                'CurrencyCode' => get_woocommerce_currency(),
                'MerchantReference' => $order_id,
                'MerchantHomepageURL' => home_url( '/' ),
                'SuccessURL' => str_replace( 'https:', 'http:', add_query_arg( 'wc-api', 'poli_nudge', home_url( '/' ) ) ),
                'FailureURL' => str_replace( 'https:', 'http:', add_query_arg( 'wc-api', 'poli_nudge', home_url( '/' ) ) ),
                'CancellationURL' => get_permalink( woocommerce_get_page_id( 'pay' ) ),
                'NotificationURL' => str_replace( 'https:', 'http:', add_query_arg( 'wc-api', 'poli_nudge', home_url( '/' ) ) )
            );

            $order_json = json_encode( $order_array );
            $auth = base64_encode( $mcode . ':' . $acode );
            $header = array(
                'Content-Type: application/json',
                'Authorization: Basic ' . $auth
            );

            $ch = curl_init( "https://poliapi.apac.paywithpoli.com/api/Transaction/Initiate" );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
            curl_setopt( $ch, CURLOPT_HEADER, 0 );
            curl_setopt( $ch, CURLOPT_POST, 1 );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $order_json );
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0 );
            curl_setopt( $ch, CURLOPT_REFERER, $referrer );//Set the referrer
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            $response = curl_exec( $ch );
            curl_close( $ch );
            $json = json_decode( $response, true );
            $redirect_url = $json["NavigateURL"];
            return array(
                'result'    => 'success',
                'redirect'  => $redirect_url
            );
        }

        public function result() {
            global $woocommerce;
            $token = $_POST["Token"];
            if( is_null( $token ) ) {
                $token = $_GET["token"];
            }

            $mcode = $this->get_option( 'merchantcode' );
            $acode = $this->get_option( 'authenticationcode' );

            $auth = base64_encode( $mcode . ':' . $acode );
            $header = array(
                'Authorization: Basic ' . $auth
            );

            $ch = curl_init( "https://poliapi.apac.paywithpoli.com/api/Transaction/GetTransaction?token=" . urlencode( $token ) );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
            curl_setopt( $ch, CURLOPT_HEADER, 0 );
            curl_setopt( $ch, CURLOPT_POST, 0 );
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0 );
            curl_setopt($ch, CURLOPT_REFERER, $referrer);//Set the referrer
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            $response = curl_exec( $ch );
            curl_close ($ch);

            $json = json_decode($response, true);

            if ( $json['TransactionStatusCode'] == 'Completed' ) {
                $order_id = $json['MerchantReference'];
                $order = new WC_Order( $order_id );
                $order->add_order_note( __( 'POLi Nudge Recieved. POLi ID: ' . $json['TransactionRefNo'], 'payment-gateway-poli-for-woocommerce' ) );
                $order->payment_complete();
                // Remove cart
                $woocommerce->cart->empty_cart();

                // Return thankyou redirect
                $redirect_url = $this->get_return_url( $order );
                wp_safe_redirect( $redirect_url );
                exit();
            } else {
                $order_id = $json['MerchantReference'];
                $error_code = $json['ErrorCode'];
                $error_message = $json['ErrorMessage'];
                $woocommerce->add_error( __( 'Payment error:', 'payment-gateway-poli-for-woocommerce') . ' Order ID: ' . $order_id . ' Error Code: ' . $error_code . ' Error Message: ' .$error_message );
                $redirect_url = get_permalink( woocommerce_get_page_id( 'pay' ) );
                wp_safe_redirect( $redirect_url );
                exit();
            }

        }

    }

}

add_filter( 'woocommerce_payment_gateways', 'add_woocommerce_poli' );
function add_woocommerce_poli( $methods ) {
    $methods[] = 'WC_Gateway_WooCommerce_POLi';
    return $methods;
}
