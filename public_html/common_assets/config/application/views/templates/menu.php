<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
    <a href="index.html" class="app-brand-link">
        <span class="app-brand-logo demo">
        <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
            fill="#7367F0" />
            <path
            opacity="0.06"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
            fill="#161616" />
            <path
            opacity="0.06"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
            fill="#161616" />
            <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
            fill="#7367F0" />
        </svg>
        </span>
        <span class="app-brand-text demo menu-text fw-bold">Vuexy</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
        <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
    </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item <?php if (strpos($this->uri->uri_string(), 'clients_orders/index') !== false) echo 'active' ?>">
            <a href="<?php echo site_url('clients_orders/index') ?>" class="menu-link">
                <i class="menu-icon tf-icons ti ti-table"></i>
                <div><?php echo $lang_arr['clients_orders_label'] ?></div>
            </a>
        </li>
        <li class="menu-item <?php if (strpos($this->uri->uri_string(), 'user/index') !== false) echo 'active' ?>">
            <a href="<?php echo site_url('user/index') ?>" class="menu-link">
                <i class="menu-icon tf-icons ti ti-table fa fa-address-book"></i>
                <div><?php echo $lang_arr['user_management'] ?></div>
            </a>
        </li>
        <li class="menu-item <?php if (strpos($this->uri->uri_string(), 'settings') !== false && strpos($this->uri->uri_string(), 'project_settings') === false && strpos($this->uri->uri_string(), 'modules_settings') === false) echo 'active' ?>">
            <a href="<?php echo site_url('settings/') ?>" class="menu-link">
                <i class="menu-icon tf-icons ti ti-table fa fa-code"></i>
                <div><?php echo $lang_arr['kitchen_account_settings_label'] ?></div>
            </a>
        </li>
        <li class="menu-item <?php if (strpos($this->uri->uri_string(), 'constructor') !== false) echo 'active' ?>">
            <a href="<?php echo site_url('constructor') ?>" class="menu-link">
                <i class="menu-icon tf-icons ti ti-table fa fa-cogs"></i>
                <div><?php echo $lang_arr['kitchen_constructor_settings_label'] ?></div>
            </a>
        </li>

        <?php if ($this->config->item('sub_account') == false && !$this->config->item('antar')): ?>
            <li class="menu-item  <?php if (strpos($this->uri->uri_string(), 'modules/') !== false ||
                        strpos($this->uri->uri_string(), 'modules_templates/') !== false ||
                        strpos($this->uri->uri_string(), 'catalog/items/modules') !== false ||
                        strpos($this->uri->uri_string(), 'catalog/categories/modules') !== false
            ) echo 'active' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-toggle-left"></i>
                    <div><?php echo $lang_arr['kitchen_modules_label'] ?></div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item <?php if (strpos($this->uri->uri_string(), 'catalog/categories/modules') !== false) echo 'active' ?>">
                        <a href="<?php echo site_url('catalog/categories/modules') ?>" class="menu-link">
                            <div><?php echo $lang_arr['kitchen_modules_categories_label'] ?></div>
                        </a>
                    </li>
                    <li class="menu-item <?php if (strpos($this->uri->uri_string(), 'catalog/items/modules') !== false) echo 'active' ?>">
                        <a href="<?php echo site_url('catalog/items/modules') ?>" class="menu-link">
                            <div><?php echo $lang_arr['modules_list'] ?></div>
                        </a>
                    </li>
                    <li class="menu-item <?php if (strpos($this->uri->uri_string(), 'modules/not_active/1') !== false) echo 'active' ?>">
                        <a href="<?php echo site_url('modules/not_active/1') ?>" class="menu-link">
                            <div><?php echo $lang_arr['kitchen_inactive_bottom_modules'] ?></div>
                        </a>
                    </li>
                    <li class="menu-item <?php if (strpos($this->uri->uri_string(), 'modules/not_active/2') !== false) echo 'active' ?>">
                        <a href="<?php echo site_url('modules/not_active/2') ?>" class="menu-link">
                            <div><?php echo $lang_arr['kitchen_inactive_top_modules'] ?></div>
                        </a>
                    </li>
                    <li class="menu-item <?php if (strpos($this->uri->uri_string(), 'modules/not_active/3') !== false) echo 'active' ?>">
                        <a href="<?php echo site_url('modules/not_active/3') ?>" class="menu-link">
                            <div><?php echo $lang_arr['kitchen_inactive_penals_modules'] ?></div>
                        </a>
                    </li>
                </ul>
            </li>


        <?php endif; ?>



    </ul>
    
    
   
    
</aside>
        <!-- / Menu -->