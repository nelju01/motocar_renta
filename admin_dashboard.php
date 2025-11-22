<<<<<<< HEAD
<!-- ============================================
STEP 11: ADMIN_DASHBOARD.PHP - Admin CRUD Dashboard
Save as: admin_dashboard.php
============================================ -->
<?php
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Handle DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM vehicles WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = "Vehicle deleted successfully!";
    header('Location: admin_dashboard.php');
    exit;
}

// Handle STATUS UPDATE
if (isset($_GET['update_status'])) {
    $id = $_GET['update_status'];
    $new_status = $_GET['status'];
    $stmt = $pdo->prepare("UPDATE vehicles SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $id]);
    $_SESSION['success'] = "Status updated successfully!";
    header('Location: admin_dashboard.php');
    exit;
}

// Handle ADD/EDIT
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $price = $_POST['price_per_day'];
    $image = $_POST['image'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    if (isset($_POST['vehicle_id']) && !empty($_POST['vehicle_id'])) {
        // UPDATE
        $id = $_POST['vehicle_id'];
        $stmt = $pdo->prepare("UPDATE vehicles SET name=?, type=?, brand=?, model=?, price_per_day=?, image=?, description=?, status=? WHERE id=?");
        $stmt->execute([$name, $type, $brand, $model, $price, $image, $description, $status, $id]);
        $_SESSION['success'] = "Vehicle updated successfully!";
    } else {
        // INSERT
        $stmt = $pdo->prepare("INSERT INTO vehicles (name, type, brand, model, price_per_day, image, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $type, $brand, $model, $price, $image, $description, $status]);
        $_SESSION['success'] = "Vehicle added successfully!";
    }
    header('Location: admin_dashboard.php');
    exit;
}

// Get vehicle for editing
$edit_vehicle = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = ?");
    $stmt->execute([$id]);
    $edit_vehicle = $stmt->fetch();
}

// Get all vehicles
$vehicles = $pdo->query("SELECT * FROM vehicles ORDER BY created_at DESC")->fetchAll();

// Get statistics
$total_vehicles = $pdo->query("SELECT COUNT(*) FROM vehicles")->fetchColumn();
$available = $pdo->query("SELECT COUNT(*) FROM vehicles WHERE status='available'")->fetchColumn();
$booked = $pdo->query("SELECT COUNT(*) FROM vehicles WHERE status='booked'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MotoRide</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        /* Admin Navigation */
        nav {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            color: white;
            font-size: 1.8em;
            font-weight: bold;
        }

        .admin-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 25px;
            border-radius: 20px;
            text-decoration: none;
            color: white;
            transition: all 0.3s;
            font-weight: 600;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            color: #333;
            font-size: 2.2em;
        }

        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .stat-icon {
            font-size: 3em;
        }

        .stat-info h3 {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: #333;
            font-size: 2em;
            font-weight: bold;
        }

        /* Success Message */
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }

        /* Form Section */
        .form-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-section h2 {
            color: #333;
            margin-bottom: 25px;
            font-size: 1.8em;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #f093fb;
            box-shadow: 0 0 0 3px rgba(240, 147, 251, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(240, 147, 251, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        /* Vehicles Table */
        .table-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .table-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
        }

        th {
            font-weight: 600;
        }

        tbody tr {
            border-bottom: 1px solid #eee;
        }

        tbody tr:hover {
            background: #f9f9f9;
        }

        .vehicle-img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 600;
        }

        .badge.available {
            background: #d4edda;
            color: #155724;
        }

        .badge.booked {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.85em;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-edit {
            background: #007bff;
            color: white;
        }

        .btn-edit:hover {
            background: #0056b3;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .btn-status {
            background: #28a745;
            color: white;
        }

        .btn-status:hover {
            background: #218838;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 0.9em;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <nav>
        <div class="nav-container">
            <div class="logo">⚙️ Admin Dashboard</div>
            <div class="admin-info">
                <span>👨‍💼 <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Vehicle Management</h1>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">🚗</div>
                <div class="stat-info">
                    <h3>Total Vehicles</h3>
                    <p><?php echo $total_vehicles; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-info">
                    <h3>Available</h3>
                    <p><?php echo $available; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-info">
                    <h3>Booked</h3>
                    <p><?php echo $booked; ?></p>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="success">
                ✓ <?php echo $_SESSION['success'];
                    unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Add/Edit Form -->
        <div class="form-section">
            <h2><?php echo $edit_vehicle ? '✏️ Edit Vehicle' : '➕ Add New Vehicle'; ?></h2>
            <form method="POST">
                <input type="hidden" name="vehicle_id" value="<?php echo $edit_vehicle ? $edit_vehicle['id'] : ''; ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label>Vehicle Name *</label>
                        <input type="text" name="name" required value="<?php echo $edit_vehicle ? htmlspecialchars($edit_vehicle['name']) : ''; ?>" placeholder="Honda Click 150i">
                    </div>

                    <div class="form-group">
                        <label>Type *</label>
                        <select name="type" required>
                            <option value="motorcycle" <?php echo ($edit_vehicle && $edit_vehicle['type'] == 'motorcycle') ? 'selected' : ''; ?>>Motorcycle</option>
                            <option value="car" <?php echo ($edit_vehicle && $edit_vehicle['type'] == 'car') ? 'selected' : ''; ?>>Car</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Brand *</label>
                        <input type="text" name="brand" required value="<?php echo $edit_vehicle ? htmlspecialchars($edit_vehicle['brand']) : ''; ?>" placeholder="Honda">
                    </div>

                    <div class="form-group">
                        <label>Model *</label>
                        <input type="text" name="model" required value="<?php echo $edit_vehicle ? htmlspecialchars($edit_vehicle['model']) : ''; ?>" placeholder="Click 150i">
                    </div>

                    <div class="form-group">
                        <label>Price per Day (₱) *</label>
                        <input type="number" step="0.01" name="price_per_day" required value="<?php echo $edit_vehicle ? $edit_vehicle['price_per_day'] : ''; ?>" placeholder="350.00">
                    </div>

                    <div class="form-group">
                        <label>Status *</label>
                        <select name="status" required>
                            <option value="available" <?php echo ($edit_vehicle && $edit_vehicle['status'] == 'available') ? 'selected' : ''; ?>>Available</option>
                            <option value="booked" <?php echo ($edit_vehicle && $edit_vehicle['status'] == 'booked') ? 'selected' : ''; ?>>Booked</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>Image URL *</label>
                        <input type="url" name="image" required value="<?php echo $edit_vehicle ? htmlspecialchars($edit_vehicle['image']) : ''; ?>" placeholder="https://images.unsplash.com/photo-...">
                    </div>

                    <div class="form-group full-width">
                        <label>Description</label>
                        <textarea name="description" placeholder="Vehicle description..."><?php echo $edit_vehicle ? htmlspecialchars($edit_vehicle['description']) : ''; ?></textarea>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $edit_vehicle ? '💾 Update Vehicle' : '➕ Add Vehicle'; ?>
                    </button>
                    <?php if ($edit_vehicle): ?>
                        <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Vehicles Table -->
        <div class="table-section">
            <h2>📋 All Vehicles</h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Price/Day</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($vehicle['image']); ?>" alt="Vehicle" class="vehicle-img"></td>
                                <td><strong><?php echo htmlspecialchars($vehicle['name']); ?></strong></td>
                                <td><?php echo ucfirst($vehicle['type']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['brand']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['model']); ?></td>
                                <td><strong>₱<?php echo number_format($vehicle['price_per_day'], 2); ?></strong></td>
                                <td>
                                    <span class="badge <?php echo $vehicle['status']; ?>">
                                        <?php echo ucfirst($vehicle['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="?edit=<?php echo $vehicle['id']; ?>" class="btn-action btn-edit">✏️ Edit</a>
                                        <?php if ($vehicle['status'] == 'available'): ?>
                                            <a href="?update_status=<?php echo $vehicle['id']; ?>&status=booked" class="btn-action btn-status" onclick="return confirm('Mark as booked?')">📅 Book</a>
                                        <?php else: ?>
                                            <a href="?update_status=<?php echo $vehicle['id']; ?>&status=available" class="btn-action btn-status" onclick="return confirm('Mark as available?')">✅ Available</a>
                                        <?php endif; ?>
                                        <a href="?delete=<?php echo $vehicle['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this vehicle?')">🗑️ Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
=======
<!-- ============================================
STEP 11: ADMIN_DASHBOARD.PHP - Admin CRUD Dashboard
Save as: admin_dashboard.php
============================================ -->
<?php
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Handle DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM vehicles WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = "Vehicle deleted successfully!";
    header('Location: admin_dashboard.php');
    exit;
}

// Handle STATUS UPDATE
if (isset($_GET['update_status'])) {
    $id = $_GET['update_status'];
    $new_status = $_GET['status'];
    $stmt = $pdo->prepare("UPDATE vehicles SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $id]);
    $_SESSION['success'] = "Status updated successfully!";
    header('Location: admin_dashboard.php');
    exit;
}

// Handle ADD/EDIT
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $price = $_POST['price_per_day'];
    $image = $_POST['image'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    if (isset($_POST['vehicle_id']) && !empty($_POST['vehicle_id'])) {
        // UPDATE
        $id = $_POST['vehicle_id'];
        $stmt = $pdo->prepare("UPDATE vehicles SET name=?, type=?, brand=?, model=?, price_per_day=?, image=?, description=?, status=? WHERE id=?");
        $stmt->execute([$name, $type, $brand, $model, $price, $image, $description, $status, $id]);
        $_SESSION['success'] = "Vehicle updated successfully!";
    } else {
        // INSERT
        $stmt = $pdo->prepare("INSERT INTO vehicles (name, type, brand, model, price_per_day, image, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $type, $brand, $model, $price, $image, $description, $status]);
        $_SESSION['success'] = "Vehicle added successfully!";
    }
    header('Location: admin_dashboard.php');
    exit;
}

// Get vehicle for editing
$edit_vehicle = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = ?");
    $stmt->execute([$id]);
    $edit_vehicle = $stmt->fetch();
}

// Get all vehicles
$vehicles = $pdo->query("SELECT * FROM vehicles ORDER BY created_at DESC")->fetchAll();

// Get statistics
$total_vehicles = $pdo->query("SELECT COUNT(*) FROM vehicles")->fetchColumn();
$available = $pdo->query("SELECT COUNT(*) FROM vehicles WHERE status='available'")->fetchColumn();
$booked = $pdo->query("SELECT COUNT(*) FROM vehicles WHERE status='booked'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MotoRide</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        /* Admin Navigation */
        nav {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            color: white;
            font-size: 1.8em;
            font-weight: bold;
        }

        .admin-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 25px;
            border-radius: 20px;
            text-decoration: none;
            color: white;
            transition: all 0.3s;
            font-weight: 600;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            color: #333;
            font-size: 2.2em;
        }

        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .stat-icon {
            font-size: 3em;
        }

        .stat-info h3 {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: #333;
            font-size: 2em;
            font-weight: bold;
        }

        /* Success Message */
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }

        /* Form Section */
        .form-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-section h2 {
            color: #333;
            margin-bottom: 25px;
            font-size: 1.8em;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #f093fb;
            box-shadow: 0 0 0 3px rgba(240, 147, 251, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(240, 147, 251, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        /* Vehicles Table */
        .table-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .table-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
        }

        th {
            font-weight: 600;
        }

        tbody tr {
            border-bottom: 1px solid #eee;
        }

        tbody tr:hover {
            background: #f9f9f9;
        }

        .vehicle-img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 600;
        }

        .badge.available {
            background: #d4edda;
            color: #155724;
        }

        .badge.booked {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.85em;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-edit {
            background: #007bff;
            color: white;
        }

        .btn-edit:hover {
            background: #0056b3;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .btn-status {
            background: #28a745;
            color: white;
        }

        .btn-status:hover {
            background: #218838;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 0.9em;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <nav>
        <div class="nav-container">
            <div class="logo">⚙️ Admin Dashboard</div>
            <div class="admin-info">
                <span>👨‍💼 <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Vehicle Management</h1>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">🚗</div>
                <div class="stat-info">
                    <h3>Total Vehicles</h3>
                    <p><?php echo $total_vehicles; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-info">
                    <h3>Available</h3>
                    <p><?php echo $available; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-info">
                    <h3>Booked</h3>
                    <p><?php echo $booked; ?></p>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="success">
                ✓ <?php echo $_SESSION['success'];
                    unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Add/Edit Form -->
        <div class="form-section">
            <h2><?php echo $edit_vehicle ? '✏️ Edit Vehicle' : '➕ Add New Vehicle'; ?></h2>
            <form method="POST">
                <input type="hidden" name="vehicle_id" value="<?php echo $edit_vehicle ? $edit_vehicle['id'] : ''; ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label>Vehicle Name *</label>
                        <input type="text" name="name" required value="<?php echo $edit_vehicle ? htmlspecialchars($edit_vehicle['name']) : ''; ?>" placeholder="Honda Click 150i">
                    </div>

                    <div class="form-group">
                        <label>Type *</label>
                        <select name="type" required>
                            <option value="motorcycle" <?php echo ($edit_vehicle && $edit_vehicle['type'] == 'motorcycle') ? 'selected' : ''; ?>>Motorcycle</option>
                            <option value="car" <?php echo ($edit_vehicle && $edit_vehicle['type'] == 'car') ? 'selected' : ''; ?>>Car</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Brand *</label>
                        <input type="text" name="brand" required value="<?php echo $edit_vehicle ? htmlspecialchars($edit_vehicle['brand']) : ''; ?>" placeholder="Honda">
                    </div>

                    <div class="form-group">
                        <label>Model *</label>
                        <input type="text" name="model" required value="<?php echo $edit_vehicle ? htmlspecialchars($edit_vehicle['model']) : ''; ?>" placeholder="Click 150i">
                    </div>

                    <div class="form-group">
                        <label>Price per Day (₱) *</label>
                        <input type="number" step="0.01" name="price_per_day" required value="<?php echo $edit_vehicle ? $edit_vehicle['price_per_day'] : ''; ?>" placeholder="350.00">
                    </div>

                    <div class="form-group">
                        <label>Status *</label>
                        <select name="status" required>
                            <option value="available" <?php echo ($edit_vehicle && $edit_vehicle['status'] == 'available') ? 'selected' : ''; ?>>Available</option>
                            <option value="booked" <?php echo ($edit_vehicle && $edit_vehicle['status'] == 'booked') ? 'selected' : ''; ?>>Booked</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>Image URL *</label>
                        <input type="url" name="image" required value="<?php echo $edit_vehicle ? htmlspecialchars($edit_vehicle['image']) : ''; ?>" placeholder="https://images.unsplash.com/photo-...">
                    </div>

                    <div class="form-group full-width">
                        <label>Description</label>
                        <textarea name="description" placeholder="Vehicle description..."><?php echo $edit_vehicle ? htmlspecialchars($edit_vehicle['description']) : ''; ?></textarea>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $edit_vehicle ? '💾 Update Vehicle' : '➕ Add Vehicle'; ?>
                    </button>
                    <?php if ($edit_vehicle): ?>
                        <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Vehicles Table -->
        <div class="table-section">
            <h2>📋 All Vehicles</h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Price/Day</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($vehicle['image']); ?>" alt="Vehicle" class="vehicle-img"></td>
                                <td><strong><?php echo htmlspecialchars($vehicle['name']); ?></strong></td>
                                <td><?php echo ucfirst($vehicle['type']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['brand']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['model']); ?></td>
                                <td><strong>₱<?php echo number_format($vehicle['price_per_day'], 2); ?></strong></td>
                                <td>
                                    <span class="badge <?php echo $vehicle['status']; ?>">
                                        <?php echo ucfirst($vehicle['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="?edit=<?php echo $vehicle['id']; ?>" class="btn-action btn-edit">✏️ Edit</a>
                                        <?php if ($vehicle['status'] == 'available'): ?>
                                            <a href="?update_status=<?php echo $vehicle['id']; ?>&status=booked" class="btn-action btn-status" onclick="return confirm('Mark as booked?')">📅 Book</a>
                                        <?php else: ?>
                                            <a href="?update_status=<?php echo $vehicle['id']; ?>&status=available" class="btn-action btn-status" onclick="return confirm('Mark as available?')">✅ Available</a>
                                        <?php endif; ?>
                                        <a href="?delete=<?php echo $vehicle['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this vehicle?')">🗑️ Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
>>>>>>> origin/main
