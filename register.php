<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    $sql = "INSERT INTO users (username,password,role) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        header("Location: login.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="container mt-5">
  <h2>Register</h2>
  <form method="POST">
    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Role</label>
      <select name="role" class="form-control">
        <option value="user">User</option>
        <option value="admin">Admin</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
    <a href="login.php" class="btn btn-link">Login</a>
  </form>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>