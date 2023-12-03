<!doctype html>
<html lang="en">
<?php
include("connection/dbconnect.php");  //include connection file
error_reporting(0);  // using to hide undefine undex errors
session_start(); //start temp session until logout/browser closed

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Movies</title>
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
                    <li class="nav-item">
                        <form class="d-flex" role="search">
                            <input id="search" class="form-control me-2" type="search" id="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit" id="search-btn">Search</button>

                        </form>
                        <!-- Menu items -->
                    </li>
                    <?php
                    if (empty($_SESSION["user_id"])) {
                        echo '<li class="nav-item"><a href="login.php" class="nav-link active"style="color:white;font-style:italic;">Login</a> </li>
							  <li class="nav-item"><a href="registration.php" class="nav-link active"style="color:white;font-style:italic;">Signup</a> </li>';
                    } else {
                        echo '<li class="nav-item"><a href="movie_request.php" class="nav-link active"style="color:white;font-style:italic;">Request a Movie</a> </li>';
                        echo '<li class="nav-item"><a href="Ticket_Purchase.php" class="nav-link active"style="color:white;font-style:italic;">Buy Ticket</a> </li>';
                        echo '<li class="nav-item"><a href="Customer_profile.php" class="nav-link active"style="color:white;font-style:italic;">Profile</a> </li>';
                        echo '<li class="nav-item"><a href="logout.php" class="nav-link active"style="color:white;font-style:italic;">Logout</a> </li>';
                    }
                    ?>
                </ul>


            </div>

        </div>

    </nav>
    <!--- navbar ended -->
    <section>
        <img src="img/Background_pic3.png">
    </section>
    <div class="line"></div><br />
    <div id="movies">
        <div class="container">
            <section class="section1" padding-left:10px>
                <h5><strong><i>📌All Movies 📌</i></strong></h5>
            </section>
        </div>

        <?php
        $sql = "SELECT DISTINCT Genre FROM movie_genre";
        $result = mysqli_query($db, $sql);
        ?>
        <div class="container">
            <section>
                <div class="filter-genres">
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <a class="nav-link" href="available_movies.php?genre=<?php echo $row['Genre']; ?>">
                            <?php echo $row['Genre']; ?>
                        </a>
                    <?php } ?>
                </div>
            </section>
        </div>
        <br /><br /><br />
        <div class="container text-center">
            <div class="row row-cols-3" style="padding-bottom: 0%;">
                <?php
                $genre = isset($_GET['genre']) ? $_GET['genre'] : "";
                if ($genre) {
                    $sql = "SELECT * FROM movies M INNER JOIN movie_genre G on M.Movie_index=G.Movie_index where G.Genre='$genre'";
                } else {
                    $sql = "SELECT * FROM movies";
                }
                $result = mysqli_query($db, $sql);
                if (mysqli_num_rows($result) > 0) {
                    // here we will print every row that is returned by our query
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <div class="col-4">
                            <div class="card" style="size:0cap">
                                <a class="nav-link" href="movies.php?id=<?php echo $row['Movie_index']; ?>"><img src="<?php echo $row['CImage']; ?>" class="card-img-top"></a>
                                <div class="card-body">
                                    <a class="nav-link" href="movies.php?id=<?php echo $row['Movie_index']; ?>">
                                        <h5 class="card-title"><?php echo $row['Title'] ?></h5>
                                    </a>
                                </div>
                            </div><br /><br /><br />
                        </div>
                <?php
                    }
                }

                ?>

            </div>
        </div>


        <br /><br /><br /><br />
        <div class="line"></div><br />
        <div class="container">
            <div style="text-align: right; ">
                <a class="nav-link" href="available_movies.php"><button type="button" class="btn btn-secondary rounded-pill fw-bold" style="background-color: beige; color: black;">
                        Show All Movies
                    </button></a>


            </div>
        </div>
    </div>
    </div>

    <div id="searchresult"></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $("#search-btn").click(function(event) {

            event.preventDefault();

            var searchTerm = $("#search").val();

            $.ajax({
                url: "search.php",
                type: "POST",
                data: {
                    search: searchTerm
                },
                success: function(data) {
                    $("#movies").html(data);
                }
            });

        });
    </script>


</body>

</html>