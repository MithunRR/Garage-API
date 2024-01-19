<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['model'] )){
    $cc_url = "https://apicars.prisms.in/car/create";
    $car_data = array(
        "model" => $_POST['model'],
        "color" => $_POST['color'],
        "ownerid" => $_POST['ownerid'],
        "purchase_date" => $_POST['purchase_date']
    );
    $carJson = json_encode($car_data);
    $ch = curl_init($cc_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $carJson);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $cc_carjsonR = curl_exec($ch);
    curl_close($ch);
    $cc_result = json_decode($cc_carjsonR, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['serv_carID'])) {
    $carID = (int)$_POST['serv_carID'];
    if ($carID) {
        $url = "https://apicars.prisms.in/car/get/$carID";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result, true);
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M.Garage | Cars</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
        <div class="logo">
            <p>M.Garage</p>
        </div>
        <div class="user-btn">
            <p><a href="index.php">User Management</a></p>
        </div>
        <div class="car-btn">
            <p>Car Management</p>
        </div>
    </div>

    <div class="car_heading">
        <h1>Car Management</h1>
    </div>

    <?php
        if (!empty($cc_result) && isset($cc_result['error'])) {
            if ($cc_result['error'] == 0) {
                echo "<p style='color:green'>... Car created successfully! </p>";
            } else {
                echo "<p style='color:red'>Error : " . $cc_result['error-message'] . "</p>";
            }
        }
    ?>

    <div class="create_user_form" id="create_close_btn" style="display:none">
        <div class="head_close">
            <h2>Create Car Entry</h2>
            <button onclick="create_close()" type="submit">X</button>
        </div>
        <form action="" method="post">
            <label for="model">Model:</label><br>
            <input type="text" name="model" placeholder="Enter Car Model" required><br>
            <label for="color">Color:</label><br>
            <input type="text" name="color" placeholder="Enter Car Color" required><br>
            <label for="ownerid">Owner Id:</label><br>
            <input type="text" name="ownerid" placeholder="Enter Car's Owner Id" required><br>
            <label for="purchase_date">Purchase Date: ( yyyy-mm-dd )</label><br>
            <input type="text" name="purchase_date" placeholder="Enter Car's Purchase Date in ( yyyy-mm-dd ) format" required><br>
            <button type="submit">Create</button>
        </form>
    </div>

    <div class="create_user_form" id="create_serv_btn" style="display:none">
        <div class="head_close">
            <h2>Create Servicing Entry</h2>
            <button onclick="create_serv_close()" type="submit">X</button>
        </div>
        <form action="" method="post">
            <label for="carid">Car Id:</label><br>
            <input type="text" name="carid" placeholder="Enter Car Id" required><br>
            
            <label for="servicing_date">Servicing Date: ( yyyy-mm-dd )</label><br>
            <input type="text" name="servicing_date" placeholder="Enter Car's Servicing Date in ( yyyy-mm-dd ) format" required><br>
            
            <label for="status">Status:</label><br>
            <input type="text" name="status" placeholder="Enter Car's Servicing Status" required><br>
            <button type="submit">Create</button>
        </form>
    </div>

    <div class="create_search" id="create_close_btn">
        <div class="create_user_btn">
            <button onclick="create_close()">Create Car Entry</button>
        </div>

        <div class="create_serv_btn">
            <button onclick="create_serv_close()"> Create Servicing Entry</button>
        </div>
    </div>

    <div class="user_management">
        <div class="user_body">
            <div style="display:flex; justify-content:space-between">
                <h2>Car Details</h2>
                <!-- <div class="serach_serv">
                    <form action="" method='post'>
                        <input type="text" name='user_id' placeholder='Enter Car Id'>
                        <button type="submit">Search</button>
                    </form>
                </div> -->
            </div>
            <div class="users">
            <table>
                <tr>
                    <th>Car Id </th>
                    <th>Model </th>
                    <th>Purchase Date</th>
                </tr>
                <?php
                if (isset($result['Car'])) {
                        echo '<tr>';
                        echo '<td> <b> ' . $result['Car']['id'] . ' </b> </td>';
                        echo '<td> <b> ' . $result['Car']['model'] . '</b> </td>';
                        echo '<td> <b> ' . $result['Car']['purchase_date'] . ' </b> </td>';
                        echo '</tr>';
                    }
                ?>
            </table>                
            
            </div>
        </div>
        <div class="user_car_body">
            <div class="serach_serv">
                <form action="" method='post'>
                <input type="text" id="serv_carID" name="serv_carID" placeholder="Enter Car ID" required>
                <button type="submit">Search</button>
                </form>
            </div>
            <div class="show_car">
                <h2>Servicing Details</h2>
            </div>
            <div class="cars">
                <table>
                    <tr>
                        <th>Servicing Id </th>
                        <th>Servicing Date</th>
                        <th>Status </th>
                    </tr>
                    <?php
                    if (isset($result['Car'])){
                        $carDetails = $result['Car'];
                        if (isset($carDetails['Servicing'])) {
                            $servicings = $carDetails['Servicing'];
                            foreach ($servicings as $servicing) {
                                if (isset($servicing['id'])) {
                                    echo '<tr>';
                                    echo '<td>' . $servicing['id'] . '</td>';
                                    echo '<td>' . $servicing['servicing_date'] . '</td>';
                                    echo '<td>' . $servicing['status'] . '</td>';
                                    echo '</tr>';
                                }
                            }
                        }
                        if (empty($carDetails['Servicing'])) {
                            echo '<p style="color:red"> No Servicing Records with this car id </p>';
                        }
                    }
                    if (isset($result['error-message'])) {
                        $errorMsg = $result['error-message'];
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>