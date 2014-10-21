<div id="sidebar">
    <h2>Links</h2>
    <ul>
        <li>
            <a href="<?php echo $app_path . 'cart'; ?>">View Cart</a>
        </li>
            <?php
            // Check if user is logged in and
            // display appropriate account links
            $account_url = $app_path . 'account';
            $logout_url = $account_url . '?action=logout';
            if (isset($_SESSION['user'])) :
            ?>
                <li><a href="<?php echo $account_url; ?>">My Account</a></li>
                <li><a href="<?php echo $logout_url; ?>">Logout</a>
            <?php elseif (!isset($_SESSION['admin'])) : ?>
                <li><a href="<?php echo $account_url; ?>">Login/Register</a></li>
            <?php endif; ?>
        <li>
            <a href="<?php echo $app_path; ?>">
               <?php echo Home; ?>
            </a>
        </li>
        
        <h2>Categories</h2>
        <!-- display links for all categories -->
        <?php
            require_once('model/database.php');
            require_once('model/category_db.php');
            
            $categories = get_categories();
            foreach($categories as $category) :
                $name = $category['categoryName'];
                $id = $category['categoryID'];
                $url = $app_path . 'catalog?category_id=' . $id;
        ?>
        <li>
            <a href="<?php echo $url; ?>">
               <?php echo $name; ?>
            </a>
        </li>
        <?php endforeach; ?>
        
        <?php if (isset($_SESSION['admin'])) : ?>
        <h2>Admin</h2>
        <li>
            <p><a href="<?php echo $app_path . 'admin/product/'; ?>">Product Manager</a></p>
            <p><a href="<?php echo $app_path . 'admin/category/'; ?>">Category Manager</a></p>
            <p><a href="<?php echo $app_path . 'admin/orders/'; ?>">Order Manager</a></p>
            <p><a href="<?php echo $app_path . 'admin/account/'; ?>">Account Manager</a></p>
            <p><a href="<?php echo $app_path . 'admin/shipping/'; ?>">Shipping Manager</a></p>
            <p><a href="<?php echo $app_path . 'admin/account?action=logout'; ?>">Logout</a></p>
        </li>
        <?php elseif (!isset($_SESSION['user'])) : ?>
        <h2>Admin</h2>
            <li>
                <p><a href="<?php echo $app_path . 'admin/account/'; ?>">Login</a></p>
            </li>
        <?php endif; ?> 
    </ul>
</div>