<?php
include 'config.php';
if ($_SESSION['role'] != 'user') {
    die("Akses ditolak!");
}
echo "<h2>User Panel</h2><a href='dashboard.php'>Kembali</a>";