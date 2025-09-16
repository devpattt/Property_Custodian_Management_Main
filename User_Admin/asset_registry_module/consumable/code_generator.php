<?php
function generateAssetTag($id) {
    // Format: ASSET-YYYY-XXXX (random 4 alphanumeric characters)
    $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
        return "ASSET-" . date("Y") . "-" . $random;
}
?>