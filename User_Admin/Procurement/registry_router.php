<?php
    if (!isset($_GET['type'], $_GET['name'], $_GET['category'], $_GET['qty'])) {
        die("Invalid request. Missing parameters.");
    }

    $type     = $_GET['type'];
    $name     = urldecode($_GET['name']);
    $category = urldecode($_GET['category']);
    $qty      = intval($_GET['qty']);

    if ($type === 'consumable') {
        header("Location: ../asset_registry_module/consumable/save_asset.php?name={$name}&category={$category}&qty={$qty}");
        exit;
    } elseif ($type === 'nonconsumable') {
        header("Location: ../asset_registry_module/non-consumable/save_asset.php?name={$name}&category={$category}&qty={$qty}");
        exit;
    } else {
        die("Invalid type. Must be 'consumable' or 'nonconsumable'.");
    }
