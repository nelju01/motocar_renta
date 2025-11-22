<<<<<<< HEAD
<!-- ============================================
STEP 6: USER_HOME.PHP - User Dashboard/Home
Save as: user_home.php
============================================ -->
<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}

$user_name = $_SESSION['user_name'];

// Get featured vehicles
$stmt = $pdo->query("SELECT * FROM vehicles WHERE status = 'available' LIMIT 3");
$featured_vehicles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - MotoRide</title>
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

        /* Navigation Bar */
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

        .user-info span {
            font-weight: 500;
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

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 20px;
            text-align: center;
        }

        .hero h1 {
            font-size: 3em;
            margin-bottom: 20px;
            animation: fadeInUp 0.8s ease;
        }

        .hero p {
            font-size: 1.3em;
            margin-bottom: 30px;
            animation: fadeInUp 1s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-hero {
            display: inline-block;
            background: white;
            color: #667eea;
            padding: 15px 40px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1em;
            transition: all 0.3s;
            animation: fadeInUp 1.2s ease;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        /* Featured Section */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        .section-title {
            text-align: center;
            font-size: 2.5em;
            color: #333;
            margin-bottom: 50px;
        }

        .vehicles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
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

        .vehicle-info h3 {
            color: #333;
            font-size: 1.5em;
            margin-bottom: 10px;
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

        .vehicle-info p {
            color: #666;
            margin: 10px 0;
        }

        .price {
            font-size: 1.5em;
            color: #667eea;
            font-weight: bold;
            margin: 15px 0;
        }

        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: 500;
        }

        .status.available {
            background: #d4edda;
            color: #155724;
        }

        .view-all {
            text-align: center;
            margin-top: 40px;
        }

        .btn-view-all {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-view-all:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        /* Features Section */
        .features {
            background: white;
            padding: 60px 20px;
            margin-top: 40px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-item {
            text-align: center;
            padding: 30px;
        }

        .feature-icon {
            font-size: 3em;
            margin-bottom: 20px;
        }

        .feature-item h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .feature-item p {
            color: #666;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <div class="logo">🏍️ MotoRide</div>
            <ul class="nav-links">
                <li><a href="user_home.php" class="active">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            <div class="user-info">
                <span>👋 Hi, <?php echo htmlspecialchars($user_name); ?></span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Welcome to MotoRide!</h1>
        <p>Your perfect ride is just a click away</p>
        <a href="shop.php" class="btn-hero">Browse Vehicles →</a>
    </div>

    <!-- Featured Vehicles -->
    <div class="container">
        <h2 class="section-title">Featured Vehicles</h2>
        <div class="vehicles-grid">
            <?php foreach ($featured_vehicles as $vehicle): ?>
                <div class="vehicle-card">
                    <img src="<?php echo htmlspecialchars($vehicle['image']); ?>" alt="<?php echo htmlspecialchars($vehicle['name']); ?>">
                    <div class="vehicle-info">
                        <span class="vehicle-type"><?php echo ucfirst($vehicle['type']); ?></span>
                        <h3><?php echo htmlspecialchars($vehicle['name']); ?></h3>
                        <p><?php echo htmlspecialchars($vehicle['description']); ?></p>
                        <div class="price">₱<?php echo number_format($vehicle['price_per_day'], 2); ?>/day</div>
                        <span class="status available">✓ Available</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="view-all">
            <a href="shop.php" class="btn-view-all">View All Vehicles</a>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features">
        <h2 class="section-title">Why Choose MotoRide?</h2>
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">🚗</div>
                <h3>Wide Selection</h3>
                <p>Choose from our extensive fleet of motorcycles and cars</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">💰</div>
                <h3>Best Prices</h3>
                <p>Competitive rates with no hidden charges</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">⚡</div>
                <h3>Quick Booking</h3>
                <p>Book your vehicle in just a few clicks</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🛡️</div>
                <h3>Safe & Reliable</h3>
                <p>All vehicles are well-maintained and insured</p>
            </div>
        </div>
    </div>
</body>

</html>
=======
<!-- ============================================
STEP 6: USER_HOME.PHP - User Dashboard/Home
Save as: user_home.php
============================================ -->
<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}

$user_name = $_SESSION['user_name'];

// Get featured vehicles
$stmt = $pdo->query("SELECT * FROM vehicles WHERE status = 'available' LIMIT 3");
$featured_vehicles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - MotoRide</title>
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

        /* Navigation Bar */
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

        .user-info span {
            font-weight: 500;
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

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 20px;
            text-align: center;
        }

        .hero h1 {
            font-size: 3em;
            margin-bottom: 20px;
            animation: fadeInUp 0.8s ease;
        }

        .hero p {
            font-size: 1.3em;
            margin-bottom: 30px;
            animation: fadeInUp 1s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-hero {
            display: inline-block;
            background: white;
            color: #667eea;
            padding: 15px 40px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1em;
            transition: all 0.3s;
            animation: fadeInUp 1.2s ease;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        /* Featured Section */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        .section-title {
            text-align: center;
            font-size: 2.5em;
            color: #333;
            margin-bottom: 50px;
        }

        .vehicles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
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

        .vehicle-info h3 {
            color: #333;
            font-size: 1.5em;
            margin-bottom: 10px;
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

        .vehicle-info p {
            color: #666;
            margin: 10px 0;
        }

        .price {
            font-size: 1.5em;
            color: #667eea;
            font-weight: bold;
            margin: 15px 0;
        }

        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: 500;
        }

        .status.available {
            background: #d4edda;
            color: #155724;
        }

        .view-all {
            text-align: center;
            margin-top: 40px;
        }

        .btn-view-all {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-view-all:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        /* Features Section */
        .features {
            background: white;
            padding: 60px 20px;
            margin-top: 40px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-item {
            text-align: center;
            padding: 30px;
        }

        .feature-icon {
            font-size: 3em;
            margin-bottom: 20px;
        }

        .feature-item h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .feature-item p {
            color: #666;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <div class="logo">🏍️ MotoRide</div>
            <ul class="nav-links">
                <li><a href="user_home.php" class="active">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            <div class="user-info">
                <span>👋 Hi, <?php echo htmlspecialchars($user_name); ?></span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Welcome to MotoRide!</h1>
        <p>Your perfect ride is just a click away</p>
        <a href="shop.php" class="btn-hero">Browse Vehicles →</a>
    </div>

    <!-- Featured Vehicles -->
    <div class="container">
        <h2 class="section-title">Featured Vehicles</h2>
        <div class="vehicles-grid">
            <?php foreach ($featured_vehicles as $vehicle): ?>
                <div class="vehicle-card">
                    <img src="<?php echo htmlspecialchars($vehicle['image']); ?>" alt="<?php echo htmlspecialchars($vehicle['name']); ?>">
                    <div class="vehicle-info">
                        <span class="vehicle-type"><?php echo ucfirst($vehicle['type']); ?></span>
                        <h3><?php echo htmlspecialchars($vehicle['name']); ?></h3>
                        <p><?php echo htmlspecialchars($vehicle['description']); ?></p>
                        <div class="price">₱<?php echo number_format($vehicle['price_per_day'], 2); ?>/day</div>
                        <span class="status available">✓ Available</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="view-all">
            <a href="shop.php" class="btn-view-all">View All Vehicles</a>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features">
        <h2 class="section-title">Why Choose MotoRide?</h2>
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">🚗</div>
                <h3>Wide Selection</h3>
                <p>Choose from our extensive fleet of motorcycles and cars</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">💰</div>
                <h3>Best Prices</h3>
                <p>Competitive rates with no hidden charges</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">⚡</div>
                <h3>Quick Booking</h3>
                <p>Book your vehicle in just a few clicks</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🛡️</div>
                <h3>Safe & Reliable</h3>
                <p>All vehicles are well-maintained and insured</p>
            </div>
        </div>
    </div>
</body>

</html>
>>>>>>> origin/main
