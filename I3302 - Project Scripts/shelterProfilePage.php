<?php
	include 'db_connection.php';

	// Check if shelter ID is provided
	if (!isset($_GET['shelter_id']) || !is_numeric($_GET['shelter_id'])) {
		die("Invalid shelter ID.");
	}

	$shelter_id = intval($_GET['shelter_id']);

	// Fetch shelter details
	$query = "SELECT * FROM shelters WHERE shelterId = ?";
	$stmt = $db->prepare($query);
	$stmt->bind_param("i", $shelter_id);
	$stmt->execute();
	$res = $stmt->get_result();
	$shelter = $res->fetch_assoc();
	$stmt->close();
	
	// If no shelter found, show error message
	if (!$shelter) {
		die("<h2>Shelter not found.</h2>");
	}

	// Fetch pets from this shelter
	$query = "SELECT * FROM pets WHERE shelter_id = ? AND pet_adoption_status = 'available'";
	$stmt = $db->prepare($query);
	$stmt->bind_param("i", $shelter_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$pets = [];
	while ($row = $result->fetch_assoc()) {
		$pets[] = $row;
	}
	$stmt->free_result();
	
	$db->close();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Shelter Profile</title>
		<style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #ffcc00, #ff9900); /* Yellowish orange gradient */
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            max-width: 1000px;
            margin: auto;
            animation: fadeIn 1s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        h1, h2, p {
            font-weight: bold;
        }
        h1 {
            color: #ff9900; 
            text-align: center;
        }
        h2 {
            margin-top: 30px;
            color: #ff9900; 
            text-align: center;
        }
        p {
            margin: 10px 0;
            text-align: center;
        }
        ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        li {
            background: #ffcc00; 
            border-radius: 10px;
            margin: 10px;
            padding: 15px;
            transition: background-color 0.3s, transform 0.3s;
            width: 200px; 
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        li:hover {
            background: #ff9900; 
            color: white;
            transform: scale(1.05); 
        }
        .no-pets {
            background: #ffcc00;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
		footer {
            margin-top: 20px;
            padding: 10px;
            text-align: center;
            width: 100%;
            background: #333;
            color: white;
        }
        a {
            text-decoration: none;
            color: black;
        }
    </style>
	</head>
	<body>
		<div class="container">
			<h1><?php echo htmlspecialchars($shelter['shelter_name']); ?></h1>
			<p>Email: <?php echo htmlspecialchars($shelter['shelter_email']); ?></p>
			<p>Phone: <?php echo htmlspecialchars($shelter['shelter_phone']); ?></p>
			<p>Address: <?php echo htmlspecialchars($shelter['shelter_address']); ?></p>
			
			<h2>Available Pets</h2>
			<?php if (count($pets) > 0): ?>
				<ul>
					<?php foreach ($pets as $pet): ?>
                        <a href="pet_details.php?petId=<?= urlencode($pet['petId']) ?>">
                            <li>
                                <strong><?php echo htmlspecialchars($pet['pet_name']); ?></strong> -
                                <?php echo htmlspecialchars($pet['pet_species']) . " | " . htmlspecialchars($pet['pet_breed']) . " | " . htmlspecialchars($pet['pet_age']) . " years old"; ?>
                            </li>
                        </a>
					<?php endforeach; ?>
				</ul>
			<?php else: ?>
				<p>No pets available for adoption at this time.</p>
			<?php endif; ?>
		</div>
		
		<footer>
        <p>&copy; 2025 Pawsitive Connection. All rights reserved.</p>
		</footer>
	</body>
</html>