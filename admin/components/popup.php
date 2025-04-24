<?php
function displayPopup($message, $type = 'success') {
    $typeClass = $type === 'success' ? 'popup-success' : 'popup-error';
    echo "<div class='popup-overlay show'>
            <div class='popup-message {$typeClass}'>
                <span>{$message}</span>
            </div>
          </div>";
}
?>