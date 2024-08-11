<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // 'admin' or 'user'

    if ($role == 'admin') {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    }
    
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $role;
        header('Location: dashboard.php');
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="text-center">Login</h2>
                        <p class="text-center text-muted">Assignment for Quadiro Technologies</p>
                        <form method="POST">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Role:</label>
                                <select class="form-control" id="role" name="role">
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger mt-2"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <p class="mt-2 text-center">Don't have an account? <a href="register.php">Register here</a>.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
