<?php
include 'config.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
$role     = $_SESSION['role'];

// --- Pagination setup
$limit = 25; // data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// --- Pencarian
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where = "";
if ($search != '') {
    $where = "WHERE nama_toko LIKE '%$search%' 
              OR nama_pic LIKE '%$search%'
              OR alamat LIKE '%$search%'
              OR region LIKE '%$search%'
              OR area LIKE '%$search%'
              OR kota_kabupaten LIKE '%$search%'
              OR class LIKE '%$search%'";
}

// --- Hitung total data
$total_result = $conn->query("SELECT COUNT(*) as total FROM customers $where");
$total_data   = $total_result->fetch_assoc()['total'];
$total_pages  = ceil($total_data / $limit);

// --- Ambil data sesuai halaman
$sql    = "SELECT * FROM customers $where ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// --- Export Excel
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=data_customers.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<table border='1'>";
    echo "<tr>
            <th>No</th>
            <th>Nama Toko</th>
            <th>Nama PIC</th>
            <th>Alamat</th>
            <th>No Telp</th>
            <th>Region</th>
            <th>Area</th>
            <th>Kota/Kabupaten</th>
            <th>Class</th>
          </tr>";

    $export_sql    = "SELECT * FROM customers $where ORDER BY id DESC";
    $export_result = $conn->query($export_sql);
    $no = 1;
    while ($row = $export_result->fetch_assoc()) {
        echo "<tr>
                <td>".$no++."</td>
                <td>".$row['nama_toko']."</td>
                <td>".$row['nama_pic']."</td>
                <td>".$row['alamat']."</td>
                <td>".$row['no_telp']."</td>
                <td>".$row['region']."</td>
                <td>".$row['area']."</td>
                <td>".$row['kota_kabupaten']."</td>
                <td>Class ".$row['class']."</td>
              </tr>";
    }
    echo "</table>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>DATA CUSTOMER</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="customers.php">PT. SLM</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link active" href="customers.php">DATA CUSTOMER</a></li>
            <li class="nav-item"><a class="nav-link" href="#">DAILY VISIT</a></li>
            <li class="nav-item"><a class="nav-link" href="#">TIMESTAMP</a></li>
            <li class="nav-item"><a class="nav-link" href="#">PURCHASE ORDER</a></li>
            <li class="nav-item"><a class="nav-link" href="#">STOCK BARANG</a></li>
            <li class="nav-item">
                <a class="nav-link text-white btn btn-sm btn-danger px-3" href="logout.php">LOGOUT</a>
            </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Content -->
  <div class="container mt-4">
    <h3>SELAMAT DATANG, <?= $username ?> (<?= $role ?>)</h3>

    <hr>
    <h4 id="customers">DATA CUSTOMERS</h4>
    <a href="customer_add.php" class="btn btn-primary btn-sm mb-3">Tambah Customer</a>

    <!-- Form Pencarian -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-10">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control">
        </div>
        <div class="col">
            <button type="submit" class="btn btn-primary w-100">CARI</button>
        </div>
        <div class="col">
            <a href="customers.php" class="btn btn-warning w-100">RESET</a>
        </div>
      </form>

      <div class="col">
          <a href="customers.php?export=excel&search=<?= urlencode($search) ?>" class="btn btn-success">EXPORT EXCEL</a>
      </div>

    <!-- Tabel -->
    <div class="table-responsive">
      <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
          <tr>
            <th>NO</th>
            <th>NAMA TOKO</th>
            <th>NAMA PIC</th>
            <th>ALAMAT</th>
            <th>NO. TELP</th>
            <th>REGION</th>
            <th>AREA</th>
            <th>KOTA/KABUPATEN</th>
            <th>CLASS</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          if ($result->num_rows > 0): 
              $no = $offset + 1;
              while($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nama_toko'] ?></td>
                <td><?= $row['nama_pic'] ?></td>
                <td><?= $row['alamat'] ?></td>
                <td><?= $row['no_telp'] ?></td>
                <td><?= $row['region'] ?></td>
                <td><?= $row['area'] ?></td>
                <td><?= $row['kota_kabupaten'] ?></td>
                <td>CLASS <?= $row['class'] ?></td>
              </tr>
          <?php 
              endwhile;
          else: ?>
            <tr>
              <td colspan="9" class="text-center">BELUM ADA DATA CUSTOMER</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center">
        <!-- Tombol Previous -->
        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
            <span aria-hidden="true">PREVIOUS</span>
          </a>
        </li>

        <!-- Nomor Halaman -->
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>

        <!-- Tombol Next -->
        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
            <span aria-hidden="true">NEXT</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>