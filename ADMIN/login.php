<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adminlogin/RFID Attendance</title>
    <link rel="icon" href="assets/image/ICLOGO.png"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Questrial&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="container">
        <nav>
            <div class="brand-container">
                <img class="logo"src="assets/image/ICLOGO.png" width="50px"/>
                <h2 class="brand-title">RFID Attendance<p style="font-size: small;">Isabela Colleges Inc.</p></h2>
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
                        
                        <form action="code.php" method="POST">    
                                <h3>HELLO ADMIN</h3>
                            <div class="user-details">    
                                <div class="input-box">
                                    <input type="text" placeholder="Username" id="username" name="username" required>
                                </div>
                                <div class="input-box">
                                    <input type="password" minlength="5" maxlength="13" placeholder="Enter your Password" id="password" name="password" required>
                                </div>
                            </div>      
                                <div class="button">
                                    <input type="submit" value="Login" name="login_btn">
                                </div>
                        </form>
                    </div> 

                   <img src="assets/image/loginimage.jpg" class="loginimage">  
        </div> 
    </div>
    <script src="assets/js/main.js"></script> 
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>             
</body>

</html>
<?php if (isset($error)) { echo '<p>' . htmlspecialchars($error) . '</p>'; } ?>
