<?php include 'view/header.php'; ?>
<?php include 'view/sidebar_admin.php'; ?>
<div id="content">
    <h1>Pending Orders</h1>
    <?php if (count($new_orders) > 0 ) : ?>
        <ul>
            <?php foreach($new_orders as $order) :
                $order_id = $order['orderID'];
                $order_date = strtotime($order['orderDate']);
                $order_date = date('M j, Y', $order_date);
                $url = $app_path . 'admin/shipping' .
                       '?action=view_order&order_id=' . $order_id;
                ?>
                <li>
                    <a href="<?php echo $url; ?>">Order # 
                    <?php echo $order_id; ?></a> for
                    <?php echo $order['firstName'] . ' ' .
                               $order['lastName']; ?> placed on
                    <?php echo $order_date; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>There are no pending orders.</p>
    <?php endif; ?>
    
</div>
<?php include 'view/footer.php'; ?>