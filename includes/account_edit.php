<?php
session_start();

include('assets/header.php');
include('assets/sidebar.php');
include('database/dbconfig.php');

$connection = mysqli_connect("localhost", "root", "", "rfidattendance_db");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['accountedit_btn'])) {
    $id = $_POST['edit_id'];

    // Use prepared statements to prevent SQL injection
    $query = $connection->prepare("SELECT * FROM users_data WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();

    // Fetch departments
    $dept_query = "SELECT id, department_name FROM dept_category";
    $dept_query_run = mysqli_query($connection, $dept_query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Account</title>
    <link rel="icon" href="assets/image/ICLOGO.png"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Questrial&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../ADMIN/assets/css/style4.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-sharp"></ion-icon>
                </div>

                <div class="user">
                    <span class="icon">
                        <ion-icon name="person-outline"></ion-icon>
                    </span>
                    <span class="title">
                        <?php
                            echo htmlspecialchars($_SESSION['email']);
                        ?>
                    </span>
                </div>
            </div>
            <div class="detailstwo">
              <div class="attendanceView-Em">
                <div class="cardHeader">
                    <caption><h2>EDIT Employees</h2></caption>
                </div>
                <div>
                  <?php if (isset($row)): ?>
                    <form action="code.php" method="POST">
                        <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($row['id']); ?>">

                      <div class="" style="margin: 20px;">
                        <label for="rfid_tag">RFID Number:</label>
                        <input type="text" id="rfid_tag" name="edit_rfidNo" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['rfid_tag']); ?>">
                      </div>
                      <div class="" style="margin: 20px;">
                          <label for="firstName">First Name:</label>
                          <input type="text" id="firstName" name="edit_firstName" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['firstname']); ?>">
                      </div>
                      <div class="" style="margin: 20px;">
                          <label for="middleName">Middle Name:</label>
                          <input type="text" id="middleName" name="edit_middleName" style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['middlename']); ?>">
                      </div>
                      <div class="" style="margin: 20px;">
                          <label for="lastName">Last Name:</label>
                          <input type="text" id="lastName" name="edit_lastName" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['lastname']); ?>">
                      </div>
                      <div class="" style="margin: 20px;">
                          <label for="dob">Date of Birth:</label>
                          <input type="date" id="dob" name="edit_dob" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['birthdate']); ?>">
                      </div>
                      <div class="" style="margin: 20px;">
                          <label for="sex">Gender:</label>
                          <select id="gender" name="edit_gender" required style="width: 100%; padding:15px;">
                              <option value="male" <?php if ($row['gender'] == 'male') echo 'selected'; ?>>Male</option>
                              <option value="female" <?php if ($row['gender'] == 'female') echo 'selected'; ?>>Female</option>
                          </select>
                      </div>
                      <div class="" style="margin: 20px;">
                          <label for="contactNo">Contact Number:</label>
                          <input type="tel" id="contactNo" name="edit_contact" style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['number']); ?>">
                      </div>
                      <div class="" style="margin: 20px;">
                          <label for="email">Email Address:</label>
                          <input type="email" id="email" name="edit_email" style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['email']); ?>">
                      </div>
                      <div class="" style="margin: 20px;">
                          <label for="designation">Designation:</label>
                          <input type="text" id="designation" name="edit_designation" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['designation']); ?>">
                      </div>
                      <div style="margin: 20px;">
                          <label for="department">Department:</label>
                          <select id="department" name="edit_department" required style="width: 100%; padding:15px;">
                              <option value="" disabled>Select Department</option>
                              <?php 
                              // Check if the department query was successful
                              if (mysqli_num_rows($dept_query_run) > 0) {
                                  while ($dept_row = mysqli_fetch_assoc($dept_query_run)) {
                                      $selected = ($row['department'] == $dept_row['id']) ? 'selected' : ''; // Check if the department matches user's department
                                      echo '<option value="'.htmlspecialchars($dept_row['id']).'" '.$selected.'>'.htmlspecialchars($dept_row['department_name']).'</option>';
                                  }
                              } else {
                                  echo '<option value="">No departments available</option>';
                              }
                              ?>
                          </select>
                      </div>
                      <div class="" style="margin: 20px;">
                          <label for="address">Complete Address:</label>
                          <input type="text" id="address" name="edit_address" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['address']); ?>"> 
                      </div>
                      <h3>Personal Account</h3>
                      <div class="" style="margin: 15px;">
                          <div class="input-box">
                              <label for="username">Username:</label>
                              <input type="text" id="username" name="edit_username" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['username']); ?>">
                          </div>
                      </div>
                      <div class="" style="margin: 20px;">
                          <label for="password">Password:</label>
                          <input type="password" id="password" name="edit_password" style="width: 100%; padding:15px;" placeholder="Enter new password (if changing)">
                      </div>
                      <div class="" style="margin: 20px;">
                          <label for="status">Status:</label>
                          <select id="status" name="edit_status" required style="width: 100%; padding:15px;">
                              <option value="active" <?php if ($row['status'] == 'active') echo 'selected'; ?>>Active</option>
                              <option value="inactive" <?php if ($row['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                          </select>
                      </div>
                      <div class="button">
                        <a href="account.php" class="status delete" style="text-decoration: none;">CANCEL</a>
                        <button type="submit" name="accountedit_btn" class="status breakin">UPDATE</button> 
                      </div>
                    </form>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
</body>
<script src="../ADMIN/assets/js/main.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</html>
