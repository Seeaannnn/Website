<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style_login.css">
</head>
<body>
    <div class="container">
        <form action="login.php" method="post">
        <?php
session_start(); 

$errors = array(); 

if (isset($_POST["submit"]) && !empty($_POST["email"]) && !empty($_POST["password"])) { 
    require_once "database.php"; 

    $email = $_POST["email"];
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        array_push($errors, "Email and Password are required");
    }


    $sql = "SELECT * FROM account_details_tbl WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row["password"])) {
                $_SESSION["email"] = $email;
                header("location: contact.html"); 
                die();
            } else {
                echo "<div class='alert alert-danger'>Invalid Email or Password</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Invalid Email or Password</div>";
        }
    } else {
        echo "<div class='alert alert-danger'> Error in preparing SQL statement to check email</div>";
    }
}
?>

            <div class="form-group">
                <input type="text" placeholder="Enter Email:" name="email" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter Password:" name="password" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Login" name="submit" class="btn btn-primary">
            </div>
        </form>

        <div><p>Register Now! <a href="register.php">Register Here</a></p></div>
        <div><p>Back to Home: <a href="index.html">Click Here</a></p></div>
    </div>
</body>
</html>