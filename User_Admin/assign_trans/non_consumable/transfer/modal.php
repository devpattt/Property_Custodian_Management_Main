<?php
if (!function_exists("renderMessageModal")) {
    function renderMessageModal($id, $title, $message) {
        ?>
        <div id="<?= $id ?>" class="modal" style="display:block;">
            <div class="modal-content">
                <span class="close" onclick="window.location.href='transfer.php'">&times;</span>
                <h2><?= $title ?></h2>
                <p><?= $message ?></p>
                <button class="close-btn" onclick="window.location.href='transfer.php'">OK</button>
            </div>
        </div>
        <?php
    }
}
?>