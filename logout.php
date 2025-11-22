<!-- ============================================
STEP 9: LOGOUT.PHP - Logout Functionality
Save as: logout.php
============================================ -->
<?php
session_start();
session_destroy();
header('Location: index.php');
exit;
?>
