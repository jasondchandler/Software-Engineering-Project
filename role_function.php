<?php
function allow(string $permission): bool {
    return isset($_SESSION["permissions"][$permission]);
}

?>