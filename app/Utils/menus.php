<?php
$icons = require __DIR__ . '/icons.php';
return [
    [
        'label' => 'Dashboard',
        'icon' => $icons['dashboard'],
        'url' => 'dashboard',
    ],
    ['label' => 'Supplier', 'url' => 'supplier.index', 'icon' => $icons['people']],
    [
        'label' => 'Inventory',
        'icon' => $icons['inventory'],
        'permission' => ['purchase.index','sale.index','stock.index'],
        'children' => [
            ['label' => 'Purchases', 'url' => 'purchase.index', 'icon' => $icons['default']],
            ['label' => 'Sale', 'url' => 'sale.index', 'icon' => $icons['default']],
            ['label' => 'Stock', 'url' => 'stock.index', 'icon' => $icons['default']],
        ]
    ],
    [
        'label' => 'Medicines',
        'icon' => $icons['medicines'],
        'permission' => ['product.index','category.index','unit.index'],
        'children' => [
            ['label' => 'Medicine List', 'url' => 'product.index', 'icon' => $icons['default']],
            ['label' => 'Categories', 'url' => 'category.index', 'icon' => $icons['default']],
            ['label' => 'Units', 'url' => 'unit.index', 'icon' => $icons['default']],
        ]
    ],
    [
        'label' => 'Reports',
        'icon' => $icons['reports'],
        'permission' => ['report.purchases','report.sales'],
        'children' => [
            ['label' => 'Profit & Loss', 'icon' => $icons['default']],
            ['label' => 'Purchase Report', 'url' => 'report.purchases', 'icon' => $icons['default']],
            ['label' => 'Sales Report', 'url' => 'report.sales', 'icon' => $icons['default']],
        ]
    ],
    ['label' => 'Customers', 'url' => 'customer.index', 'icon' => $icons['people']],
    [
        'label' => 'App Settings',
        'icon' => $icons['settings'],
        'permission' => ['user.index','role.index','settings'],
        'children' => [
            ['label' => 'Users', 'url' => 'user.index', 'icon' => $icons['default']],
            ['label' => 'Role & Permission', 'url' => 'role.index', 'icon' => $icons['default']],
            ['label' => 'Settings', 'url' => 'settings', 'icon' => $icons['default']],
        ]
    ],
    [
        'label' => 'Prescription', 'url' => 'prescription.index',
        'icon' => $icons['prescription']
    ],
    [
        'label' => 'Payment Method', 'url' => 'gateway.index',
        'icon' => $icons['payment']
    ],
    [
        'label' => 'Database Backup', 'url' => 'database_backup.index',
        'icon' => $icons['database_backup']
    ],
];

