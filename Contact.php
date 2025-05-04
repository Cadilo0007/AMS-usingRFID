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
        <div id="content1" class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3816.7595244205377!2d121.76916757492393!3d16.937150283875198!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x338551ac712c4ed5%3A0x30b549bc887377e5!2sIsabela%20Colleges%2C%20Inc.!5e0!3m2!1sen!2sph!4v1718337560602!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" alt="error"></iframe>     
        </div>
        <div id="content2" class="contact-form"> 
            <h2>Contact Us</h2>
        
            <form action="#" method="post">
                <div class="form-group">
                    <label for="name">Name(optional)</label>
                    <input type="text" id="name" name="name" placeholder="Your Name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Your Email" required>
                </div>
                <div class="form-group">
                    <label for="contact-number">Contact Number (optional)</label>
                    <input type="tel" id="contact-number" name="contact-number" placeholder="Your Contact Number" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="4" placeholder="Message Here..." required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div> 
    </div>    
    <script src="ADMIN/assets/js/main.js"></script>   
</body>
</html>
