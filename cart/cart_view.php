<?php include '../view/header.php'; ?>
<div id="content">
    <h1>Your Cart</h1>
    <?php if (isset($error)) : ?>
        <p id="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (cart_product_count() == 0) : ?>
        <p>There are no products in your cart.</p>
    <?php else: ?>
        <p>To remove an item from your cart, change its quantity to 0.</p>
        <form action="." method="post">
            <input type="hidden" name="action" value="update" />
            <table id="cart">
            <tr id="cart_header">
                <th class="left">Item</th>
                <th class="right">Price</th>
                <th class="right">Quantity</th>
                <th class="right">Total</th>
                </tr>
            <?php foreach ($cart as $product_id => $item) : ?>
            <?php
            $error = false;
            $product = get_product($product_id);
            $available_stock = $product['qtyOnHand'] - $product['pending'];
            if ($item['quantity'] > $available_stock) {
                $item['quantity'] = $available_stock;
                cart_update_item($product_id, $item['quantity']);
                $error = true;
            }
            ?>
            <tr>
                <td><?php echo $item['name']; ?>
                    <?php if ($error) : ?>
                        <br><span class="error">Quantity exceeds available stock. <b>Quantity adjusted</b>.</span>
                    <?php endif; ?>
                </td>
                <td class="right">
                    <?php echo sprintf('$%.2f', $item['unit_price']); ?>
                </td>
                <td class="right">
                    <input type="text" size="3" class="right"
                           name="items[<?php echo $product_id; ?>]"
                           value="<?php echo $item['quantity']; ?>" />
                </td>
                <td class="right">
                    <?php echo sprintf('$%.2f', $item['line_price']); ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr id="cart_footer" >
                <td colspan="3" class="right" ><b>Subtotal</b></td>
                <td class="right">
                    <?php echo sprintf('$%.2f', cart_subtotal()); ?>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="right">
                    <input type="submit" value="Update Cart" />
                </td>
            </tr>
            </table>
        </form>
        
    <?php endif; ?>

    <p>Return to: <a href="../">Home</a></p>

    <!-- display most recent category -->
    <?php if (isset($_SESSION['last_category_id'])) :
            $category_url = '../catalog' .
                '?category_id=' . $_SESSION['last_category_id'];
        ?>
        <p>Return to: <a href="<?php echo $category_url; ?>">
            <?php echo $_SESSION['last_category_name']; ?></a></p>
    <?php endif; ?>

    <!-- if cart has items, display the Checkout link -->
    <?php if (cart_product_count() > 0) : ?>
        <p>
            Proceed to: <a href="../checkout">Checkout</a>
        </p>
    <?php endif; ?>
</div>
<?php include '../view/footer.php'; ?>