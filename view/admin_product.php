<?php
    // Parse data
    $category_id = $product['categoryID'];
    $product_code = $product['productCode'];
    $product_name = $product['productName'];
    $description = $product['description'];
    $list_price = $product['listPrice'];
    $discount_percent = $product['discountPercent'];
    $pending= $product['pending'];
    $available_stock = $product['qtyOnHand'] - $product['pending'];

    // Add HMTL tags to the description
    $description = add_tags($description);

    // Calculate discounts
    $discount_amount = round($list_price * ($discount_percent / 100), 2);
    $unit_price = $list_price - $discount_amount;

    // Format discounts
    $discount_percent = number_format($discount_percent, 0);
    $discount_amount = number_format($discount_amount, 2);
    $unit_price = number_format($unit_price, 2);

    // Get image URL and alternate text
    $image_filename = $product_code . '_m.png';
    $image_path = $app_path . 'images/' . $image_filename;
    $image_alt = 'Image filename: ' . $image_filename;
?>

<h1><?php echo $product_name; ?></h1>
<div id="left_column">
    <p><img src="<?php echo $image_path; ?>"
            alt="<?php echo $image_alt; ?>" /></p>
</div>

<div id="right_column">
    <p><b>List Price:</b>
        <?php echo '$' . $list_price; ?></p>
    <p><b>Discount:</b>
        <?php echo $discount_percent . '%'; ?></p>
    <p><b>Your Price:</b>
        <?php echo '$' . $unit_price; ?>
        (You save
        <?php echo '$' . $discount_amount; ?>)</p>
    <p><b>Quantity on Hand:</b>
        <?php echo $qty; ?></p>
    <p><b>Pending Shipment:</b>
        <?php echo $pending; ?></p>
    <p><b>Available Stock: </b>
        <?php echo $available_stock; ?></p>
    <h2>Description</h2>
    <?php echo $description; ?>
</div>