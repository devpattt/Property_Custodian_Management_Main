<?php
function generateReferenceNo($id) {
    // Generate unique reference (first assignment only)
    $reference_no = "REF-" . date("Ymd") . "-" . strtoupper(substr(uniqid(), -5));
    return $reference_no;
}
?>