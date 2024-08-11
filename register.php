<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // 'admin' or 'user'

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    if ($role == 'admin') {
        $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    }

    if ($stmt->execute([$username, $hashedPassword])) {
        $success = "Registration successful. You can now log in.";
    } else {
        $error = "Registration failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="text-center">Register</h2>
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
                            <button type="submit" class="btn btn-success btn-block">Register</button>
                            <?php if (isset($success)): ?>
                                <div class="alert alert-success mt-2"><?php echo $success; ?></div>
                            <?php endif; ?>
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger mt-2"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <p class="mt-2 text-center">Already have an account? <a href="index.php">Login here</a>.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
