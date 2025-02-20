<?php
	session_start();
	include 'db_connection.php';

    $_SESSION['user_email'] = '';
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = trim($_POST['name']);
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);

		// Check credentials
		$query = "SELECT * FROM users WHERE user_email = ?";
		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $email);
		$stmt->execute([$email]);
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();

		if ( $user ) { 
			if ( ($name == $user['user_name']) && ($email == $user['user_email']) && password_verify($password, $user['user_password']) ) {
				$_SESSION['user_email'] = $user['user_email'];
				echo "<script>alert('Login successful!'); window.location.href='homePage.php';</script>";
			} else {
				echo "<script>alert('Invalid credentials!');</script>";
			}
		}
		$stmt->close();
	}
	$db->close();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pawsitive Connection</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #ffcc00, #ff9900); /* Yellowish orange gradient */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }
        .welcome-banner {
            text-align: center;
            padding: 20px;
            background: #ffcc00; /* Yellowish */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 400px;
            margin-bottom: 20px;
        }
        .welcome-banner h1 {
            margin: 0;
            font-size: 24px;
        }
        .welcome-banner p {
            margin: 10px 0;
            font-size: 16px;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 12px;
            margin-bottom: 15px;
            border: 2px solid #ff9900; /* Darker yellow-orange */
            border-radius: 5px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #ffcc00; /* Lighter yellow */
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #ff9900; /* Yellow-orange */
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #ffcc00; /* Lighter yellow */
        }
        p {
            margin-top: 15px;
        }
        a {
            color: #ff9900; /* Yellow-orange */
            text-decoration: none;
            transition: color 0.3s;
        }
        a:hover {
            color: #ffcc00; /* Lighter yellow */
        }
    </style>
</head>
<body>
    <div class="welcome-banner">
        <h1>Welcome to Pawsitive Connection!</h1>
        <p>Find your perfect pet today!</p>
    </div>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="">
			<label>Name:</label>
            <input type="text" name="name" required="required">
            <label>Email:</label>
            <input type="email" name="email" required="required">
            <label>Password:</label>
            <input type="password" name="password" required="required">
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="registrationPage.php">Register here</a></p>
    </div>
</body>
</html>