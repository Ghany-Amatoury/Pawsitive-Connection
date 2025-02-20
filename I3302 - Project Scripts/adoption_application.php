<?php
	session_start();
	include 'db_connection.php'; // Database connection

	// Check if user is logged in
	if (!isset($_SESSION['user_email']) || ($_SESSION['user_email'] == '') ) {
		header("Location: loginPage.php");
		exit();
	}

	// Check if pet ID is provided
	if (!isset($_GET['petId']) || !is_numeric($_GET['petId'])) {
		die("Invalid pet ID.");
	}

	$pet_id = intval($_GET['petId']);
	$email = $_SESSION['user_email'];

    // Retrieve user details
	$stmt = $db->prepare("SELECT userId, user_name, user_email FROM users WHERE user_email = ?");
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows === 0) {
		die("User not found.");
	}

	$user = $result->fetch_assoc();
	$stmt->close();

	// Retrieve pet details
	$stmt = $db->prepare("SELECT p.*, s.shelter_name, s.shelter_email FROM pets p JOIN shelters s ON p.shelter_id = s.shelterId WHERE p.petId = ?");
	$stmt->bind_param("i", $pet_id);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows === 0) {
		die("Pet not found.");
	}

	$pet = $result->fetch_assoc();
	$stmt->close();

	// Handle form submission
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$application_details = trim($_POST['application_details']);

		$stmt = $db->prepare("INSERT INTO applications (user_id, pet_id, application_details, application_date) VALUES (?, ?, ?, NOW())");
		$stmt->bind_param("iis", $user['userId'], $pet_id, $application_details);

		if ($stmt->execute()) {
			// Send email to shelter
			$to = $pet['shelter_email'];
			$subject = "New Adoption Request for " . $pet['pet_name'];
			$message = "Hello " . $pet['shelter_name'] . ",\n\n";
			$message .= "A new adoption request has been submitted for " . $pet['pet_name'] . ".\n";
			$message .= "Applicant Details:\n";
			$message .= "User name: " . $user['user_name'] . "\n";
			$message .= "User email: " . $user['user_email'] . "\n";
			$message .= "Application Message: " . $application_details . "\n\n";
			$message .= "Please review the application and respond accordingly.\n\n";
			$message .= "Best Regards,\nPawsitive Connection Team";
			$_SESSION['message'] = $message;
			
			$headers = "From: no-reply@pawsitiveconnection.com";
			mail($to, $subject, $message, $headers);
		
			echo "<script>alert('Adoption request submitted successfully!'); window.location.href='adoption_email_sent.php?petId={$pet_id}';</script>";
			exit();
		} else {
			echo "Error submitting application: " . $db->error;
		}

		$stmt->close();
	}
	$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption Application</title>
    <style>
        /* Your existing styles here */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        header {
            background: #ffcc00;
            width: 100%;
            padding: 20px;
            text-align: center;
        }
        h1 {
            margin: 0;
            font-size: 2.5em;
        }
        main {
            width: 90%;
            max-width: 600px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }
        .error-message, .success-message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input, textarea {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #ff9900;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
        }
        .pet-image {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
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
    <header>
        <h1>Adoption Application</h1>
    </header>

    <main>
        <img src="<?= htmlspecialchars($pet['pet_image']); ?>" alt="<?= htmlspecialchars($pet['pet_name']); ?>" class="pet-image">

        <h2>Applying for: <?= htmlspecialchars($pet['pet_name']); ?></h2>
        <form action="" method="POST">

            <label for="application_details">Why do you want to adopt this pet?</label>
            <textarea name="application_details" rows="4" required></textarea>

            <button type="submit">Submit Application</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Pawsitive Connection. All rights reserved.</p>
    </footer>
</body>
</html>