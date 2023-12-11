<?php
// Start session
session_start();

// Include database connection
include("connection/dbconnect.php");

// Enable error reporting for debugging
ini_set('display_startup_errors', 1);

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    // User not logged in
    header("Location: login.php");
    exit();
}
$errorMessage = $Name = $title = $Cinema_type = $hall_no = $price = $total = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $uid = $_POST['uid'];
    $price = $_POST['price'];
    $movie_id = $_POST['mid'];
    $title = $_POST['titlex'];
    $Cinema_type = $_POST['Cine_type'];
    $hall_no = $_POST['hall_no'];
    $timing = $_POST['timing'];
    $no_of_seats = $_POST['number_of_seats'];
    $date = $_POST['date'];
    $total = $price * $no_of_seats;
    $remaining_seat = $_POST["Availableseats"];


    $usql = "SELECT c.Customer_Name FROM customer c INNER JOIN users u ON u.U_ID = c.Customer_ID WHERE c.Customer_ID = '" . $_SESSION['user_id'] . "'; ";
    $uresult = mysqli_query($db, $usql);
    $uresult_info = mysqli_fetch_assoc($uresult);
    $customer_name = $uresult_info['Customer_Name'];
    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Buy Ticket</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <style>
            body {
                background-color: #00111c;
            }

            .navbar {
                background-color: #22404A;
            }

            .navbar,
            .nav-link,
            .navbar-brand {
                color: white;
            }

            .filter-genres {
                position: absolute;
                top: 20px;
                right: 20px;
                display: flex;
                flex-wrap: wrap;
            }

            .filter-genres a {
                display: inline-block;
                margin-right: 20px;
                color: #007bff;
                text-decoration: none;
                flex: 1;
                font-family: Arial, Helvetica, sans-serif;
                font-size: medium;
                color: wheat;
            }

            .filter-genres a:hover {
                text-decoration: underline;
            }

            .section1 {
                color: aliceblue;
            }

            .error {
                color: red;
            }

            .card {
                width: 12rem;
                height: 18rem;
                /* fixes width */
            }

            .card-img-top {
                height: 18rem;
                width: 12rem;
                object-fit: fill;
            }

            .card-body {
                background-color: #00111c;
                opacity: 1;
            }

            .card-title {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 15px;
                font-style: oblique;
                color: beige;
                opacity: 1;
                text-align: left;
                padding-left: 2%;
            }

            .row-cols-3 .col {
                flex: 0 0 50%;
                /* makes columns 50% width each */
                max-width: 50%;
                /* fallback for older browsers */
            }

            section {
                position: relative;
                image-resolution: 100%;
            }

            section img {
                width: 100%;
                height: 300px;
                object-fit: cover;
                opacity: 0.45;
            }

            .line::after {
                content: "";
                display: block;
                width: 100%;
                height: 2px;
                background: rgba(255, 255, 255, 0.35);
            }

            .modal-backdrop {
                backdrop-filter: blur(5px);
                /* Adjust the blur value as needed */
                background-color: rgba(0, 0, 0, 0.5);
                /* Adjust the opacity value as needed */
            }
        </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
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

                    <?php
                    if (empty($_SESSION["user_id"])) {
                        echo '<li class="nav-item"><a href="login.php" class="nav-link active"style="color:white;font-style:italic;">Login</a> </li>
							  <li class="nav-item"><a href="registration.php" class="nav-link active"style="color:white;font-style:italic;">Signup</a> </li>';
                    } else {
                        echo '<li class="nav-item"><a href="Movie_request.php" class="nav-link active"style="color:white;font-style:italic;">Request a Movie</a> </li>';
                        echo '<li class="nav-item"><a href="Ticket_Purchase.php" class="nav-link active"style="color:white;font-style:italic;">Buy Ticket</a> </li>';
                        echo '<li class="nav-item"><a href="Customer_profile.php" class="nav-link active"style="color:white;font-style:italic;">Profile</a> </li>';
                        echo '<li class="nav-item"><a href="logout.php" class="nav-link active"style="color:white;font-style:italic;">Logout</a> </li>';
                    }
                    ?>
                </ul>


            </div>

        </div>

    </nav><br /><br />

    <div class="container text-center">
        <div class="row">
            <div class="col-md-15" style="background-color:#E4DCCF; opacity:1; color: black; font-family: Arial, Helvetica, sans-serif; font-size: medium;">
                <br /><br />

                <h1>Payment Details</h1>

                <form action="update_payment.php" method="post">
                    <div class="mb-3">
                        <label>Customer Name</label>
                        <input type="text" class="form-control" value="<?php echo  $customer_name; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <!--<label>Movie Index</label> -->
                        <input type="text" class="form-control" name="uuser_ID" value="<?php echo $uid; ?>" hidden>
                        <input type="text" class="form-control" name="movi_index" value="<?php echo $movie_id; ?>" hidden>
                    </div>
                    <div class="mb-3">
                        <label>Movie Title</label>
                        <input type="text" class="form-control" value="<?php echo $title; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label>Cine type</label>
                        <input type="text" class="form-control" value="<?php echo $Cinema_type; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label>Hall ID</label>
                        <input type="text" class="form-control" name="hall_no" value="<?php echo $hall_no; ?>" disabled>
                        <input type="text" class="form-control" name="hall_no" value="<?php echo $hall_no; ?>" hidden>
                    </div>
                    <div class="mb-3">
                        <label>Number of Seats</label>
                        <input type="text" class="form-control" name="seat_amount" value="<?php echo $no_of_seats; ?>" disabled>
                        <input type="text" class="form-control" name="seat_amount" value="<?php echo $no_of_seats; ?>" hidden>
                    </div>

                    <div class="mb-3">
                        <label>Timing</label>
                        <input type="text" class="form-control" name="premier" value="<?php echo $timing; ?>" disabled>
                        <input type="text" class="form-control" name="premier" value="<?php echo $timing; ?>" hidden>
                    </div>

                    <div class="mb-3">
                        <label>Date</label>
                        <input type="text" class="form-control" name="Ticket_date" value="<?php echo $date; ?>" disabled>
                        <input type="text" class="form-control" name="Ticket_date" value="<?php echo $date; ?>" hidden>
                    </div>
            </div>

            <div class="mb-3">
                <label style="Color:White;">Total Amount</label>
                <input type="text" class="form-control" name="amount" value="<?php echo $total; ?> Taka" disabled>
                <input type="text" class="form-control" name="amount" value="<?php echo $total; ?> Taka" hidden>
                <input type="text" class="form-control" name="Availableseats" value="<?php echo $remaining_seat; ?>" hidden>
            </div>

            <div class="col-sm-10">
                <p> <input type="button" value="Confrim Payment?" name="Submit_payment"> </p>
            </div>

            </form>

        </div>
    </div>
    </div>
</body>

</html>