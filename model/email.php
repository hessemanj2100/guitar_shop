<?php
require('PHPMailer.php');
require('POP3.php');

function sendConfirmEmail($order_id) {
$customer = get_customer(get_customer_id($order_id));
$customer_name = $customer['firstName'] . ' ' .
             $customer['lastName'];
$customer_email = $customer['emailAddress'];

$order = get_order($order_id);
$order_date = strtotime($order['orderDate']);
$order_date = date('M j, Y', $order_date);
$order_items = get_order_items($order_id);
$copyright_date = date("Y");

set_time_limit(0);

$messageHTML = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- the head section -->
<head>
    <title>My Guitar Shop: Order Confirmation</title>
    <style>
    /* the styles for the HTML elements */
    body {
        margin-top: 0;
        background-color: rgb(128, 141, 159);
        font-family: Arial, Helvetica, sans-serif;
    }
    h1 {
        font-size: 150%;
        margin: 0;
        padding: .5em 0 .25em;
    }
    h2 {
        font-size: 120%;
        margin: 0;
        padding: .5em 0 .25em;
    }
    h1, h2 {
        color: rgb(205, 163, 94);
    }

    ul {
        margin: 0 0 1em 0;
        padding: 0 0 0 2.5em;
    }
    li {
        margin: 0;
        padding: .25em;
    }
    a {
        color: rgb(69, 85, 106);   
        font-weight: bold;

    }
    a:hover {
        color: blue;
    }
    p {
        margin: 0;
        padding: .25em 0;
    }

    form {
        margin: .5em 0;
        width: 100%;
    }
    label {
        width: 8em;
        padding-right: .5em;
        padding-bottom: .5em;
        text-align: right;
        float: left;
    }
    textarea {
        width: 25em;
        margin-bottom: .5em;
    }
    table {
        border-collapse: collapse;
    }
    td, th {
        margin: 0;
        padding: .15em 0;
    }
    br {
        clear: both;
    }

    /* the styles for the div tags that divide the page into sections */
    #page {
        width: 850px;
        margin: 0 auto;
        background-color: white;
        border: 1px solid rgb(119, 75, 77);
    }
    #header {
        margin: 0;
        border-bottom: 2px solid rgb(119, 75, 77);
        padding: .5em 2em;
    }
    #header h1 {
        margin: 0;
        padding: .5em 0;
        color: black;
    }
    #main {
        margin: 0;
        padding: .5em 2em;
    }
    #sidebar {
        float: left;
        width: 170px;
    }
    #sidebar h2 {
        padding: 1em 0 .25em;
    }
    #sidebar ul {
        list-style-type: none;
        margin-left: 0;
        padding-left: 0;
        margin-bottom: 2em;
    }
    #sidebar li {
        margin: 0;
        padding-bottom: .25em;
    }
    #content {
        float: left;
        width: 580px;
        padding-bottom: 1.5em;
    }
    #left_column {
        float: left;
        width: 150px;
        padding-left: .5em;
    }
    #right_column {
        float: left;
        width: 300px;
        padding-left: 1em;
    }
    #footer {
        clear: both;
        margin-top: 1em;
        padding-right: 1em;
        border-top: 2px solid rgb(119, 75, 77);
    }
    #footer p {
        text-align: right;
        font-size: 80%;
        margin: 1em 0;
    }
    /********************************************************************
    * styles for the classes
    ********************************************************************/
    .right {
        text-align: right;
    }
    .left {
        text-align: left;
    }
    .cart_qty  {
        text-align: right;
        width: 3em;
    }
    .button_form {
        margin: 0;
        padding: 0;
        float: left;
    }
    .inline {
        display: inline;
        margin-left: .5em;
    }
    /********************************************************************
    * Styles for the Product Manager application
    ********************************************************************/
    #category_table form {
        margin: 0;
    }
    #category_table td {
        margin: 0;
        padding: .15em .5em 0 0;
    }
    #add_category_form {
        margin: 0;
    }
    #add_category_form input {
        margin-right: .5em;  
    }
    #add_admin_user_form  label {
        width: 8.5em;
    }
    #edit_and_delete_buttons {
        margin-bottom: .5em;
    }
    #edit_and_delete_buttons form {
        display: inline;
    }
    #image_manager input {
        margin: .25em;
    }
    /********************************************************************
    * Styles for the Product Catalog application
    ********************************************************************/
    #product_image_column {
        width: 8em;
        text-align: center;
    }
    /*******************************************************************/
    #add_to_cart_form {
        margin: .25em;
    }
    #add_to_cart_form input {
        float: none;
    }
    /*******************************************************************/
    #cart {
        margin: 0;
        padding: 1em .25em;
        border-collapse: collapse;
        width: 100%;
    }
    #cart_header th {
        border-bottom: 2px solid black;
    }
    #cart_footer td {
        border-top: 2px solid black;
        font-style: bold;
    }
    #cart td {
        padding: .25em 0;
    }
    /*******************************************************************/
    #login_form label {
        width: 5em;
        padding-right: 1em;
    }
    #login_form input[text] {

    }
    #payment_form label {
        width: 8em;
        padding-right: 1em;
    }
    #payment_form input[text] {
        width: 5em;
        margin: 0;
        padding-right: 1em;
    }
    #add_category label {
        text-align: left;
        width: 3em;
    }
    #add_category input { 
        margin-right: .25em;
    }
    </style>
</head>
<!-- the body section -->
<body>
<div id="page">
<div id="header">
    <h1>My Guitar Shop</h1>
</div>
<div id="main">
    <div id="content">
        <h2>Order Confirmation</h2>
        <p>Hello <b>$customer_name</b>,<br /><br />
        Thank you for placing an order with My Guitar Shop. 
        You may log on at any time to check the status of your order. 
        We will send you a shipping confirmation when your order 
        leaves our facility.</p>
    <h2>Order Details</h2>
    <p>Order #$order_id<br />
    Placed on $order_date</p>
    <p>&nbsp;</p>
    <table id="cart">
        <tr id="cart_header">
            <th class="left">Item</th>
            <th class="right">List Price</th>
            <th class="right">Savings</th>
            <th class="right">Your Cost</th>
            <th class="right">Quantity</th>
            <th class="right">Line Total</th>
        </tr>
HTML;
$message = <<<TEXT
My Guitar Shop

Order Confirmation
------------------

Hello $customer_name,

Thank you for placing an order with My Guitar Shop. 
You may log on at any time to check the status of your order. 
We will send you a shipping confirmation when your order 
leaves our facility.

Order Details
-------------
    
Order #$order_id 
Placed on $order_date 
TEXT;
?>
<?php
    $subtotal = 0;
    $url = 'http://localhost/book_apps/guitar_shop/';
    foreach ($order_items as $item) :
        $product_id = $item['productID'];
        $product_url = $url . 'catalog/?product_id=' . $product_id;
        $product = get_product($product_id);
        $item_name = $product['productName'];
        $list_price = $item['itemPrice'];
        $list_price_txt = sprintf('$%.2f', $list_price);
        $savings = $item['discountAmount'];
        $savings_txt = sprintf('$%.2f', $savings);
        $your_cost = $list_price - $savings;
        $your_cost_txt = sprintf('$%.2f', $your_cost);
        $quantity = $item['quantity'];
        $line_total = $your_cost * $quantity;
        $line_total_txt = sprintf('$%.2f', $line_total);
        $subtotal += $line_total;
?>
<?php
$messageHTML .= <<<HTML
        <tr>
            <td><a href="$product_url" target="_blank">
                $item_name</a></td>
            <td class="right">
                $list_price_txt
            </td>
            <td class="right">
                $savings_txt
            </td>
            <td class="right">
                $your_cost_txt
            </td>
            <td class="right">
                $quantity
            </td>
            <td class="right">
                $line_total_txt
            </td>
        </tr>
HTML;
$message .= <<<TEXT
$item_name 
List Price: $list_price_txt
Savings:    $savings_txt 
Your Cost:  $your_cost_txt 
Quantity:   $quantity 
Line Total: $line_total_txt 
TEXT;
?>
<?php endforeach; ?>
<?php
$order_url = $url . 'account/?action=view_order&order_id=' . $order_id;
$subtotal_txt = sprintf('$%.2f', $subtotal);
$tax_amount_txt = sprintf('$%.2f', $order['taxAmount']);
$ship_amount_txt = sprintf('$%.2f', $order['shipAmount']);
$total = $subtotal + $order['taxAmount'] + $order['shipAmount'];
$total_txt = sprintf('$%.2f', $total);
$messageHTML .= <<<HTML
        <tr id="cart_footer">
            <td colspan="5" class="right">Subtotal:</td>
            <td class="right">
                $subtotal_txt
            </td>
        </tr>
        <tr>
            <td colspan="5" class="right">$ship_state Tax:</td>
            <td class="right">
                $tax_amount_txt
            </td>
        </tr>
        <tr>
            <td colspan="5" class="right">Shipping:</td>
            <td class="right">
                $ship_amount_txt
            </td>
        </tr>
            <tr>
            <td colspan="5" class="right">Total:</td>
            <td class="right">
                $total_txt
            </td>
        </tr>
    </table>
    <p><a href="$order_url" target="_blank">Click here</a> to check the status of your order.</p>
    <p>&nbsp;</p>
    <p>Thank you for shopping with us.<br /><b>My Guitar Shop</b></p>
    </div>
</div><!-- end main -->
<div id="footer">
    <p class="copyright">
        &copy; $copyright_date My Guitar Shop, Inc.
    </p>
</div><!-- end footer -->
</div><!-- end page -->
</body>
</html>
HTML;

$message .= <<<TEXT
--------------------------------------------
Subtotal: $subtotal_txt 
GA Tax:   $tax_amount_txt 
Shipping: $ship_amount_txt 
Total:    $total_txt

To check the status of your order, use this link:
$order_url 


Thank you for shopping with us.
My Guitar Shop
TEXT;
?>
<?php
$email = new PHPMailer();

$email->IsSMTP();
// $email->IsSendmail();
$email->Host       = "smtp.gmail.com";   //Will need to be modified
$email->SMTPAuth   = true;  
$email->Port       = 465;
$email->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)  
//$email->SMTPSecure = 'tls';  
$email->SMTPSecure = 'ssl';    
$email->Username   = "guitarshopinc@gmail.com"; // SMTP account username Will need to be modified
$email->Password   = "mrlerch14";        // SMTP account password Will need to be modified
$email->SetFrom('guitarshopinc@gmail.com', 'My Guitar Shop');
$email->SingleTo  = true;	// true allows that only one person will receive an email per array group
$email->Subject   = 'Your Order with My Guitar Shop'; // appears in subject of email
$email->Body      = $messageHTML;  // the body will interpret HTML - $messageHTML identified above
$email->AltBody = $message;            // the AltBody will not interpret HTML - $message identified above
$destination_email_address = "$customer_email";  // destination email address
$destination_user_name = "$customer_name";   // Destination user name
$email->AddAddress($destination_email_address, $destination_user_name); 
	// AddAddress method identifies destination and sends email	
if(!$email->Send()) {
  return false;
} else {
  return true;
}

} // end function


function sendShippingConfirmEmail($order_id) {
$customer = get_customer(get_customer_id($order_id));
$customer_name = $customer['firstName'] . ' ' .
             $customer['lastName'];
$customer_email = $customer['emailAddress'];

$order = get_order($order_id);
$order_date = strtotime($order['orderDate']);
$order_date = date('M j, Y', $order_date);
$order_items = get_order_items($order_id);
$ship_date = date('M j, Y', strtotime($order['shipDate']));
$shipping_address = get_address($order['shipAddressID']);
$ship_line1 = $shipping_address['line1'];
$ship_line2 = $shipping_address['line2'];
$ship_city = $shipping_address['city'];
$ship_state = $shipping_address['state'];
$ship_zip = $shipping_address['zipCode'];
$ship_phone = $shipping_address['phone'];
$copyright_date = date("Y");

set_time_limit(0);

$messageHTML = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- the head section -->
<head>
    <title>My Guitar Shop: Shipping Confirmation</title>
    <style>
    /* the styles for the HTML elements */
    body {
        margin-top: 0;
        background-color: rgb(128, 141, 159);
        font-family: Arial, Helvetica, sans-serif;
    }
    h1 {
        font-size: 150%;
        margin: 0;
        padding: .5em 0 .25em;
    }
    h2 {
        font-size: 120%;
        margin: 0;
        padding: .5em 0 .25em;
    }
    h1, h2 {
        color: rgb(205, 163, 94);
    }

    ul {
        margin: 0 0 1em 0;
        padding: 0 0 0 2.5em;
    }
    li {
        margin: 0;
        padding: .25em;
    }
    a {
        color: rgb(69, 85, 106);   
        font-weight: bold;

    }
    a:hover {
        color: blue;
    }
    p {
        margin: 0;
        padding: .25em 0;
    }

    form {
        margin: .5em 0;
        width: 100%;
    }
    label {
        width: 8em;
        padding-right: .5em;
        padding-bottom: .5em;
        text-align: right;
        float: left;
    }
    textarea {
        width: 25em;
        margin-bottom: .5em;
    }
    table {
        border-collapse: collapse;
    }
    td, th {
        margin: 0;
        padding: .15em 0;
    }
    br {
        clear: both;
    }

    /* the styles for the div tags that divide the page into sections */
    #page {
        width: 850px;
        margin: 0 auto;
        background-color: white;
        border: 1px solid rgb(119, 75, 77);
    }
    #header {
        margin: 0;
        border-bottom: 2px solid rgb(119, 75, 77);
        padding: .5em 2em;
    }
    #header h1 {
        margin: 0;
        padding: .5em 0;
        color: black;
    }
    #main {
        margin: 0;
        padding: .5em 2em;
    }
    #sidebar {
        float: left;
        width: 170px;
    }
    #sidebar h2 {
        padding: 1em 0 .25em;
    }
    #sidebar ul {
        list-style-type: none;
        margin-left: 0;
        padding-left: 0;
        margin-bottom: 2em;
    }
    #sidebar li {
        margin: 0;
        padding-bottom: .25em;
    }
    #content {
        float: left;
        width: 580px;
        padding-bottom: 1.5em;
    }
    #left_column {
        float: left;
        width: 150px;
        padding-left: .5em;
    }
    #right_column {
        float: left;
        width: 300px;
        padding-left: 1em;
    }
    #footer {
        clear: both;
        margin-top: 1em;
        padding-right: 1em;
        border-top: 2px solid rgb(119, 75, 77);
    }
    #footer p {
        text-align: right;
        font-size: 80%;
        margin: 1em 0;
    }
    /********************************************************************
    * styles for the classes
    ********************************************************************/
    .right {
        text-align: right;
    }
    .left {
        text-align: left;
    }
    .cart_qty  {
        text-align: right;
        width: 3em;
    }
    .button_form {
        margin: 0;
        padding: 0;
        float: left;
    }
    .inline {
        display: inline;
        margin-left: .5em;
    }
    /********************************************************************
    * Styles for the Product Manager application
    ********************************************************************/
    #category_table form {
        margin: 0;
    }
    #category_table td {
        margin: 0;
        padding: .15em .5em 0 0;
    }
    #add_category_form {
        margin: 0;
    }
    #add_category_form input {
        margin-right: .5em;  
    }
    #add_admin_user_form  label {
        width: 8.5em;
    }
    #edit_and_delete_buttons {
        margin-bottom: .5em;
    }
    #edit_and_delete_buttons form {
        display: inline;
    }
    #image_manager input {
        margin: .25em;
    }
    /********************************************************************
    * Styles for the Product Catalog application
    ********************************************************************/
    #product_image_column {
        width: 8em;
        text-align: center;
    }
    /*******************************************************************/
    #add_to_cart_form {
        margin: .25em;
    }
    #add_to_cart_form input {
        float: none;
    }
    /*******************************************************************/
    #cart {
        margin: 0;
        padding: 1em .25em;
        border-collapse: collapse;
        width: 100%;
    }
    #cart_header th {
        border-bottom: 2px solid black;
    }
    #cart_footer td {
        border-top: 2px solid black;
        font-style: bold;
    }
    #cart td {
        padding: .25em 0;
    }
    /*******************************************************************/
    #login_form label {
        width: 5em;
        padding-right: 1em;
    }
    #login_form input[text] {

    }
    #payment_form label {
        width: 8em;
        padding-right: 1em;
    }
    #payment_form input[text] {
        width: 5em;
        margin: 0;
        padding-right: 1em;
    }
    #add_category label {
        text-align: left;
        width: 3em;
    }
    #add_category input { 
        margin-right: .25em;
    }
    </style>
</head>
<!-- the body section -->
<body>
<div id="page">
<div id="header">
    <h1>My Guitar Shop</h1>
</div>
<div id="main">
    <div id="content">
        <h2>Shipping Confirmation</h2>
        <p>Hello <b>$customer_name</b>,<br /><br />
        Thank you for shopping with us. We thought you'd like to know that we
        shipped your item(s), and that this completes your order. Your order
        is on its way, and can no longer be changed. Please allow 5-7 business
        days for your order to arrive.</p>
    <h2>Shipping Details</h2>
    <p>Order #$order_id<br />
    Placed on $order_date<br />
    Shipped on $ship_date</p>
    <p>&nbsp;</p>
    <p><b>Your order was sent to:</b><br />
        $customer_name<br />
        $ship_line1<br />
HTML;
if ( strlen($ship_line2) > 0 ) {
        $messageHTML .= $ship_line2.'<br />';
}
$messageHTML .= <<<HTML
        $ship_city, $ship_state $ship_zip
        </p>
    <p>&nbsp;</p>
    <table id="cart">
        <tr id="cart_header">
            <th class="left">Item</th>
            <th class="right">List Price</th>
            <th class="right">Savings</th>
            <th class="right">Your Cost</th>
            <th class="right">Quantity</th>
            <th class="right">Line Total</th>
        </tr>
HTML;
$message = <<<TEXT
My Guitar Shop

Shipping Confirmation
------------------

Hello $customer_name,

Thank you for shopping with us. We thought you'd like to know that we 
shipped your item(s), and that this completes your order. Your order is on  
its way, and can no longer be changed. Please allow 5-7 business days 
for your order to arrive.  

Shipping Details
----------------
    
Order #$order_id 
Placed on $order_date  
Shipped on $ship_date 

Your order was sent to:
$customer_name 
$ship_line1
TEXT;
if ( strlen($ship_line2) > 0 ) {
    $message .= $ship_line2 . ' ';
}
$message = <<<TEXT
$ship_city, $ship_state $ship_zip  
TEXT;
?>
<?php
    $subtotal = 0;
    $url = 'http://localhost/book_apps/guitar_shop/';
    foreach ($order_items as $item) :
        $product_id = $item['productID'];
        $product_url = $url . 'catalog/?product_id=' . $product_id;
        $product = get_product($product_id);
        $item_name = $product['productName'];
        $list_price = $item['itemPrice'];
        $list_price_txt = sprintf('$%.2f', $list_price);
        $savings = $item['discountAmount'];
        $savings_txt = sprintf('$%.2f', $savings);
        $your_cost = $list_price - $savings;
        $your_cost_txt = sprintf('$%.2f', $your_cost);
        $quantity = $item['quantity'];
        $line_total = $your_cost * $quantity;
        $line_total_txt = sprintf('$%.2f', $line_total);
        $subtotal += $line_total;
?>
<?php
$messageHTML .= <<<HTML
        <tr>
            <td><a href="$product_url" target="_blank">
                $item_name</a></td>
            <td class="right">
                $list_price_txt
            </td>
            <td class="right">
                $savings_txt
            </td>
            <td class="right">
                $your_cost_txt
            </td>
            <td class="right">
                $quantity
            </td>
            <td class="right">
                $line_total_txt
            </td>
        </tr>
HTML;
$message .= <<<TEXT
$item_name 
List Price: $list_price_txt
Savings:    $savings_txt 
Your Cost:  $your_cost_txt 
Quantity:   $quantity 
Line Total: $line_total_txt 
TEXT;
?>
<?php endforeach; ?>
<?php
$order_url = $url . 'account/?action=view_order&order_id=' . $order_id;
$subtotal_txt = sprintf('$%.2f', $subtotal);
$tax_amount_txt = sprintf('$%.2f', $order['taxAmount']);
$ship_amount_txt = sprintf('$%.2f', $order['shipAmount']);
$total = $subtotal + $order['taxAmount'] + $order['shipAmount'];
$total_txt = sprintf('$%.2f', $total);
$messageHTML .= <<<HTML
        <tr id="cart_footer">
            <td colspan="5" class="right">Subtotal:</td>
            <td class="right">
                $subtotal_txt
            </td>
        </tr>
        <tr>
            <td colspan="5" class="right">$ship_state Tax:</td>
            <td class="right">
                $tax_amount_txt
            </td>
        </tr>
        <tr>
            <td colspan="5" class="right">Shipping:</td>
            <td class="right">
                $ship_amount_txt
            </td>
        </tr>
            <tr>
            <td colspan="5" class="right">Total:</td>
            <td class="right">
                $total_txt
            </td>
        </tr>
    </table>
    <p>&nbsp;</p>
    <p>Thank you for shopping with us.<br /><b>My Guitar Shop</b></p>
    </div>
</div><!-- end main -->
<div id="footer">
    <p class="copyright">
        &copy; $copyright_date My Guitar Shop, Inc.
    </p>
</div><!-- end footer -->
</div><!-- end page -->
</body>
</html>
HTML;

$message .= <<<TEXT
--------------------------------------------
Subtotal: $subtotal_txt 
GA Tax:   $tax_amount_txt 
Shipping: $ship_amount_txt 
Total:    $total_txt


Thank you for shopping with us.
My Guitar Shop
TEXT;
?>
<?php
$email = new PHPMailer();

$email->IsSMTP();
// $email->IsSendmail();
$email->Host       = "smtp.gmail.com";   //Will need to be modified
$email->SMTPAuth   = true;  
$email->Port       = 465;
$email->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)  
//$email->SMTPSecure = 'tls';  
$email->SMTPSecure = 'ssl';    
$email->Username   = "guitarshopinc@gmail.com"; // SMTP account username Will need to be modified
$email->Password   = "mrlerch14";        // SMTP account password Will need to be modified
$email->SetFrom('guitarshopinc@gmail.com', 'My Guitar Shop');
$email->SingleTo  = true;	// true allows that only one person will receive an email per array group
$email->Subject   = 'Your Order with My Guitar Shop has Shipped!'; // appears in subject of email
$email->Body      = $messageHTML;  // the body will interpret HTML - $messageHTML identified above
$email->AltBody = $message;            // the AltBody will not interpret HTML - $message identified above
$destination_email_address = "$customer_email";  // destination email address
$destination_user_name = "$customer_name";   // Destination user name
$email->AddAddress($destination_email_address, $destination_user_name); 
	// AddAddress method identifies destination and sends email	
if(!$email->Send()) {
  return false;
} else {
  return true;
}

} // end function

?>
