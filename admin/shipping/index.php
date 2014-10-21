<?php
require_once('../../util/main.php');
//require_once('util/secure_conn.php');
//require_once('util/valid_admin.php');

require_once('model/customer_db.php');
require_once('model/address_db.php');
require_once('model/order_db.php');
require_once('model/product_db.php');
require_once('model/email.php');

if (!isset($_SESSION['admin'])) {
    display_error('You cannot login to the admin section while ' .
                  'logged in as a customer.');
}

if ( isset($_POST['action']) ) {
    $action = $_POST['action'];
} elseif ( isset($_GET['action']) ) {
    $action = $_GET['action'];
} else {
    $action = 'view_orders';
}

switch($action) {
    case 'view_orders':
        include 'view_orders.php';
        break;
    case 'view_pending':
        $new_orders = get_unfilled_orders();
        include 'view_pending.php';
        break;
    case 'view_shipped':
        $old_orders = get_filled_orders();
        include 'view_shipped.php';
        break;
    case 'view_order':
        $order_id = $_GET['order_id'];

        // Get order data
        $order = get_order($order_id);
        $order_date = date('M j, Y', strtotime($order['orderDate']));
        $order_items = get_order_items($order_id);

        // Get customer data
        $customer = get_customer($order['customerID']);
        $name = $customer['lastName'] . ', ' . $customer['firstName'];
        $email = $customer['emailAddress'];
        $card_number = $order['cardNumber'];
        $card_expires = $order['cardExpires'];
        $card_name = card_name($order['cardType']);

        $shipping_address = get_address($order['shipAddressID']);
        $ship_line1 = $shipping_address['line1'];
        $ship_line2 = $shipping_address['line2'];
        $ship_city = $shipping_address['city'];
        $ship_state = $shipping_address['state'];
        $ship_zip = $shipping_address['zipCode'];
        $ship_phone = $shipping_address['phone'];

        $billing_address = get_address($order['billingAddressID']);
        $bill_line1 = $billing_address['line1'];
        $bill_line2 = $billing_address['line2'];
        $bill_city = $billing_address['city'];
        $bill_state = $billing_address['state'];
        $bill_zip = $billing_address['zipCode'];
        $bill_phone = $billing_address['phone'];

        include 'view_order.php';
        break;
    case 'set_ship_date':
        $order_id = intval($_POST['order_id']);
        set_ship_date($order_id);
        $url = '?action=view_order&order_id=' . $order_id;
        sendShippingConfirmEmail($order_id);
        redirect($url);
    default:
        display_error("Unknown shipping action: " . $action);
        break;
}
?>