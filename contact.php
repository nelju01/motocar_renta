<!-- ============================================
STEP 8: CONTACT.PHP - Contact Page
Save as: contact.php
============================================ -->
<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - MotoRide</title>
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .page-header h1 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 15px;
        }

        .contact-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 40px;
        }

        .contact-info {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .contact-info h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 1.8em;
        }

        .info-item {
            display: flex;
            align-items: start;
            margin-bottom: 25px;
        }

        .info-icon {
            font-size: 2em;
            margin-right: 20px;
        }

        .info-text h3 {
            color: #333;
            margin-bottom: 5px;
        }

        .info-text p {
            color: #666;
            line-height: 1.6;
        }

        .map-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .map-container iframe {
            width: 100%;
            height: 400px;
            border: none;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .contact-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <nav>
        <div class="nav-container">
            <div class="logo">🏍️ MotoRide</div>
            <ul class="nav-links">
                <li><a href="user_home.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="contact.php" class="active">Contact</a></li>
            </ul>
            <div class="user-info">
                <span>👋 Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>📞 Contact Us</h1>
            <p>We're here to help! Reach out to us anytime</p>
        </div>

        <div class="contact-content">
            <div class="contact-info">
                <h2>Get In Touch</h2>

                <div class="info-item">
                    <div class="info-icon">📍</div>
                    <div class="info-text">
                        <h3>Address</h3>
                        <p>123 Rental Street, Quezon City<br>Metro Manila, Philippines 1100</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">📞</div>
                    <div class="info-text">
                        <h3>Phone</h3>
                        <p>+63 912 345 6789<br>+63 917 888 9999</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">✉️</div>
                    <div class="info-text">
                        <h3>Email</h3>
                        <p>info@motoride.com<br>support@motoride.com</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">⏰</div>
                    <div class="info-text">
                        <h3>Business Hours</h3>
                        <p>Monday - Friday: 8:00 AM - 6:00 PM<br>
                            Saturday: 9:00 AM - 5:00 PM<br>
                            Sunday: Closed</p>
                    </div>
                </div>
            </div>

            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d30903.13856788!2d121.0244!3d14.6760!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b7edfe47fb01%3A0xf31863e9c9b8c3e5!2sQuezon%20City%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1234567890" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</body>

</html>
