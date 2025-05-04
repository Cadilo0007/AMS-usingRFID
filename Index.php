<?php
include('includes/header.php');
?>
<body>
    <div class="container">
        <nav>
            <div class="brand-container">
                <a>
                    <img class="logo"src="ADMIN/assets/image/ICLOGO.png" width="50px"/>
                    <h2 class="brand-title">RFID Attendance<p style="font-size: small;">Isabela Colleges Inc.</p></h2>
                </a>   
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
            <h1>"Time plays a vital role in our lives."</h1>
            <p>Time is important every day in whatever we do, so we should always appreciate and make the most of it.</p>
            <a href="#" class="btn-use" id="myBtn">How to Used</a>
        </div>
    
        <div id="content2" class="content2">
            <img src="ADMIN/assets/image/schedule.png" class="feature-img"/>
        </div>
        
        <!-- Separate registration form -->
        <div class="modal" id="myModal">
            <div class="modal-content">
                <span class="close">&times;</span>
                    <!-- Your registration form goes here -->
                <div class="addEmployeeForm" id="Form">
                    <h2 style="color: #D81F26;">HOW TO USED</h2>
                            
                    <form>
                        <h3>Guide how to used</h3>
                        <p style="color: #D81F26; font-size: 20px;">RFID Card:</p>
                        <p>
                            - Each individual is assigned a unique RFID card.<br>
                            - Ensure that every person who will be using the system has their own RFID card.<br>
                            - To log attendance, individuals simply need to tap their RFID card on the RFID reader upon entering or leaving the premises.<br>
                            - Make sure individuals tap their cards individually and not in batches to avoid errors.
                        </p> 
                        <p style="color: #D81F26; font-size: 20px;">RFID Reader:</p>
                        <p>
                            - Place the RFID reader in a convenient location where individuals can easily tap their RFID cards. <br>
                            - Connect the RFID reader to a computer or microcontroller system according to the provided instructions.
                        </p>
                        <p style="color: #D81F26; font-size: 20px;">Attendance Logging:</p>
                        <p>
                            - When an RFID card is scanned, the reader detects the unique ID stored on the card and logs it into the system.<br>
                            - The system records the time of entry or exit along with the ID of the cardholder.
                        </p>
                        <p style="color: #D81F26; font-size: 20px;">Review Attendance Records:                           <p>
                            - Administrators can access attendance records from the system's interface.<br>
                            - Use the provided software or interface to view attendance logs, generate reports, etc.
                        </p>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
    <script src="ADMIN/assets/js/main.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>       
</body>

</html>