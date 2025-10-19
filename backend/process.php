<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Get form data
	$name = htmlspecialchars(trim($_POST['name']));
	$email = htmlspecialchars(trim($_POST['email']));
	$subject = htmlspecialchars(trim($_POST['subject']));
	$message = htmlspecialchars(trim($_POST['message']));

	// Validation
	$errors = [];

	if (empty($name)) {
		$errors[] = "Name is required";
	}

	if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors[] = "Valid email is required";
	}

	if (empty($subject)) {
		$errors[] = "Subject is required";
	}

	if (empty($message)) {
		$errors[] = "Message is required";
	}

	// If no errors, save to file
	if (empty($errors)) {
		// Create data array
		$submission = [
			'timestamp' => date('Y-m-d H:i:s'),
			'name' => $name,
			'email' => $email,
			'subject' => $subject,
			'message' => $message,
			'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
		];

		// Convert to JSON
		$data = json_encode($submission) . PHP_EOL;

		// Save to file
		$filename = 'submissions.txt';
		
		if (file_put_contents($filename, $data, FILE_APPEND | LOCK_EX)) {
			// Success - redirect to success page
			header("Location: index.php?success=1");
			exit();
		} else {
			// Error saving file
			header("Location: index.php?error=1");
			exit();
		}
	} else {
		// Validation errors
		header("Location: index.php?error=1");
		exit();
	}
} else {
	// If not POST request, redirect to form
	header("Location: index.php");
	exit();
}
?>
