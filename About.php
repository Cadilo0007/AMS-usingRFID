<?php
include('includes/header.php');
?>
<body>
    <div class="container">
        <nav>
            <div class="brand-container">
                <img class="logo"src="ADMIN/assets/image/ICLOGO.png" width="50px"/>
                <h2 class="brand-title">RFID Attendance
                    <p style="font-size: small;">Isabela Colleges Inc.</p>
                </h2>  
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
            <h1 style="font-size: 60px;">"About System"</h1>
            <p>This system utilizes RFID (Radio Frequency Identification) technology for attendance monitoring. It is based on an RFID reader that is built using Arduino microcontroller boards. The RFID reader interacts with RFID cards assigned to individuals. Each person has a unique RFID card which they use to log their attendance.

                The RFID reader is connected to a computer or a microcontroller system where attendance data is processed. When an RFID card is scanned, the reader detects the unique ID stored on the card. This information is then logged into the system, recording the time and the person associated with the RFID card.
                
                Employees or individuals simply need to tap their RFID card on the reader upon entering or leaving the premises. The system automatically registers their attendance, eliminating the need for manual attendance taking.
                
                It simplifies attendance management processes and provides accurate records of entry and exit times.
            </p> 
        </div>
        <div id="content2" class="content2">
            <img src="ADMIN/assets/image/RFIDarduino.jpg" class="feature-img" style="width: 90%;"/>
        </div>
    </div>    
    <script src="ADMIN/assets/js/main.js"></script>  

</body>

</html>