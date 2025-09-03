<?php
include 'config.php';
if(isset($_POST['query'])){
    $query = $conn->real_escape_string($_POST['query']);
    $sql = "SELECT * FROM customers 
            WHERE nama_toko LIKE '%$query%' 
            ORDER BY nama_toko ASC LIMIT 10";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "<a href='#' class='list-group-item list-group-item-action toko-item'
                    data-nama='{$row['nama_toko']}'
                    data-pic='{$row['nama_pic']}'
                    data-alamat='{$row['alamat']}'
                    data-area='{$row['area']}'>
                    {$row['nama_toko']}
                  </a>";
        }
    } else {
        echo "<span class='list-group-item'>Tidak ditemukan</span>";
    }
}
?>