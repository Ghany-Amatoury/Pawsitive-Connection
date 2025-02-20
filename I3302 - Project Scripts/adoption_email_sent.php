<?php
	session_start();
	include 'db_connection.php';

	// Check if user is logged in
	if (!isset($_SESSION['user_email']) || ($_SESSION['user_email'] == '')) {
		header("Location: login.php");
		exit();
	}

	// Check if petId is provided
	if (!isset($_GET['petId']) || !is_numeric($_GET['petId'])) {
		die("Invalid pet ID.");
	}

	$pet_id = intval($_GET['petId']);

	// Mark pet status as pending
	$stmt = $db->prepare("UPDATE pets SET pet_adoption_status = 'pending' WHERE petId = ?");
	$stmt->bind_param("i", $pet_id);
	$stmt->execute();
	$stmt->close();

	// Retrieve pet and shelter details
	$stmt = $db->prepare("SELECT p.*, s.shelter_name FROM pets p JOIN shelters s ON p.shelter_id = s.shelterId WHERE p.petId = ?");
	$stmt->bind_param("i", $pet_id);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows === 0) {
		die("Pet not found.");
	}

	$pet = $result->fetch_assoc();
	$stmt->close();
	$db->close();

	$message = $_SESSION['message'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption Request Sent</title>
    <style>
        body { 
			font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #ffcc00, #ff9900); /* Yellowish orange gradient */
            margin: 0;
            padding: 20px;
            color: #333;
		}
        .container { 
			max-width: 600px; 
			margin: auto; 
			background: #fff; 
			padding: 20px; 
			border-radius: 10px; 
			box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
		}
        h1 { 
			color: #ff9900; 
		}
        p { 
			font-size: 16px;
		}
        .button { 
			background: #ff9900; 
			color: white; 
			padding: 10px 20px; 
			text-decoration: none; 
			border-radius: 5px; 
			display: inline-block; 
			margin-top: 20px; 
		}
        .button:hover { 
			background: #ffcc00; 
		}
    </style>
</head>
<body>
    <div class="container">
        <h1>Adoption Request Sent Successfully!</h1>
        <p>Your adoption request for <strong><?php echo htmlspecialchars($pet['pet_name']); ?></strong> has been sent to <strong><?php echo htmlspecialchars($pet['shelter_name']); ?></strong>.</p>
        <p>Here is a copy of the email sent:</p><br>
        <pre><?php echo nl2br(htmlspecialchars($message)); ?></pre>
        <a href="homePage.php" class="button">Back to Home Page</a>
    </div>
</body>
</html>
