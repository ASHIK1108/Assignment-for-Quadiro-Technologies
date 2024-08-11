<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch car records
$stmt = $pdo->query("SELECT * FROM cars");
$cars = $stmt->fetchAll();

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $car_name = $_POST['car_name'];
        $year = $_POST['year'];
        $price = $_POST['price'];
        
        $stmt = $pdo->prepare("INSERT INTO cars (car_name, manufacturing_year, price) VALUES (?, ?, ?)");
        $stmt->execute([$car_name, $year, $price]);
        header('Location: dashboard.php');
    }
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $car_name = $_POST['car_name'];
        $year = $_POST['year'];
        $price = $_POST['price'];

        $stmt = $pdo->prepare("UPDATE cars SET car_name = ?, manufacturing_year = ?, price = ? WHERE id = ?");
        $stmt->execute([$car_name, $year, $price, $id]);
        header('Location: dashboard.php');
    }
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: dashboard.php');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Admin Dashboard</h2>
        <p>Total Cars: <?php echo count($cars); ?></p>

        <div class="card mb-3">
            <div class="card-header">Add New Car</div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="car_name">Car Name:</label>
                            <input type="text" class="form-control" id="car_name" name="car_name" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="year">Manufacturing Year:</label>
                            <input type="number" class="form-control" id="year" name="year" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="price">Price:</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                        </div>
                    </div>
                    <button type="submit" name="add" class="btn btn-success">Add Car</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Car List</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Car Name</th>
                            <th>Year</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cars as $car): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($car['car_name']); ?></td>
                            <td><?php echo htmlspecialchars($car['manufacturing_year']); ?></td>
                            <td><?php echo htmlspecialchars($car['price']); ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="editCar(<?php echo htmlspecialchars(json_encode($car)); ?>)">Edit</button>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $car['id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function editCar(car) {
            document.getElementById('car_name').value = car.car_name;
            document.getElementById('year').value = car.manufacturing_year;
            document.getElementById('price').value = car.price;
            document.getElementsByName('add')[0].setAttribute('name', 'update');
            document.querySelector('form').action = '';
            document.querySelector('form').innerHTML += '<input type="hidden" name="id" value="' + car.id + '">';
        }
    </script>
</body>
</html>
