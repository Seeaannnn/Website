<?php
session_start(); 

$errors = array(); 
$messageSent = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "database.php"; 

    // Assuming your form fields are 'name', 'email', and 'message'
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    // Validate the form fields
    if (empty($name) || empty($email) || empty($message)) {
        array_push($errors, "All fields are required");
    }

    // Additional form validation and sanitation as needed

    if (empty($errors)) {
        // Proceed with database insertion
        $sql = "INSERT INTO contact_details_db (name, email, message) VALUES (?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message);
            mysqli_stmt_execute($stmt);

            // Assuming the form processing is successful
            $messageSent = true;
        } else {
            array_push($errors, "Error in preparing SQL statement for form processing");
        }
    }
}

// Close the database connection if it's open
if (isset($conn)) {
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add your head content here -->
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #081b29;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .message-box {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #a6bbcc;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 500px;
        text-align: center;
    }

    .message-box p {
        margin-bottom: 20px;
    }

    .message-btns {
        display: flex;
        justify-content: space-around;
    }

    .message-btns button {
        color: black; /* Set the color to black */
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .message-btns button:hover {
        background-color: aquamarine;
    }
</style>
</head>

<body>
    <?php
    if ($messageSent) {
        echo "<div class='message-box'>";
        echo "    <p>Message sent successfully!</p>";
        echo "    <div class='message-btns'>";
        echo "        <button onclick='redirectToHome()'>Back to Home</button>";
        echo "        <button onclick='sendAnotherMessage()'>Send Another Message</button>";
        echo "    </div>";
        echo "</div>";
    }
    ?>

    <script>
        function redirectToHome() {
            window.location.replace('index.html'); // Change this to the correct path
        }

        function sendAnotherMessage() {
            window.location.replace('contact.html'); // Change this to the correct path
        }

        // Show the message box if messageSent is true
        <?php
        if ($messageSent) {
            echo "document.querySelector('.message-box').style.display = 'block';";
        }
        ?>
    </script>
</body>

</html>
