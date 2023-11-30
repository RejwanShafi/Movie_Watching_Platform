<?php
$movie_id = $_GET['id']; 
echo $movie_id;

// SQL query to fetch details of this movie 
$sql = "SELECT * FROM movies 
        WHERE Movie_index=$movie_id";

$result = mysqli_query($db, $sql);

if(mysqli_num_rows($result) > 0){

  $row = mysqli_fetch_assoc($result);

  // Display movie data
  echo "<h2>".$row['Title']."</h2>"; 
  echo "<img src='".$row['CImage']."'>";
  echo "<p>".$row['Description']."</p>";

  // other details like rating, genre etc

} else {
  echo "Error fetching details"; 
}
?>