<?php 
session_start();
include('includes/database/dbconfig.php');

$connection = mysqli_connect("localhost", "root", "", "rfidattendance_db");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
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
    <link rel="stylesheet" href="ADMIN/assets/css/login.css">
</head>
<body>
    <div class="container">
        <nav>
            <div class="brand-container">
                <img class="logo"src="ADMIN/assets/image//ICLOGO.png" width="50px"/>
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
        <div id="content1" class="content1">
                    <?php
                        if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
                            echo '<h2>' . $_SESSION['status'] . '</h2>';
                            unset($_SESSION['status']);
                        }
                    ?>
                    <div class="addEmployeeForm" id="addEmployeeForm">
                        <h2>Log In</h2>
                        
                        <form action="includes/code.php" method="POST">    
                                <h3>EMPLOYEE LOG IN</h3>
                            <div class="user-details">    
                                <div class="input-box">
                                    <input type="email" placeholder="Name123@gmail.com" id="email" name="email" required>
                                </div>
                                <div class="input-box">
                                    <input type="password"minlength="5" maxlength="13"  placeholder="Enter your Password" id="password" name="password" required>
                                </div>
                            </div>      
                                <div class="button">
                                    <input type="submit" value="Login" name="login_btn">
                                </div>
                        </form>
                        <form class="addEmployeeForm" id="addEmployeeForm">
                            <div class="button">
                                <a class="btn">Don't have an Account? &nbsp</a>
                                <a href="#" class="btn-use" id="myBtn" style="text-decoration: none;"><h4> REGISTER</h4></a>
                            </div>
                        </form>
                    </div> 

                   <img src="ADMIN/assets/image/loginimage.jpg" class="loginimage">   
   
        </div> 
        <div class="content">             
                   <div class="modal" id="myModal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <!-- Your registration form goes here -->
                            <div class="addEmployeeForm" id="addEmployeeForm">
                                <h2 style="color: #D81F26;">NEW EMPLOYEE</h2>
                                
                                <form action="/includes/code.php" method="POST">
                                    <h3>Personal Information</h3>
                                    <div class="user-details">
                                        <div class="input-box">
                                            <label for="rfidNo">RFID Number:</label>
                                            <input type="text" placeholder="Enter your RFID Number" id="rfidNo" name="rfidNo" required>
                                        </div>
                                        <div class="input-box">
                                            <label for="firstName">First Name:</label>
                                            <input type="text" placeholder="Enter your First Name" id="firstName" name="firstName" required>
                                        </div>
                                        <div class="input-box">
                                            <label for="middleName">Middle Name (optional):</label>
                                            <input type="text" placeholder="Enter your Middle Name" id="middleName" name="middleName">
                                        </div>
                                        <div class="input-box">
                                            <label for="lastName">Last Name:</label>
                                            <input type="text" placeholder="Enter your Last Name" id="lastName" name="lastName" required>
                                        </div>
                                        <div class="input-box">
                                            <label for="dob">Date of Birth:</label>
                                            <input type="date"placeholder="Date of Birth" max="2010-12-31" id="dob" name="dob" required>
                                        </div>
                                        <div class="input-box">
                                            <label for="sex">Gender:</label>
                                            <ion-icon name="chevron-down-circle-outline"></ion-icon>
                                            <select id="gender" name="gender" required>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                        <div class="input-box">
                                            <label for="contactNo">Contact Number (optional):</label>
                                            <input type="tel"placeholder="Enter your Contact Number" id="contactNo" name="contactNo">
                                        </div>
                                        <div class="input-box">
                                            <label for="email">Email Address (optional):</label>
                                            <input type="email" placeholder="Enter your Email Address" id="email" name="email">
                                        </div>
                                        <div class="input-box">
                                            <label for="designation">Designation:</label>
                                            <input type="text"placeholder="Designation" id="designation" name="designation" required>
                                        </div>
                                        <div class="input-box">
                                            <label for="department">Department:</label>
                                                <ion-icon name="chevron-down-circle-outline"></ion-icon>
                                                <select id="department" name="department_name" required>
                                                    <option value="" disabled selected>Select Department</option>
                                                    <?php
                                                    // Check if any departments are fetched
                                                    if (mysqli_num_rows($dept_query_run) > 0) {
                                                        while ($row = mysqli_fetch_assoc($dept_query_run)) {
                                                            ?>
                                                            <option value="<?php echo htmlspecialchars($row['id']); ?>">
                                                                <?php echo htmlspecialchars($row['department_name']); ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    } else {
                                                        echo '<option value="" disabled>No Departments Available</option>';
                                                    }
                                                    ?>
                                                </select>

                                        </div>
                                        <div class="input-box">
                                            <label for="address">Complete Address:</label>
                                            <input type="text"placeholder="Complete Address" id="address" name="address" required> 
                                        </div>
                                    </div>    
                                        <h3>Personal Account</h3>
                                    <div class="user-details">    
                                        <div class="input-box">
                                            <label for="username">Username:</label>
                                            <input type="text" placeholder="username" id="username" name="username" required>
                                        </div>
                                        <div class="input-box">
                                            <label for="password">Password:</label>
                                            <input type="password"minlength="5" maxlength="13"  placeholder="Enter your Password" id="password" name="password" required>
                                        </div>
 
                                        <div class="input-box">
                                            <label for="status">Status:</label>
                                            <ion-icon name="chevron-down-circle-outline"></ion-icon>
                                            <select id="status" name="status" required>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="input-box">
                                            <label for="password">Confirm Password:</label>
                                            <input type="password"minlength="5" maxlength="13"  placeholder="Enter your Password" id="password" name="confirmpassword" required>
                                        </div>
                                    </div>      
                                        <div class="button">
                                          <input type="submit" value="Save" name="registerbtn">
                                        </div>
                                      
                                </form>
                            </div>
                        </div>
                    </div>
        </div>
    </div>  
    <script src="assets/js/main.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>             
</body>

</html>
<?php if (isset($error)) { echo '<p>' . htmlspecialchars($error) . '</p>'; } ?>