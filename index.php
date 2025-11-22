<!-- ============================================
STEP 3: INDEX.PHP - Landing Page
Save as: index.php
============================================ -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MotoRide - Choose Your Portal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            text-align: center;
            animation: fadeIn 0.8s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            font-size: 3.5em;
            margin-bottom: 10px;
        }

        h1 {
            color: white;
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .tagline {
            color: #f0f0f0;
            font-size: 1.2em;
            margin-bottom: 50px;
        }

        .portal-selection {
            display: flex;
            gap: 40px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .portal-card {
            background: white;
            border-radius: 20px;
            padding: 40px 50px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
            min-width: 280px;
        }

        .portal-card:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
        }

        .portal-card .icon {
            font-size: 5em;
            margin-bottom: 20px;
            display: block;
        }

        .portal-card h2 {
            font-size: 2em;
            margin-bottom: 10px;
            color: #333;
        }

        .portal-card p {
            color: #666;
            font-size: 1.1em;
        }

        .user-card {
            border-top: 5px solid #667eea;
        }

        .admin-card {
            border-top: 5px solid #f093fb;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">🏍️🚗</div>
        <h1>Welcome to MotoRide</h1>
        <p class="tagline">Your Premium Vehicle Rental Service</p>

        <div class="portal-selection">
            <a href="user_login.php" class="portal-card user-card">
                <span class="icon">👤</span>
                <h2>User Portal</h2>
                <p>Browse & Rent Vehicles</p>
            </a>

            <a href="admin_login.php" class="portal-card admin-card">
                <span class="icon">⚙️</span>
                <h2>Admin Portal</h2>
                <p>Manage Your Fleet</p>
            </a>
        </div>
    </div>
</body>

</html>
