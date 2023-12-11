<?php
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
echo $remaining_seat;
?>