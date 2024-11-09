<?php 


if ($_SESSION['user']['acctype'] !== 'superadmin') {
    $_SESSION['warning'] = "You do not have the privilege to enter this section.";
    header("Location: index.php"); // Redirect to the dashboard or another page
    exit();
}


if (isset($_POST['usead'])) {

    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $name = validate ($_POST['name']);
    $username = validate($_POST['username']);
    $email = validate($_POST['email']);
    $contact = validate($_POST['contact']);
    $password = validate($_POST['password']);
    $bio = validate($_POST['bio']);


    if (empty($name) || empty($username) || empty($email) || empty($contact) || empty($password)) {
        $errorMessage = "All fields are required";
        echo $errorMessage;
        exit;
    }

    $sql = "INSERT INTO users (name, username, email, contact, password) VALUES ('$name', '$username', '$email', '$contact', '$password')";

    if ($conn->query($sql) === TRUE) {
        $successMessage = "User added successfully";
        echo $successMessage;
    } else {
        $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
        echo $errorMessage;
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>