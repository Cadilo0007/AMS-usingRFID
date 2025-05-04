<?php 
session_start();
include('database/dbconfig.php'); // Ensure the path is correct

// Check database connection
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['login_btn'])) {

    $email_login = trim($_POST['email']);
    $password_login = trim($_POST['password']);

    $query = "SELECT * FROM users_data WHERE email = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $email_login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify hashed password
        if (password_verify($password_login, $row['password'])) {
            $_SESSION['email'] = $email_login;
            header("Location: user.php");
            exit();
        } else {
            $_SESSION['status'] = ''; //email or password if incorrect
            header("Location: ../employeelogin.php");
            exit();
        }
    } else {
        $_SESSION['status'] = '';
        header("Location: ../employeelogin.php");
        exit();
    }
}



if (isset($_POST['accountedit_btn'])) {
    $id = $_POST['edit_id'];
    $rfid_tag = $_POST['edit_rfidNo'];
    $firstname = $_POST['edit_firstName'];
    $middlename = $_POST['edit_middleName'];
    $lastname = $_POST['edit_lastName'];
    $birthdate = $_POST['edit_dob'];
    $gender = $_POST['edit_gender'];
    $number = $_POST['edit_contact'];
    $email = $_POST['edit_email'];
    $designation = $_POST['edit_designation'];
    $department = $_POST['edit_department'];
    $address = $_POST['edit_address'];
    $username = $_POST['edit_username'];
    $password = $_POST['edit_password'];
    $status = $_POST['edit_status'];

    // Prepare the query based on whether the password is provided or not
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users_data SET rfid_tag = ?, firstname = ?, middlename = ?, lastname = ?, birthdate = ?, gender = ?, number = ?, email = ?, designation = ?, department = ?, address = ?, username = ?, password = ?, status = ? WHERE id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ssssssssssssssi", $rfid_tag, $firstname, $middlename, $lastname, $birthdate, $gender, $number, $email, $designation, $department, $address, $username, $hashed_password, $status, $id);
    } else {
        $query = "UPDATE users_data SET rfid_tag = ?, firstname = ?, middlename = ?, lastname = ?, birthdate = ?, gender = ?, number = ?, email = ?, designation = ?, department = ?, address = ?, username = ?, status = ? WHERE id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("sssssssssssssi", $rfid_tag, $firstname, $middlename, $lastname, $birthdate, $gender, $number, $email, $designation, $department, $address, $username, $status, $id);
    }
    if ($stmt->execute()) {
        $_SESSION['success'] = "User updated successfully!";
        header('Location: account.php');
        exit();
    } else {
        $_SESSION['status'] = "Execution failed: " . $stmt->error;
        header('Location: account_edit.php');
        exit();
    }
    // Close statement and connection
    $stmt->close();
    $connection->close();
}

if (isset($_POST['registerbtn'])) {
    $rfidNo = $_POST['rfidNo'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $contact = $_POST['contactNo'];
    $email = $_POST['email'];
    $designation = $_POST['designation'];
    $department = $_POST['department_name'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['confirmpassword'];
    $status = $_POST['status'];

    if ($password === $cpassword) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $connection->prepare("INSERT INTO users_data (rfid_tag, firstname, middlename, lastname, birthdate, gender, number, email, designation, department, address, username, password, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssssss", $rfidNo, $firstName, $middleName, $lastName, $dob, $gender, $contact, $email, $designation, $department, $address, $username, $hashed_password, $status);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Employee Profile Added Successfully";
            header('Location: register.php');
            exit();
        } else {
            $_SESSION['status'] = "Employee Profile Not Added";
            header('Location: register.php');
            exit();
        }
    } else {
        $_SESSION['status'] = "Password and Confirm Password do not match";
        header('Location: register.php');
        exit();
    }
}
?>