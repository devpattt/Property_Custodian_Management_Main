<?php
function generateAssetTag($id) {
    // Format: ASSET-YYYY-000X
    return "ASSET-" . date("Y") . "-" . str_pad($id, 4, "0", STR_PAD_LEFT);
}
?>