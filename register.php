<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link href="./build/css/demo.css" rel="stylesheet">
	<link href="./build/css/intlTelInput.css" rel="stylesheet">
	<link rel="stylesheet" href="style8.css">
</head>
<body>
<div class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <div class="form-holder col-md-10">
                <div class="form-content">
                    <div class="form-items">
                        <h3>Create Account</h3>
                        <p>Fill in the data below.</p>
                        <form action="register.php" method="post" class="requires-validation">
							<?php
								require_once "database.php";

								if (isset($_POST["submit"])) {
									$firstname = $_POST["firstname"];
									$lastname = $_POST["lastname"];
									$email = $_POST["email"];
									$lot_blk = $_POST["lot_blk"];
									$street = $_POST["street"];
									$phase_subdivision = $_POST["phase_subdivision"];
									$barangay = $_POST["barangay"];
									$province = $_POST["province"];
									$city_municipality = $_POST["city_municipality"];
									$country = $_POST["country"];
									$phone = $_POST["phone"];
									$password = $_POST["password"];
									$repeatpassword = $_POST["repeatpassword"];

									$passwordHash = password_hash($password, PASSWORD_DEFAULT);

									$errors = array();

									if (empty($firstname) || empty($lastname) || empty($email) || empty($lot_blk) || empty($street) || empty($phase_subdivision) || empty($barangay) || empty($province) || empty($city_municipality) || empty($country) || empty($phone) || empty($password) || empty($repeatpassword)) {
										array_push($errors, "All fields are required");
									}
									if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
										array_push($errors, "Email is not valid");
									}
									if (strlen($password) < 8 ) {
										array_push($errors, "Password must be at least 8 characters long");
									}
									if ($password !== $repeatpassword) {
										array_push($errors, "Password does not match");
									}

									$sqlCheckEmail = "SELECT * FROM account_details_tbl WHERE email = ?";
									$stmtCheckEmail = mysqli_stmt_init($conn);

									if (mysqli_stmt_prepare($stmtCheckEmail, $sqlCheckEmail)) {
										mysqli_stmt_bind_param($stmtCheckEmail, "s", $email);
										mysqli_stmt_execute($stmtCheckEmail);
										$resultCheckEmail = mysqli_stmt_get_result($stmtCheckEmail);

										if (mysqli_num_rows($resultCheckEmail) > 0) {
											array_push($errors, "Email already exists!");
										}
									} else {
										die("Error in preparing SQL statement to check email");
									}

									if (count($errors) > 0) {
										foreach ($errors as $error) {
											echo "<div class='alert alert-danger'>$error</div>";
										}
									} else {
										$sqlInsertInfo = "INSERT INTO account_details_tbl (firstname, lastname, blk_lot, street, phase_subdivision, barangay, city_municipality, province, country, contact_no, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
										$stmtInsertInfo = mysqli_stmt_init($conn);

										if (mysqli_stmt_prepare($stmtInsertInfo, $sqlInsertInfo)) {
											mysqli_stmt_bind_param($stmtInsertInfo, "ssssssssssss", $firstname, $lastname, $lot_blk, $street, $phase_subdivision, $barangay, $city_municipality, $province, $country, $phone, $email, $passwordHash);
											mysqli_stmt_execute($stmtInsertInfo);
											echo "<div class='alert alert-success'>You are registered successfully.</div>";

											// Get the AccountID of the inserted user
											$accountID = mysqli_insert_id($conn);
										} else {
											die("Error in preparing SQL statement to insert user account");
										}
									}

								}
							?>

							<div class="row">
								<!-- First Name and Last Name -->
								<div class="col-md-6">
									<div class="form-group">
										<input type="text" name="firstname" class="form-control" placeholder="First Name:" required/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input type="text" name="lastname" class="form-control" placeholder="Last Name:" required/>
									</div>
								</div>
							</div>

							<!-- Email -->
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<input type="text" name="email" class="form-control" placeholder="Email:" required/>
									</div>
								</div>
							</div>

							<!-- Password and Confirm Password -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<input type="password" name="password" class="form-control" placeholder="Password:" required/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input type="password" name="repeatpassword" class="form-control" placeholder="Confirm Password:" required/>
									</div>
								</div>
							</div>

							<!-- Lot/Blk and Street -->
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<input type="text" name="lot_blk" class="form-control" placeholder="Blk/Lot:" required/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input type="text" name="street" class="form-control" placeholder="Street:" required/>
									</div>
								</div>
							</div>

							<!-- Phase/Subdivision -->
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<input type="text" name="phase_subdivision" class="form-control" placeholder="Phase/Subdivision:" required/>
									</div>
								</div>
							</div>

							<!-- Province -->
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
									<select id="provinces" name="province" class="form-control" required>
										<option disabled selected>Select Province</option>
									</select>
									</div>
								</div>
							</div>

							<!-- City/Municipality -->
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
									<select id="cities" name="city_municipality" class="form-control" required>
										<option disabled selected>Select City/Municipality</option>
									</select>
									</div>
								</div>
							</div>

							<!-- Barangay -->
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
									<select id="barangay" name="barangay" class="form-control" required>
										<option disabled selected>Select Barangay</option>
									</select>
									</div>
								</div>
							</div>

							<!-- Country -->
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
									<select id="countries" name="country" class="form-control" name="countries" required>
										<option disabled selected>Select Country</option>
									</select>
									</div>
								</div>
							</div>

							<!-- Phone# -->
							<div class="form-group">
								<input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required maxlength="20">
							</div>

                            <div class="form-button mt-3">
                                <button id="submit" type="submit" class="btn btn-primary" value="Register" name="submit">Register</button>
                            </div>

							<div class="already-signed-up">
								<p>Already have an account? <a href="login.php">Login Here</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<script src="./build/js/intlTelInput.js"></script>
		<script src="country.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
		<script src="province_barangay_city.js"></script>
		<script>
			document.addEventListener("DOMContentLoaded", function() {
				var input = document.querySelector("#phone");
				var iti = window.intlTelInput(input, {
					utilsScript: "./build/js/utils.js", 
					separateDialCode: true,         
				});

				// Event listener for handling changes in the input
				input.addEventListener("change", function() {
					// Check if the input value already contains the dial code
					if (!input.value.startsWith('+')) {
						var selectedCountryData = iti.getSelectedCountryData();
						var countryCode = selectedCountryData.dialCode;

						// Remove leading zeros
						input.value = input.value.replace(/^0+/, '');

						// Add the dial code only if it's not already present
						input.value = '+' + countryCode + input.value;
					}
				});
			});
	</script>
</body>
</html>