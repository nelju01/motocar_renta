<!-- ============================================
STEP 7: SHOP.PHP - Browse All Vehicles
Save as: shop.php
============================================ -->
<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}

// Get filter
$filter = isset($_GET['type']) ? $_GET['type'] : 'all';

// Build query
if ($filter == 'all') {
    $stmt = $pdo->query("SELECT * FROM vehicles ORDER BY created_at DESC");
} else {
    $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE type = ? ORDER BY created_at DESC");
    $stmt->execute([$filter]);
}
$vehicles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - MotoRide</title>
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

        nav {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
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
            padding: 15px 0;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 0;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 20px 25px;
            display: block;
            transition: all 0.3s;
            font-weight: 500;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: rgba(255, 255, 255, 0.2);
        }

        .user-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 20px;
            border-radius: 20px;
            text-decoration: none;
            color: white;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header h1 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 15px;
        }

        .filters {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 12px 30px;
            border: 2px solid #667eea;
            background: white;
            color: #667eea;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .vehicles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }

        .vehicle-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .vehicle-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .vehicle-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .vehicle-info {
            padding: 25px;
        }

        .vehicle-type {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 0.85em;
            margin-bottom: 10px;
        }

        .vehicle-info h3 {
            color: #333;
            font-size: 1.4em;
            margin-bottom: 10px;
        }

        .vehicle-info p {
            color: #666;
            margin: 10px 0;
            line-height: 1.6;
        }

        .vehicle-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .price {
            font-size: 1.5em;
            color: #667eea;
            font-weight: bold;
        }

        .status {
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
        }

        .status.available {
            background: #d4edda;
            color: #155724;
        }

        .status.booked {
            background: #f8d7da;
            color: #721c24;
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-results h3 {
            font-size: 2em;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <nav>
        <div class="nav-container">
            <div class="logo">🏍️ MotoRide</div>
            <ul class="nav-links">
                <li><a href="user_home.php">Home</a></li>
                <li><a href="shop.php" class="active">Shop</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            <div class="user-info">
                <span>👋 Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>🚗 Browse Our Fleet</h1>
            <p>Find your perfect ride from our collection</p>
        </div>

        <div class="filters">
            <a href="shop.php?type=all" class="filter-btn <?php echo $filter == 'all' ? 'active' : ''; ?>">All Vehicles</a>
            <a href="shop.php?type=motorcycle" class="filter-btn <?php echo $filter == 'motorcycle' ? 'active' : ''; ?>">🏍️ Motorcycles</a>
            <a href="shop.php?type=car" class="filter-btn <?php echo $filter == 'car' ? 'active' : ''; ?>">🚗 Cars</a>
        </div>

        <?php if (count($vehicles) > 0): ?>
            <div class="vehicles-grid">
                <?php foreach ($vehicles as $vehicle): ?>
                    <div class="vehicle-card">
                        <img src="<?php echo htmlspecialchars($vehicle['image']); ?>" alt="<?php echo htmlspecialchars($vehicle['name']); ?>">
                        <div class="vehicle-info">
                            <span class="vehicle-type"><?php echo ucfirst($vehicle['type']); ?></span>
                            <h3><?php echo htmlspecialchars($vehicle['name']); ?></h3>
                            <p><strong>Brand:</strong> <?php echo htmlspecialchars($vehicle['brand']); ?></p>
                            <p><strong>Model:</strong> <?php echo htmlspecialchars($vehicle['model']); ?></p>
                            <p><?php echo htmlspecialchars($vehicle['description']); ?></p>
                            <div class="vehicle-details">
                                <div class="price">₱<?php echo number_format($vehicle['price_per_day'], 2); ?>/day</div>
                                <span class="status <?php echo $vehicle['status']; ?>">
                                    <?php echo $vehicle['status'] == 'available' ? '✓ Available' : '✗ Booked'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <h3>No vehicles found</h3>
                <p>Try a different filter</p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
