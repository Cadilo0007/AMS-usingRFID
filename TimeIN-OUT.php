<?php
    session_start();

    $connection = mysqli_connect("localhost", "root", "", "rfidattendance_db");
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $rfid_tag = $_POST['rfid_tag'];
        $action = $_POST['action'];

        $stmt = mysqli_prepare($connection, "SELECT id FROM users_data WHERE rfid_tag = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $rfid_tag);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $user_id);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($user_id) {
            $timeNow = date("H:i:s");
            $dateNow = date("Y-m-d");

            $checkQuery = "SELECT * FROM attendance WHERE user_id = ? AND date = ?";
            $stmt = mysqli_prepare($connection, $checkQuery);
            mysqli_stmt_bind_param($stmt, "is", $user_id, $dateNow);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $recordExists = mysqli_stmt_num_rows($stmt) > 0;
            mysqli_stmt_close($stmt);

            $fields = [
                'time_in' => 'time_in',
                'lunch_start' => 'lunch_start',
                'lunch_end' => 'lunch_end',
                'time_out' => 'time_out'
            ];

            if (array_key_exists($action, $fields)) {
                $field = $fields[$action];
                if ($recordExists) {
                    $attendanceQuery = "UPDATE attendance SET $field = ? WHERE user_id = ? AND date = ?";
                    $stmt = mysqli_prepare($connection, $attendanceQuery);
                    mysqli_stmt_bind_param($stmt, "sis", $timeNow, $user_id, $dateNow);
                } else {
                    $attendanceQuery = "INSERT INTO attendance (user_id, date, $field) VALUES (?, ?, ?)";
                    $stmt = mysqli_prepare($connection, $attendanceQuery);
                    mysqli_stmt_bind_param($stmt, "iss", $user_id, $dateNow, $timeNow);
                }

                if (mysqli_stmt_execute($stmt)) {
                    $response = ['status' => 'success', 'message' => ucfirst(str_replace('_', ' ', $action)) . ' recorded successfully.'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Error: ' . mysqli_error($connection)];
                }
                mysqli_stmt_close($stmt);
            } else {
                $response = ['status' => 'error', 'message' => 'Invalid action.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid RFID Tag.'];
        }

        echo json_encode(['uid' => $rfid_tag, 'message' => $response['message']]);
        exit;
    }

    // Fetch records with pagination and search
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
    $searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($connection, $_GET['search']) : '';

    $attendanceQuery = "
        SELECT a.id, a.user_id, u.rfid_tag, CONCAT(u.firstname, ' ', u.lastname) AS name, 
            a.date, a.time_in, a.lunch_start, a.lunch_end, a.time_out
        FROM attendance a
        JOIN users_data u ON a.user_id = u.id
        WHERE CONCAT(u.firstname, ' ', u.lastname) LIKE ?
        ORDER BY a.date DESC
        LIMIT ?
    ";

    $stmt = mysqli_prepare($connection, $attendanceQuery);
    $searchTermParam = "%$searchTerm%";
    mysqli_stmt_bind_param($stmt, "si", $searchTermParam, $limit);
    mysqli_stmt_execute($stmt);
    $query_run = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Attendance</title>
    <link rel="icon" href="ADMIN/assets/image/ICLOGO.png"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Questrial&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="ADMIN/assets/css/time.css">
    <style>
        .rfid_tag {
            width: 80%;
            padding: 10px;
            margin: 10px;
            font-size: 16px;
            color: yellowgreen;
            border: 2px solid var(-)white;
            border-radius: 5px;
            background-color: var(--black1);
        }
        .action-select{
            padding: 10px;
            width: 80%;
        }
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php
    $dateString = '2024-09-12'; // Date string
    $timestamp = strtotime($dateString);
    $formattedDate = date('M d Y', $timestamp);
    ?>
    <div class="container">
        <nav>
            <div class="brand-container">
                <img class="logo"src="ADMIN/assets/image/ICLOGO.png" width="50px"/>
                <h2 class="brand-title">RFID Attendance<p style="font-size: small;">Isabela Colleges Inc.</p></h2>
            </div>

            <div class="links-container"> 
                <a class="link" href="Index.php">Home</a>
                <a class="link" href="About.php">About</a>
                <a class="link" href="Contact.php">Contact</a>
                <a class="btn-link" href="TimeIN-OUT.php">Time In/Out</a>
                <a class="btn-link" href="employeelogin.php">Log In here</a>
            </div>           
        </nav> 
        <div id="content1" class="attendanceView-Em">
                <div class="time-date" id="">
                    <div id="clockdate"  class="clockdate">
                        <div class="clockdate-wrapper">
                            <div id="clock" class="clock">04:47:54 <span>PM</span></div>
                            <div id="date" class="date"><i class="fas fa-calendar"></i> June 14, 2024</div>
                        </div>
                        
                    </div>
                </div>  
            <div class="how-use">
                <h1>
                    Tap the RFID card on the RFID Reader for Time in and Time out.
                </h1>
            </div>
        </div>
                <!-- Modal Structure -->
                <div id="responseModal" class="modal" style="display: none;">
                    <div class="modal-content">
                        <span id="closeModal" class="close">&times;</span>
                        <p id="modalMessage"></p>
                    </div>
                </div>

                <div id="content2" class="details">
                    <div class="attendanceView-Em">
                        <div class="cardHeader">
                            <caption><h2>Attendance View</h2></caption>
                        </div>

                        <div class="cardHeader2">
                            <label for="entriesSelect"><a class="btn">Show
                                <select id="entriesSelect" onchange="updateTable()">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                entries</a>
                            </label>
                            <div class="search">
                                <label>
                                    <ion-icon name="search-sharp"></ion-icon>
                                    <input type="search" placeholder="Search here"/>
                                </label>
                            </div>    
                        </div>
                        <table class="All">
                            <thead>
                                <tr>
                                    <td>Employee RFID</td>
                                    <td>Name</td>
                                    <td>Date</td>
                                    <td>Time In</td>
                                    <td>Lunch Start</td>
                                    <td>Lunch End</td>
                                    <td>Time Out</td>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                // Custom function to replace '12:00 AM' with an empty string
                                        function formatTime($time) {
                                            return ($time == '12:00 AM') ? '' : $time;
                                        }
                                if ($query_run && mysqli_num_rows($query_run) > 0) {
                                    while ($row = mysqli_fetch_assoc($query_run)) {
                                        $date = new DateTime($row['date']);
                                        $formattedDate = $date->format('M d Y');
                                        $time_in = date('g:i A', strtotime($row['time_in']));
                                        $lunch_start = date('g:i A', strtotime($row['lunch_start']));
                                        $lunch_end = date('g:i A', strtotime($row['lunch_end']));
                                        $time_out = date('g:i A', strtotime($row['time_out']));

                                        
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['rfid_tag']); ?></td>
                                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                                            <td><?php echo htmlspecialchars($formattedDate); ?></td>
                                            <td><span class="status timein"><?php echo formatTime($time_in); ?></span></td>
                                            <td><span class="status breakout"><?php echo formatTime($lunch_start); ?></span></td>
                                            <td><span class="status breakin"><?php echo formatTime($lunch_end); ?></span></td>
                                            <td><span class="status timeout"><?php echo formatTime($time_out); ?></span></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="7">No records found</td></tr>';
                                }
                                ?>
                            </tbody>

                </table>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var form = document.querySelector('form');
                var modal = document.getElementById('responseModal');
                var modalMessage = document.getElementById('modalMessage');
                var rfidInput = document.querySelector('input[name="rfid_tag"]'); // Target the RFID input field

                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    var formData = new FormData(form);

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'TimeIN-OUT.php', true);
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            
                            // Populate the RFID input field with the UID
                            rfidInput.value = response.uid;
                            
                            // Display the response in the modal
                            modalMessage.textContent = response.message;
                            modal.style.display = 'block';
                        } else {
                            console.error('An error occurred:', xhr.statusText);
                        }
                    };
                    xhr.send(formData);
                });

                var closeButton = document.getElementById('closeModal');
                closeButton.addEventListener('click', function() {
                    modal.style.display = 'none';
                });
            });
        </script>                   
    <script>
        function updateTable() {
            var limit = document.getElementById('entriesSelect').value;
            window.location.href = 'TimeIN-OUT.php?limit=' + limit;
        }
    </script>
        <!-- RFID Reader Form -->
        <form method="POST" action="TimeIN-OUT.php">
            <div id="content3" class="Employeeslist">
                <div class="cardHeader">
                    <h2>RFID Reader</h2>
                </div>
                <br>
                <div class="reader">
                    <input type="t" name="rfid_tag" class="rfid_tag" placeholder="Tap your RFID card..." autofocus required>
                    <br>
                    <select name="action" class="action-select" required>
                        <option value="time_in">Time In</option>
                        <option value="lunch_start">Lunch Start</option>
                        <option value="lunch_end">Lunch End</option>
                        <option value="time_out">Time Out</option>
                    </select>
                    <img src="ADMIN/assets/image/rfidCard.jpg" class="feature-img"/>
                </div>
                <input class="btn-link" type="submit" value="Submit">
            </div>
        </form>

    </div>   
    <script>
            document.addEventListener('DOMContentLoaded', function() {
            // Get the form and modal elements
            var form = document.querySelector('form');
            var modal = document.getElementById('responseModal');
            var modalMessage = document.getElementById('modalMessage');

            // Handle form submission with AJAX
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                // Create a FormData object from the form
                var formData = new FormData(form);

                // Send AJAX request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'TimeIN-OUT.php', true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Parse the JSON response
                        var response = JSON.parse(xhr.responseText);

                        // Display the response in the modal
                        modalMessage.textContent = response.message;
                        modal.style.display = 'block';

                        // Optionally, update the attendance table here
                        // updateTable();
                    } else {
                        // Handle errors
                        console.error('An error occurred:', xhr.statusText);
                    }
                };
                xhr.send(formData);
            });

            // Close the modal when the (x) button is clicked
            var closeButton = document.getElementById('closeModal');
            closeButton.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        });
    </script>
            <script src="ADMIN/assets/js/time.js"></script> 
            <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>  
</body>

</html>