<?php
/**
 * Authentication Check
 * Include this file at the top of protected admin pages
 */
require_once '../config/session.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>
