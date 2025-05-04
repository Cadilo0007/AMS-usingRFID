<?php
    include('security.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Administrators</title>
    <link rel="icon" href="assets/image/ICLOGO.png"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Questrial&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="assets/css/style4.css">
    <style>
        
        /* Pagination container */
        .pagination {
            display: flex;
            justify-content:space-between;
            align-items: center;
            margin: 20px 0;
            font-family: Arial, sans-serif;
        }

        /* Pagination text */
        .pagination p {
            margin-right: 20px;
            font-size: 14px;
            color: #333;
        }

        /* Pagination list */
        .pagination ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        /* Pagination list items */
        .pagination li {
            margin: 0 5px;
        }

        /* Pagination links */
        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            text-decoration: none;
            color: #007bff;
            border: 1px solid #007bff;
            border-radius: 4px;
            font-size: 14px;
        }

        /* Pagination links hover and active states */
        .pagination a:hover {
            background-color: #007bff;
            color: #fff;
        }

        .pagination a.active {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        /* Pagination Previous and Next buttons */
        .pagination li a {
            padding: 8px 12px;
            border-radius: 4px;
        }

        .pagination li a.disabled {
            color: #ccc;
            border-color: #ccc;
            pointer-events: none;
        }
        

    </style>
</head>
<body>
    <div class="container">
            <div class="navigation"> 
                <ul>
                    <li>
                        <a href="#">
                            <span class="icon">
                                <img class="logo"src="assets/image/ICLOGO.png" width="50px"/>
                            </span>
                            <span class="title">Isabela Colleges Inc.</span>
                            <h6>RFID Attendance Monitoring System</h6>
                        </a>
                    </li>
                    <hr>
                    <li class="Active">
                        <a href="index.php" target="_self">
                            <span class="icon">
                                <ion-icon name="home-outline"></ion-icon>
                            </span>
                            <span class="title"><h3>Dashboard</h3></span>
                        </a>
                    </li>

                    <li>
                        <a href="register.php" target="_self">
                            <span class="icon">
                                <ion-icon name="people-outline"></ion-icon>
                            </span>
                            <span class="title"><h3>Employees List</h3></span>
                        </a>
                    </li>
                    <li>
                        <a href="Attendance.php" target="_self">
                            <span class="icon">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </span>
                            <span class="title"><h3>Attendance</h3></span>
                        </a>
                    </li>

                    <li>
                        <a href="schedule.php" target="_self">
                            <span class="icon">
                                <ion-icon name="time-outline"></ion-icon>
                            </span>
                            <span class="title"><h3>Works Schedules</h3></span>
                        </a>
                    </li>

                    <li>
                        <a href="department.php" target="_self">
                            <span class="icon">
                                <ion-icon name="book-outline"></ion-icon>
                            </span>
                            <span class="title"><h3>Department</h3></span>
                        </a>
                    </li>
                    <hr>
                    <li>
                        <a href="#" target="_self">
                            <span class="icon">
                                <ion-icon name="mail-outline"></ion-icon>
                            </span>
                            <span class="title"><h3>Message</h3></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" target="_self">
                            <span class="icon">
                                <ion-icon name="settings-outline"></ion-icon>
                            </span>
                            <span class="title"><h3>How to use</h3></span>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php" target="_self">
                            <span class="icon">
                                <ion-icon name="log-out-outline"></ion-icon>
                            </span>
                            <span class="title"><h3>logout</h3></span>
                        </a>
                    </li>
                </ul>
            </div>


            <div class="main">
                <div class="topbar">
                    <div class="toggle">
                        <ion-icon name="menu-sharp"></ion-icon>
                    </div>
                    <div class="user">
                        <img src="assets/image/user-profile-icon-free-vector.jpg" alt="">
                        <span class="user-name">
                            <?php
                                echo $_SESSION['username'];
                            ?>
                        </span>
                    </div>               
                </div>
                <div  class="about">
                    <caption><h2>Dashboard</h2></caption>
                </div>
                <!-- ======================= Cards ================== -->
                <div class="cardBox">
                    <div class="card">
                        <a href="department.php" class="linked">
                            <div>
                                <?php 
                                    require 'database/dbconfig.php';

                                    $query = "SELECT id FROM dept_category ORDER BY id";
                                    $query_run = mysqli_query($connection, $query);

                                    $row = mysqli_num_rows($query_run);

                                    echo'<div id="tableCount1" class="numbers"> ' .$row. '</div>';
                                ?>
                                <div class="cardName">Departments</div>
                            </div>
                        </a>
                        <a href="department.php" class="linked">  
                            <div class="iconBx">
                                <ion-icon name="book-outline"></ion-icon>
                            </div> 
                        </a>     
                    </div>
                    <div class="card">
                        <a  href="register.php" class="linked">
                            <div class="count">
                                <?php 
                                    require 'database/dbconfig.php';

                                    $query = "SELECT id FROM users_data ORDER BY id";
                                    $query_run = mysqli_query($connection, $query);

                                    $row = mysqli_num_rows($query_run);

                                    echo'<div id="tableCount2" class="numbers"> ' .$row. '</div>';
                                ?>
                                <div class="cardName">Total Employees</div>
                            </div>
                        </a>
                        <a  href="register.php" class="linked">
                            <div class="iconBx">
                                <ion-icon name="people-outline"></ion-icon>
                            </div>
                        </a>
                    </div>

                    <div class="card">
                        <a  href="schedule.php" class="linked">
                            <div>
                            <?php 
                                    require 'database/dbconfig.php';

                                    $query = "SELECT id FROM schedules ORDER BY id";
                                    $query_run = mysqli_query($connection, $query);

                                    $row = mysqli_num_rows($query_run);

                                    echo'<div id="tableCount2" class="numbers"> ' .$row. '</div>';
                                ?>
                                <div class="cardName">Schedules</div>
                            </div>
                        </a>    
                        <a  href="Position.php" class="linked">
                            <div class="iconBx">
                                <ion-icon name="time-outline"></ion-icon>
                            </div>
                        </a>    
                    </div>

                    <div class="card">
                        <a  href="Attendance.php" class="linked">
                            <div>
                                <?php 
                                        require 'database/dbconfig.php';

                                        $query = "SELECT id FROM attendance ORDER BY id";
                                        $query_run = mysqli_query($connection, $query);

                                        $row = mysqli_num_rows($query_run);

                                        echo'<div id="tableCount4" class="numbers"> ' .$row. '</div>';
                                    ?>
                                <div class="cardName">Attendance</div>
                            </div>
                        </a>
                        <a  href="Attendance.php" class="linked">
                            <div class="iconBx">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </div>
                        </a>    
                    </div>
                </div>
            </div>
    </div>
</body>
<script src="assets/js/main.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script>
    function printTable() {
        var printContents = document.querySelector('.attendanceView-Em').innerHTML; // Select the table container
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents; // Replace the body with table contents
        window.print(); // Trigger the print dialog
        document.body.innerHTML = originalContents; // Restore original contents
        window.location.reload(); // Refresh the page to restore any JavaScript functionality lost after print
    }

    document.getElementById('searchInput').addEventListener('keyup', function() {
    var searchValue = this.value.toLowerCase(); // Convert search input to lowercase
    var rows = document.querySelectorAll('#departmentTable tr'); // Select all rows in the table body

    // Loop through all table rows
    rows.forEach(function(row) {
        // Get text content of each row and convert it to lowercase
        var rowText = row.textContent.toLowerCase();

        // Check if the row contains the search value
        if (rowText.includes(searchValue)) {
            row.style.display = ''; // Show the row if it matches
        } else {
            row.style.display = 'none'; // Hide the row if it doesn't match
        }
    });
    });
</script>
</html>