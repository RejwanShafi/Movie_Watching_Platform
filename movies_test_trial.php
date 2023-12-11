<!DOCTYPE html>
<html lang="en">
<?php

include("connection/dbconnect.php");  //include connection file
error_reporting(0);  // using to hide undefine undex errors
session_start(); //start temp session until logout/browser closed

//Get movieID
$movie_id = $_GET['id'];
$isNewMovie = false;
$isOldMovie = false;
$U_id=$_SESSION["user_id"];

//Retriving Movie Details
$sql = "SELECT * FROM movies WHERE Movie_index=$movie_id";
$result = mysqli_query($db, $sql);
$movie = mysqli_fetch_assoc($result);
$trailer_link = $movie["Trailer_link"];
$video_id = explode('=', $trailer_link)[1];

//Checking if new or old movie
$sql = "SELECT * FROM new_movies WHERE Movie_index=$movie_id";
if (mysqli_num_rows(mysqli_query($db, $sql)) > 0) {
    $isNewMovie = true;
}

$sql = "SELECT * FROM old_movies WHERE Movie_index=$movie_id";
$old_result = mysqli_query($db, $sql);
if (mysqli_num_rows($old_result) > 0) {
    $isOldMovie = true;
    $oldMovie = mysqli_fetch_assoc($old_result);
    $stream_link = $oldMovie["Stream_Link"];
}
function createCommentRow($data) {
    global $db;

    $response = '
            <div class="comment">
                <div class="user">'.$data['Customer_Name'].' <span class="time">'.$data['Comment_Date'].'</span></div>
                <div class="userComment">'.$data['Comment_Text'].'</div>
                <div class="reply"><a href="javascript:void(0)" data-commentID="'.$data['C_ID'].'" onclick="reply(this)">REPLY</a></div>
                <div class="replies">';
    $sql = $db->query("SELECT r.r_id, c.Customer_Name, r.Reply, r.Reply_Date FROM replies r INNER JOIN customer c ON r.Customer_ID = c.Customer_ID WHERE r.c_id = '".$data['C_ID']."'  ORDER BY r.r_id DESC LIMIT 1");
    while($dataR = $sql->fetch_assoc())
        $response .= createCommentRow($dataR);

    $response .= '
                        </div>
            </div>
        ';

    return $response;
}

if (isset($_POST['getAllComments'])) {
    $start = $db->real_escape_string($_POST['start']);
    $response="";
    $sql = $db->query("SELECT Customer_Name,Comment_Text, c.Comment_Date FROM comments c INNER JOIN customer u on c.Customer_ID=u.Customer_ID WHERE C.Movie_Index='$movie_id' ORDER by c.C_ID DESC LIMIT $start, 20");
    while($data = $sql->fetch_assoc())
        $response .= createCommentRow($data);

    exit($response);
}
if (isset($_POST['addComment'])) {
    $comment = $db->real_escape_string($_POST['comment']);
    $isReply = $db->real_escape_string($_POST['isReply']);
    $commentID = $db->real_escape_string($_POST['commentID']);

    if ($isReply != 'true') {
        $db->query("INSERT INTO replies (c_id, Customer_ID, Movie_Index, Reply,Reply_Date) VALUES ('$commentID','".$_SESSION['userID']."','$movie_id', '$comment', CURDATE())");
        $sql = $db->query("SELECT r.r_id, u.Customer_Name, r.Reply, r.Reply_Date FROM replies r INNER JOIN customer u ON r.Customer_ID = u.Customer_ID WHERE r.Movie_Index=$movie_id ORDER BY replies.id DESC LIMIT 1");
    } else {
        $db->query("INSERT INTO comments (Customer_ID, Movie_Index,Comment_Text, Comment_Date) VALUES ('".$_SESSION['userID']."','$movie_id','$comment',CURDATE())");
        $sql = $db->query("SELECT c.C_ID, u.Customer_Name, c.Comment_Text, c.Comment_Date FROM comments c INNER JOIN customer u ON c.Customer_ID = u.Customer_ID WHERE c.Movie_Index=$movie_id ORDER BY c.C_ID DESC LIMIT 1");
    }

    $data = $sql->fetch_assoc();
    exit(createCommentRow($data));
}

if (isset($_POST["Buy_Ticket"]) || isset($_POST["addComments"])) {

    // Check if user logged in
    if (!isset($_SESSION["user_id"])) {

        // User not logged in
        header("Location: login.php");
        exit('Success');
    }else{
        if(isset($_POST["Buy_Ticket"])) {
        
            // Redirect to ticket purchase page
            header("Location: Ticket_Purchase.php?id=$movie_id"); 
            exit;
      
          } 
          
    }
    
}
$sqlNumComments = $db->query("SELECT C.C_ID FROM comments C WHERE C.Movie_Index=$movie_id ");
$numComments = $sqlNumComments->num_rows;

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $movie["Title"]; ?>
    </title>
    <!-- Bootstrap CSS -->
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
            font-family: Arial, Helvetica, sans-serif;
            font-size: 25px;
            font-style: italic;
        }

        .card {
            width: 18rem;
            height: 15rem;
            background-color: transparent;
            padding: 0;
            /* fixes width */
        }

        .card-img-top {
            height: 15rem;
            width: 18rem;
            object-fit: fill;
            padding: 0;

        }

        .card-body {
            background-color: #00111c;
            opacity: 1;
            padding: 0;
            height: 15rem;
            width: 18rem;
        }

        .card-title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;
            font-style: oblique;
            color: beige;
            opacity: 1;
            text-align: left;
            padding-left: 0;
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

        .movie-details {
            line-height: 2.5;
        }




        .line::after {
            content: "";
            display: block;
            width: 100%;
            height: 2px;
            background: rgba(255, 255, 255, 0.35);
        }

        .line1::after {
            content: "";
            display: block;
            width: 100%;
            height: 1.5px;
            background: rgba(0, 0, 0, 0.35);
        }


        .comment {
            margin-bottom: 20px;
        }

        .user {
            font-weight: bold;
            color: black;
        }

        .time,
        .reply {
            color: gray;
        }

        .userComment {
            color: #000;
        }

        .replies .comment {
            margin-top: 20px;

        }

        .replies {
            margin-left: 20px;
        }

    
        button {
            background-color: rgb(200, 0, 50);
            color: white;
            padding: 8px 20px;
            border-radius: 300px;
            cursor: pointer;
            margin-left: 360px;
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

    </nav>
    <div class="line"></div><br /><br />
    <div class="container">
        <section class="section1 padding-left: 10px">
            <h2><?php echo $movie["Title"]; ?></h2>
            <div class="line"></div>
        </section>
    </div>
    <br /><br />
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card" style="size:10px;">
                    <!-- Movie Cover Image -->
                    <img src="<?php echo $movie["CImage"];   ?>">
                </div>
            </div>
            <!-- Vertical line -->

            <div class="col-md-6" style="background-color:White; opacity:0.75;">
                <!--- Retriving Release Year -->
                <div class="row" style="color:Black; opacity:1;">
                    <p>
                    <h2><b><i><?php echo $movie["Title"]; ?></h2></b></i></p>
                    <p>
                    <h5>Release Date : <?php echo $movie['Release_Date']; ?></h5>
                    </p>
                    <div class="line1"></div>
                </div>
                <!--- Retriving Watch Time -->
                <div class="row" style="color:Black; opacity:1;">
                    <p>
                    <h5>Runtime : <?php echo $movie['Watch_Time']; ?> Minutes</h5>
                    </p>
                    <div class="line1"></div>
                </div>
                <!--- Retriving Prequel -->
                <div class="row" style="color:Black; opacity:1;">
                    <p>
                    <h5>Prequel : <?php
                                    if ($movie['Pre_sequel'] == NULL) {
                                        echo "None";
                                    } else {
                                        echo $movie['Pre_sequel'];
                                    }
                                    ?>
                    </h5>
                    </p>
                    <div class="line1"></div>
                </div>
                <!--- Retriving Country -->
                <div class="row" style="color:Black; opacity:1;">
                    <p>
                    <h5>Country : <?php echo $movie['Country']; ?></h5>
                    </p>
                    <div class="line1"></div>
                </div>
                <!--- Retriving Genre -->
                <div class="row" style="color:Black; opacity:1;">
                    <?php

                    // Get genres 
                    $sql = "SELECT * FROM movie_genre WHERE Movie_index=$movie_id";
                    $result = mysqli_query($db, $sql);
                    $genres = array();

                    while ($row = mysqli_fetch_assoc($result)) {
                        $genres[] = $row['Genre'];
                    }
                    ?>
                    <!--- Concating infos -->
                    <p>
                    <h5>Genre: <?php echo implode(", ", $genres); ?></h5>
                    </p>
                    <div class="line1"></div>
                </div>
                <!--- Retriving Cast info -->
                <div class="row" style="color:Black; opacity:1;">
                    <?php

                    // Get Casts 
                    $sql = "SELECT * FROM movie_castings WHERE Movie_index=$movie_id";
                    $result = mysqli_query($db, $sql);
                    $Casts = array();

                    while ($row = mysqli_fetch_assoc($result)) {
                        $Casts[] = $row['Cast'];
                    }
                    ?>

                    <!--- Concating infos -->
                    <p>
                    <h5>Cast: <?php echo implode(", ", $Casts); ?></h5>
                    </p>
                    <div class="line1"></div>

                </div>


            </div>
        </div><br /><br /><br />

        <div class="line"></div>

        <div class="row" style="color:aliceblue;">
            <div class="col-sm-6">
                <p>
                <h5>Trailer</h5>
                </p>
                <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $video_id; ?>?autoplay=1" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="col-sm-6">
                <section class="movie-details">
                    <p>
                    <h5><i><b>Storyline</b></i></h5>
                    </p>
                    <?php echo $movie["Summary"]; ?>
                </section>
                <br />

                <!-- Checking if old or new movie -->
                <?php if ($isOldMovie) { ?>
                    <section class="movie-details">
                        <p>
                        <h5><i><b>Stream Now</b></i></h5>
                        </p>
                        <?php echo "<a class='btn btn-primary'  href='$stream_link' role='button'>Stream</a>"; ?>
                    </section>
                <?php } else { ?>
                    <!-- Show buy tickets for new movies -->
                    <form action="" method="POST">
                        <button name="Buy_Ticket">Buy Tickets</button>
                    </form>

                <?php } ?>

                <!--comment section-->

            </div>

        </div><br /><br />
        <!-- Comment Section -->
        <div class="row">
            <div class="col-md-12">
                <form action="" method="POST">
                    <textarea class="form-control" id="mainComment" placeholder="Add Public Comment" cols="30" rows="2"></textarea><br>
                    <button style="float:right"  class="btn-primary btn" name="addComments" addonclick="isReply = false;" id="addComment">Add Comment</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2><b id="numComments" style="color:white;"><?php echo $numComments ?> Comments</b></h2>
                <div class="userComments">

                </div>
            </div>
        </div>

    </div>
    <div class="row replyRow" style="display:none">
        <div class="col-md-12">
            <textarea class="form-control" id="replyComment" placeholder="Add Public Comment" cols="30" rows="2"></textarea><br>
            <button style="float:right" class="btn-primary btn" onclick="isReply = true;" id="addReply">Add Reply</button>
            <button style="float:right" class="btn-default btn" onclick="$('.replyRow').hide();">Close</button>
        </div>
    </div>

    <script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript">
        var isReply = false, commentID = 0, max = <?php echo $numComments ?>;

        $(document).ready(function () {
        $("#addComment, #addReply").on('click', function () {
            var comment;

            if (!isReply)
                comment = $("#mainComment").val();
            else
                comment = $("#replyComment").val();

            if (comment.length > 5) {
                $.ajax({
                    url: 'tt.php',
                    method: 'POST',
                    dataType: 'text',
                    data: {
                        addComment: 1,
                        comment: comment,
                        isReply: isReply,
                        commentID: commentID
                    }, success: function (response) {
                        max++;
                        $("#numComments").text(max + " Comments");

                        if (!isReply) {
                            $(".userComments").prepend(response);
                            $("#mainComment").val("");
                        } else {
                            commentID = 0;
                            $("#replyComment").val("");
                            $(".replyRow").hide();
                            $('.replyRow').parent().next().append(response);
                        }
                    }
                });
            } else
                alert('Please Check Your Inputs');
        });

       

        getAllComments(0, max);
    });

    function reply(caller) {
        commentID = $(caller).attr('data-commentID');
        $(".replyRow").insertAfter($(caller));
        $('.replyRow').show();
    }

    function getAllComments(start, max) {
        if (start > max) {
            return;
        }

        $.ajax({
            url: 'tt.php',
            method: 'POST',
            dataType: 'text',
            data: {
                getAllComments: 1,
                start: start
            }, success: function (response) {
                $(".userComments").append(response);
                getAllComments((start+20), max);
            }
        });
    }
    </script>

</body>

</html>