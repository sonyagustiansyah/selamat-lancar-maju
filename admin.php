<?php
include 'config.php';
if ($_SESSION['role'] != 'admin') {
    die("Akses ditolak!");
}
echo "<h2>Admin Panel</h2><a href='dashboard.php'>Kembali</a>";