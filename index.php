<?php

$url = "https://apicars.prisms.in/user/getall";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result =  curl_exec($ch);
curl_close($ch);
$result = json_decode($result, true);

$cr_result='';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $ucUrl = "https://apicars.prisms.in/user/create";
    $crUserData = array(
        "name" => $_POST['name'],
        "phone_no" => $_POST['phone']
    );
    $crJsonData = json_encode($crUserData);
    $ch = curl_init($ucUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $crJsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $jsonResponse = curl_exec($ch);
    curl_close($ch);
    $cr_result = json_decode($jsonResponse, true);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $url = "https://apicars.prisms.in/user/get/$user_id";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $car_result =  curl_exec($ch);
    curl_close($ch);
    $car_result = json_decode($car_result, true);
} else {
    $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M.Garage | User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="logo">
            <p>M.Garage</p>
        </div>
        <div class="user-btn">
            <p>User Management</p>
        </div>
        <div class="car-btn">
            <p><a href="car.php">Car Management</a></p>
        </div>
    </div>

    <div class="user_heading">
        <h1>User Management</h1>
    </div>

    <?php
        if (!empty($cr_result) && isset($cr_result['error'])) {
            if ($cr_result['error'] == 0) {
                echo "<p style='color:green'>...User created successfully! </p>";
            } else {
                echo "<p style='color:red'>Error : " . $cr_result['error-message'] . "</p>";
            }
        }
    ?>

    <div class="create_user_form" id="create_close_btn" style="display:none">
        <div class="head_close">
            <h2>Create User</h2>
            <button onclick="create_close()" type="submit">X</button>
        </div>
        <form action="" method="post">
            <label for="name">User Name:</label><br>
            <input type="text" name="name" placeholder="Enter User Name" required><br>
            <label for="phone">Phone Number:</label><br>
            <input type="text" name="phone" placeholder="Enter User Phone Number" required><br>
            <button type="submit">Create</button>
        </form>
    </div>

    <div class="create_search" id="create_close_btn">
        <div class="create_user_btn">
            <button onclick="create_close()">Create User</button>
        </div>

        <div class="serach_car">
            <form action="" method='post'>
                <input type="text" name='user_id' placeholder='Enter User Id'>
                <button type="submit">Show Cars</button>
            </form>
        </div>
    </div>

    <div class="user_management">
        <div class="user_body">
            <h2>Users</h2>
            <div class="users">
            <table>
                <tr>
                    <th>Sr. No. / Id</th>
                    <th>Name</th>
                    <th>Phone No.</th>
                </tr>
                <?php
                if (isset($result["Users"])) {
                    for ($i = 0; $i < count($result["Users"]); $i++) {
                        echo '<tr>';
                        echo '<td>' . $result["Users"][$i]["id"] . '</td>';
                        echo '<td>' . $result["Users"][$i]["name"] . '</td>';
                        echo '<td>' . $result["Users"][$i]["phone_no"] . '</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </table>                
            
            </div>
        </div>
        <div class="user_car_body">
            <div class="show_car">
                <h2>User's Cars</h2>
                <?php 
                if (isset($car_result["User"])) {
                    echo "<h3> Owner Id : ". $user_id ."</h3>";
                }
                elseif (empty($_POST['user_id'])){
                    echo "<h3> &#8679; Enter User Id</h3>";
                }
                else {
                    echo "<h3 style='color:red'> Invalid User Id</h3>";
                }
                ?>
            </div>

            <table>
                <?php
                if (isset($car_result["User"])) {
                    $userR = $car_result["User"];
                        echo '<tr>';
                        echo '<td> <b>User Id: </b>' . $userR['id'] . '</td>';
                        echo '<td> <b>Name : </b>' . $userR['name'] . '</td>';
                        echo '<td> <b>Phone No. : </b>' . $userR['phone_no'] . '</td>';
                        echo '</tr>';
                    }
                ?>
            </table>

            <div class="cars">
                <table>
                    <tr>
                        <th>Car Id </th>
                        <th>Model </th>
                        <th>Color </th>
                        <th>Purchase Date</th>
                    </tr>
                    <?php
                    if (isset($car_result["User"]["Cars"])) {
                        $cars = $car_result["User"]["Cars"];
                        foreach ($cars as $car) {
                            echo '<tr>';
                            echo '<td>' . $car['id'] . '</td>';
                            echo '<td>' . $car['model'] . '</td>';
                            echo '<td>' . $car['color'] . '</td>';
                            echo '<td>' . $car['purchase_date'] . '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>