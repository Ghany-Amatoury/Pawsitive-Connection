<?php
	session_start();
	include 'db_connection.php'; // Database connection

	// Check if user is logged in
	if ( !isset($_SESSION['user_email']) || ($_SESSION['user_email'] == '') ) {
		header("Location: loginPage.php");
		exit();
	}

	// Check if pet ID is provided
	if (!isset($_GET['petId']) || !is_numeric($_GET['petId'])) {
		die("Invalid pet ID.");
	}

	$pet_id = intval($_GET['petId']);

	// Retrieve pet details
	$stmt = $db->prepare("SELECT p.*, s.shelter_name FROM pets p JOIN shelters s ON p.shelter_id = s.shelterId WHERE p.petId = ?");
	$stmt->bind_param("i", $pet_id);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();

		// Set a cookie to remember the last viewed pet
		setcookie("last_viewed_pet", $pet_id, time() + 3600, "/");

		echo "<div class='container'>";
		echo "<h1>" . htmlspecialchars($row['pet_name']) . "</h1>";
		echo "<img src='" . htmlspecialchars($row['pet_image']) . "' alt='" . htmlspecialchars($row['pet_name']) . "' class='pet-image'><br>";
		echo "<p><strong>Species: </strong> " . htmlspecialchars($row['pet_species']) . "</p>";
		echo "<p><strong>Breed: </strong> " . htmlspecialchars($row['pet_breed']) . "</p>";
		echo "<p><strong>Age: </strong> " . htmlspecialchars($row['pet_age']) . "</p>";
		echo "<p><strong>Size: </strong> " . htmlspecialchars($row['pet_size']) . "</p>";
		echo "<p><strong>Gender: </strong> " . htmlspecialchars($row['pet_gender']) . "</p>";
		echo "<p><strong>Description: </strong> " . htmlspecialchars($row['pet_description']) . "</p>";
		echo "<p><strong>Health Details: </strong> " . htmlspecialchars($row['pet_health_details']) . "</p>";
		echo "<p><strong>Adoption Status: </strong> " . htmlspecialchars($row['pet_adoption_status']) . "</p>";
		echo "<p><strong>Shelter: </strong> " . htmlspecialchars($row['shelter_name']) . "</p>"; ?>
		<a href="shelterProfilePage.php?shelter_id=<?= urlencode($row['shelter_id']) ?>"><input type="submit" value="View Shelter Details"></a>
		<?php echo "<br>";

		echo "<br>";
	}

	$stmt->close();
	$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adopt a Pet</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #ffcc00, #ff9900); /* Yellowish orange gradient */
            margin: 0;
            padding: 20px;
            color: #333;
            animation: fadeIn 1s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            margin: auto;
            text-align: center;
            animation: slideIn 0.5s;
        }
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        h1, h2 {
            color: #ff9900; /* Darker yellow-orange */
            font-weight: bold;
            transition: color 0.3s;
        }
        h1:hover, h2:hover {
            color: #ffcc00; /* Lighter yellow on hover */
        }
        .pet-image {
            width: 300px;
            border-radius: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .pet-image:hover {
            transform: scale(1.05); /* Scale on hover */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        textarea {
            width: calc(100% - 20px);
            height: 100px;
            padding: 10px;
            border: 2px solid #ff9900;
            border-radius: 5px;
            resize: none;
            transition: border-color 0.3s;
        }
        textarea:focus {
            border-color: #ffcc00; /* Lighter yellow */
            outline: none;
        }
        input[type="submit"] {
            background-color: #ff9900;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #ffcc00; /* Lighter yellow */
            transform: translateY(-2px);
        }
        .success-message, .error-message {
            margin-top: 20px;
            font-weight: bold;
            transition: opacity 0.5s;
        }
        .success-message {
            color: green;
        }
        .error-message {
            color: red;
        }
		footer {
            margin-top: 20px;
            padding: 10px;
            text-align: center;
            width: 100%;
            background: #333;
            color: white;
        }
    </style>
</head>
<body>
		<a href="adoption_application.php?petId=<?= urlencode($row['petId']) ?>"><input type="submit" value="Apply for Adoption"></a>
		
		<footer>
			<p>&copy; 2025 Pawsitive Connection. All rights reserved.</p>
		</footer>
</body>
</html>