<?php
	session_start();
	include 'db_connection.php';

	$search = isset($_GET['search']) ? trim($_GET['search']) : '';
	$query = "SELECT * FROM pets WHERE pet_adoption_status = 'available'";
	$params = [];
	$featuredPets = [];

	if (!empty($search)) {
		$query .= " AND (pet_name LIKE ? OR pet_breed LIKE ? OR pet_species LIKE ? OR pet_age LIKE ?)";
		$searchTerm = "%$search%";
		$params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
	}

	$stmt = $db->prepare($query);
	if (!empty($params)) {
		$stmt->bind_param("ssss", ...$params);
	}
	$stmt->execute();
	$result = $stmt->get_result();
	$pets = $result->fetch_all(MYSQLI_ASSOC);
	$stmt->close();
	$db->close();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Pawsitive Connection - Home</title>
		<style>
			body {
				font-family: 'Arial', sans-serif;
				margin: 0;
				padding: 0;
				background-color: #f0f0f0;
				background-image: url('Images/paw-pattern.png');
				background-repeat: repeat;
			}
			header {
				background: linear-gradient(90deg, #ffcc00, #ff9900);
				color: #333;
				padding: 20px;
				text-align: center;
				position: relative;
				border-bottom: 5px solid #333;
				transition: background 0.3s ease;
				overflow: hidden;
			}
			header:hover {
				background: linear-gradient(90deg, #ff9900, #ffcc00);
			}
			.nav-links {
				margin-top: 10px;
			}
			.nav-links a {
				margin: 0 15px;
				color: #333;
				text-decoration: none;
				font-weight: bold;
			}
			.nav-links a:hover {
				color: #ff9900;
			}
			.contact-info {
				margin-top: 10px;
				font-size: 14px;
				color: #333;
			}
			.search-bar {
				margin-top: 10px;
			}
			.search-bar input[type="text"] {
				padding: 10px;
				width: 300px;
				border: 1px solid #ccc;
				border-radius: 25px;
				outline: none;
				transition: border 0.3s;
			}
			.search-bar input[type="text"]:focus {
				border: 1px solid #ff9900;
			}
			.search-bar button {
				padding: 10px 15px;
				background-color: #333;
				color: #fff;
				border: none;
				border-radius: 25px;
				cursor: pointer;
				transition: background 0.3s;
			}
			.search-bar button:hover {
				background-color: #ff9900;
			}
			.login-button {
				margin-top: 10px;
				padding: 10px 15px;
				background-color: #333;
				color: #fff;
				border: none;
				border-radius: 25px;
				cursor: pointer;
				transition: background 0.3s;
				text-decoration: none;
				display: inline-block;
			}
			.login-button:hover {
				background-color: #ff9900;
			}
			.walking-dog {
				position: absolute;
				width: 100px;
				animation: walk-dog 10s linear infinite;
				bottom: 70px;
			}
			.walking-cat {
				position: absolute;
				width: 100px;
				animation: walk-cat 10s linear infinite;
				bottom: 10px;
			}
			@keyframes walk-dog {
				0% { left: -150px; }
				100% { left: 100%; }
			}
			@keyframes walk-cat {
				0% { left: -150px; }
				100% { left: 100%; }
			}
			.available-pets {
				padding: 20px;
				text-align: center;
				margin-top: 20px;
			}
			.pet-cards {
				display: flex;
				justify-content: center;
				flex-wrap: wrap;
				gap: 20px;
			}
			.pet-card {
				background: white;
				border-radius: 10px;
				box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
				padding: 15px;
				width: 200px;
				text-align: left;
				transition: transform 0.2s, box-shadow 0.2s;
			}
			.pet-card:hover {
				transform: translateY(-5px);
				box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
			}
			.pet-card img {
				width: 100%;
				border-radius: 10px;
			}
			.pet-card h3 {
				margin: 10px 0;
				color: #ff9900;
			}
			.pet-card p {
				margin: 5px 0;
			}
			.pet-card a {
				display: inline-block;
				margin-top: 10px;
				padding: 10px;
				background-color: #ffcc00;
				color: #333;
				text-decoration: none;
				border-radius: 5px;
				text-align: center;
				transition: background 0.3s;
			}
			.pet-card a:hover {
				background-color: #ffa500;
			}
			footer {
				background: #333;
				color: #fff;
				text-align: center;
				padding: 10px;
				position: relative;
				bottom: 0;
				width: 100%;
			}
			.footer-links {
				margin-top: 10px;
			}
			.footer-links a {
				color: #ffcc00;
				text-decoration: none;
				margin: 0 10px;
			}
			.footer-links a:hover {
				text-decoration: underline;
			}
		</style>
	</head>
	<body>
		<header>
			<h1>Welcome to Pawsitive Connection!</h1>
			<img src="Images/dog-running.gif" alt="Walking Dog" class="walking-dog">
			<p>Find your perfect pet today!</p>
			<div class="nav-links">
				<a href="about_us.php">About Us</a>
				<a href="privacy_policy.php">Privacy Policy</a>
				<a href="terms_of_service.php">Terms of Service</a>
			</div>
			<div class="contact-info">
				<p>Contact us: <strong>Phone: (123) 456-7890</strong> | <strong>Email: info@pawsitiveconnection.com</strong></p>
			</div>
			<div class="search-bar">
				<form action="" method="GET">
					<input type="text" name="search" placeholder="Search for pets...">
					<button type="submit">Search</button>
				</form>
			</div>
			<?php if ( empty($_SESSION['user_email']) ) {?>
				<a href="loginPage.php" class="login-button">Login</a>
			<?php } else {?>
				<a href="loginPage.php" class="login-button">Logout</a>
			<?php }?>
		</header>

		<main>
			<section class="available-pets">
				<h2>Available Pets</h2>
				<div class="pet-cards">
					<?php foreach ($pets as $pet): ?>
						<div class="pet-card">
							<img src="<?= $pet['pet_image'] ?>" alt="<?= $pet['pet_name'] ?>">
							<h3><?= $pet['pet_name'] ?></h3>
							<p>Species: <?= $pet['pet_species'] ?></p>
							<p>Breed: <?= $pet['pet_breed'] ?></p>
							<p>Age: <?= $pet['pet_age'] ?></p>
							<a href="pet_details.php?petId=<?= urlencode($pet['petId']) ?>">View Details</a>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		</main>

		<footer>
			<img src="Images/cat-walking.gif" alt="Walking Cat" class="walking-cat">
			<div class="footer-links">
				<a href="about_us.php">About Us</a>
				<a href="privacy_policy.php">Privacy Policy</a>
				<a href="terms_of_service.php">Terms of Service</a>
			</div>
			<p>&copy; 2025 Pawsitive Connection. All rights reserved.</p>
		</footer>
	</body>
</html>