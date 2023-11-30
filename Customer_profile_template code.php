<!DOCTYPE html>
<html lang="en">
<?php
include("connection/dbconnect.php");  //include connection file
error_reporting(0);  // using to hide undefine undex errors
session_start(); //start temp session until logout/browser closed


?>
<?php
if (isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    echo $user_id;
    $new_password = $_POST['new_password'];

    $update_query = "UPDATE users SET Pass='$new_password' WHERE U_ID=$user_id";
    $update_result = mysqli_query($db, $update_query);

    if ($update_result) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating password: " . mysqli_error($db);
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Your Profile</title>
    <style>
        body {
            background-color: #00111c;

        }

        input {
            background-color: white;
            color: black;
            border: 0;
        }

        .navbar {
            background-color: #22404A;
        }

        .navbar,
        .nav-link,
        .navbar-brand {
            color: white;
        }

        .section1 {
            color: aliceblue;
        }

        .c1 {
            color: white;
        }

        .rounded-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        .form-group-2 {
            margin-left: 1.75in;
            text-align: left;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">

        <div class="container-fluid">

            <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="img/project logo.png" widtgh='250' height='40' alt=""> </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <!-- Empty div to push menu items to right -->
                <div class="me-auto"></div>

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php" style="color:white;font-style:italic;">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="available_movies.php" style="color:white;font-style:italic;">Movies</a>
                    </li>

                    <!-- Rest of items -->
                    <li class="nav-item">
                        <form class="d-flex" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                        <!-- Menu items -->
                    </li>
                    <?php
                    if (empty($_SESSION["user_id"])) {
                        echo '<li class="nav-item"><a href="login.php" class="nav-link active"style="color:white;font-style:italic;">Login</a> </li>
                      <li class="nav-item"><a href="registration.php" class="nav-link active"style="color:white;font-style:italic;">Signup</a> </li>';
                    } else {
                        echo '<li class="nav-item"><a href="Ticket_Purchase.php" class="nav-link active"style="color:white;font-style:italic;">Buy Ticket</a> </li>';
                        echo '<li class="nav-item"><a href="Customer_profile.php" class="nav-link active"style="color:white;font-style:italic;">Profile</a> </li>';
                        echo '<li class="nav-item"><a href="logout.php" class="nav-link active"style="color:white;font-style:italic;">Logout</a> </li>';
                    }
                    ?>
                </ul>


            </div>

        </div>

    </nav>
    <br /><br /><br />
    <?php
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users U inner join customer C on U.U_ID=C.Customer_ID where U.U_ID=$user_id";

    $result = mysqli_query($db, $sql);

    $user_data = mysqli_fetch_assoc($result);;
    ?>

    <div class="container text-center">
        <div class="row">
            <div class="col-md-12" style="background-color:#E4DCCF; opacity:1; color: black; font-family: Arial, Helvetica, sans-serif; font-size: medium;">
                <section>
                    <img src="img/blank-profile-picture.png" class="rounded-circle"><br />
                    Welcome <input value="<?php echo $user_data['Customer_Name'] ?>" disabled>;
                </section>
                <br />
                <form action="" method="post">
                    <div class="row">
                        <div class="form-group col-sm-8">
                            <h3>Username: <input value="<?php echo $user_data['Username'] ?>" disabled></h3>
                        </div>
                    </div><br /><br />
                    <div class="row">
                        <div class="form-group col-sm-8">
                            <h3>Name: <input value="<?php echo $user_data['Customer_Name'] ?>" disabled></h3>
                        </div>
                    </div><br /><br />
                    <div class="row">
                        <div class="form-group col-sm-8">
                            <h3>Email Address: <input value="<?php echo $user_data['Email'] ?>" disabled></h3>
                        </div>
                    </div><br /><br />
                    <div class="row">
                        <div class="form-group col-sm-8">
                            <h3>Password: <input id="password" value="<?php echo $user_data['Pass'] ?>" disabled></h3>
                            <button type="button" onclick="toggleUpdatePassword()">Update Password</button>
                        </div>
                    </div><br /><br />
                    <div class="row">
                        <div class="form-group col-sm-8">
                            <h3>Phone Number: <input id="phone" value="<?php echo $user_data['Phone_number'] ?>" disabled></h3>
                        </div>
                    </div><br />
                    
                </form>

            </div>
        </div>
    </div>
    <script>
        function toggleUpdatePassword() {
            var passwordInput = document.getElementById("password");
            passwordInput.disabled = !passwordInput.disabled;
            var phoneInput = document.getElementById("phone");
            phoneInput.disabled = !phoneInput.disabled;

            var newPasswordInput = document.querySelector("input[name='new_password']");
            var submitButton = document.querySelector("input[name='submit']");
            var newPhoneInput = document.querySelector("input[name='new_phone']");
            var levelLabel = document.querySelector("label[for='level']");

            if (!passwordInput.disabled) {
                // Show the input box and submit button
                newPasswordInput.style.display = "block";
                submitButton.style.display = "block";
                levelLabel.style.display = "block";
                newPhoneInput.style.display = "block";
            } else {
                // Hide the input box and submit button
                newPasswordInput.style.display = "none";
                submitButton.style.display = "none";
                levelLabel.style.display = "none";
                newPhoneInput.style.display = "block";
            }
        }
    </script>


    <div class="container text-center">
        <div class="row">
            <div class="col-md-12" style="background-color:#E4DCCF; opacity:1; color: black; font-family: Arial, Helvetica, sans-serif; font-size: medium;">

                <form action="" method="post">
                    <div class="row">
                        <div class="form-group-2 col-sm-8" style="font-family: Arial, Helvetica, sans-serif; font-size: medium;">
                            <label for="level" style="display: none; font-family: Arial, Helvetica, sans-serif; font-size: medium;">Password</label>
                            <!-- Hidden input field for the new password -->
                            <input type="password" name="new_password" style="display: none;" placeholder="Enter new password">
                            <!-- Hidden "Submit" button -->
                            <input type="submit" value="Submit" name="submit" style="display: none;">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


</body>

</html>