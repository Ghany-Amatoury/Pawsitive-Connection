<?php
	session_start();
	include 'db_connection.php';
	
	$_SESSION['user_email'] = '';
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = trim($_POST['name']);
		$email = trim($_POST['email']);
		$phone = trim($_POST['phone']);
		$address = trim($_POST['address']);
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

		// Check if user already exists
		$query = "SELECT * FROM users WHERE user_email = ?";
		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->store_result();
		if ( $stmt->num_rows > 0 ) {
			echo "<script>alert('Account already exists! Please login.'); window.location.href='loginPage.php';</script>";
			exit;
		}
		$stmt->free_result();

		// Insert new user
		$query = "INSERT INTO users (user_name, user_email, user_phone, user_address, user_password) VALUES (?, ?, ?, ?, ?)";
		$stmt = $db->prepare($query);
		$stmt->bind_param("sssss", $name, $email, $phone, $address, $password);
		$stmt->execute();

		$_SESSION['user_email'] = $email;
		echo "<script>alert('Registration successful!'); window.location.href='homepage.php';</script>";
		
		$stmt->close();
	}
	$db->close();
?>

<!DOCTYPE htm>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Register - Pawsitive Connection</title>
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
			.register-container {
				background: white;
				padding: 30px;
				border-radius: 10px;
				box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
				width: 400px;
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
			input[type="password"],
			select {
				width: 100%;
				padding: 12px;
				margin-bottom: 15px;
				border: 2px solid #ff9900; /* Darker yellow-orange */
				border-radius: 5px;
				transition: border-color 0.3s;
			}
			input:focus,
			select:focus {
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
		<div class="register-container">
			<h2>Register</h2>
			
			<form action="" method="POST">
				<label>Name: </label>
				<input type="text" name="name" required="required"><br><br>
				
				<label>Email: </label>
				<input type="email" name="email" required="required"><br><br>

				<label>Password: </label>
				<input type="password" name="password" required="required"><br><br>
				
				<label>Phone Number: </label>
				<input type="text" name="phone" required="required"><br><br>
				
				<label>Address: </label>
				<input type="text" name="address" required="required"><br><br>
				
				<button type="submit">Register</button>
			</form>
			
			<p>Already have an account? <a href="loginPage.php">Login here</a>.</p>
		</div>
	</body>
</html>