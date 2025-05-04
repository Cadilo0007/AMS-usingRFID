<?php
session_start();

if (isset($_SESSION['status'])) {
    echo "<div class='alert alert-danger'>" . $_SESSION['status'] . "</div>";
    unset($_SESSION['status']);  // Clear the session message after showing it
}

include('database/dbconfig.php');
// Check connection
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Run this part only once to hash plain text passwords
$query = "SELECT id, password FROM accounts";
$result = mysqli_query($connection, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $plainPassword = $row['password'];

    // Check if the password is already hashed (bcrypt hashes start with "$2y$")
    if (substr($plainPassword, 0, 4) !== '$2y$') {
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);  // Hash the password

        // Update the database with the hashed password
        $updateQuery = "UPDATE accounts SET password = ? WHERE id = ?";
        $stmt = $connection->prepare($updateQuery);
        $stmt->bind_param("si", $hashedPassword, $row['id']);  // "si" means string, integer
        $stmt->execute();
    }
}

// Handle login
if (isset($_POST['login_btn'])) {

    // Get the input values and trim them
    $username_login = trim($_POST['username']);
    $password_login = trim($_POST['password']);

    // Prepare the SQL statement to avoid SQL injection
    $query = "SELECT * FROM accounts WHERE username = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $username_login);  // "s" means the parameter is a string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password using password_verify
        if (password_verify($password_login, $row['password'])) {
            // Password is correct, start session and redirect to index.php
            $_SESSION['username'] = $username_login;
            header("Location: index.php");
            exit();  // Ensure the script stops executing after redirect
        } else {
            // Invalid password
            $_SESSION['status'] = 'Invalid password';
            header("Location: login.php");
            exit();
        }
    } else {
        // Username doesn't exist
        $_SESSION['status'] = 'Invalid username';
        header("Location: login.php");
        exit();
    }
}


//REGISTER-----
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
//include('database/dbconfig.php'); // Include your database connection

if (isset($_POST['updateuser_btn'])) {
    $id = $_POST['edit_id'];
    $rfidNo = $_POST['edit_rfidNo'];
    $firstName = $_POST['edit_firstName'];
    $middleName = $_POST['edit_middleName'];
    $lastName = $_POST['edit_lastName'];
    $dob = $_POST['edit_dob'];
    $gender = $_POST['edit_gender'];
    $contact = $_POST['edit_contact'];
    $email = $_POST['edit_email'];
    $designation = $_POST['edit_designation'];
    $department = $_POST['edit_department'];
    $address = $_POST['edit_address'];
    $username = $_POST['edit_username'];
    $password = $_POST['edit_password'];
    $status = $_POST['edit_status'];

    $hashed_password = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

    $query = "UPDATE users_data SET rfid_tag=?, firstname=?, middlename=?, lastname=?, birthdate=?, gender=?, number=?, email=?, designation=?, department=?, address=?, username=?, status=?";
    if ($hashed_password) {
        $query .= ", password=?";
    }
    $query .= " WHERE id=?";

    $stmt = $connection->prepare($query);

    if ($hashed_password) {
        $stmt->bind_param("ssssssssssssssi", $rfidNo, $firstName, $middleName, $lastName, $dob, $gender, $contact, $email, $designation, $department, $address, $username, $status, $hashed_password, $id);
    } else {
        $stmt->bind_param("sssssssssssssi", $rfidNo, $firstName, $middleName, $lastName, $dob, $gender, $contact, $email, $designation, $department, $address, $username, $status, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "User updated successfully!";
        header('Location: register.php');
        exit();
    } else {
        $_SESSION['status'] = "Execution failed: " . $stmt->error;
        header('Location: register_edit.php');
        exit();
    }
}

//register.php delete_btn

if(isset($_POST['deleteuser_btn']))
{
    $id = $_POST['deleteuser_id'];

    // Correct table name should be used here
    $query = "DELETE FROM users_data WHERE id='$id' ";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['success'] = "Your Data is DELETED";
        header('location: register.php');
    }
    else
    {
        $_SESSION['status'] = "Your Data is NOT DELETED";
        header('location: register.php');
        exit();
    }
}

//Department
if (isset($_POST['dept_save'])) {
    include 'database/dbconfig.php'; // Include your database connection

    $name = $_POST['deptname'];
    $abbreviation = $_POST['deptabbr'];

    // Handle file upload
    $image = $_FILES['dept_image']['name'];
    $imageTempName = $_FILES['dept_image']['tmp_name'];
    $imageFolder = 'upload/department/' . basename($image);

    // Validate file
    $allowed_types = ['image/jpeg', 'image/png'];

    if ($image) {
        // Check if the image upload is successful
        if (in_array($_FILES['dept_image']['type'], $allowed_types) && $_FILES['dept_image']['size'] <= 500000) {
            if (move_uploaded_file($imageTempName, $imageFolder)) {
                // Insert into database with image
                $query = "INSERT INTO dept_category (department_name, abbreviation, image) VALUES (?, ?, ?)";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("sss", $name, $abbreviation, $image);
            } else {
                $_SESSION['status'] = "Failed to upload file.";
                header('Location: department.php');
                exit();
            }
        } else {
            $_SESSION['status'] = "Invalid file type or size.";
            header('Location: department.php');
            exit();
        }
    } else {
        // Insert into database without image
        $query = "INSERT INTO dept_category (department_name, abbreviation) VALUES (?, ?)";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ss", $name, $abbreviation);
    }

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['success'] = "Department added successfully.";
    } else {
        $_SESSION['status'] = "Error: " . $stmt->error;
    }

    // Close resources
    $stmt->close();
    $connection->close();
    header('Location: department.php');
    exit();
}


if (isset($_POST['deletedept_btn'])) {
// Include your database connection

    $id = $_POST['deletedept_id'];

    // Delete from database
    $query = "DELETE FROM dept_category WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        $_SESSION['success'] = "Your Data is DELETED";
        header('Location: department.php');
    } else {
        $_SESSION['status'] = "Your Data is NOT DELETED";
        header('Location: department.php');
        exit();
    }
}


if (isset($_POST['update_dept'])) {
    $id = $_POST['edit_id']; // Assuming you pass the id through the form
    $deptName = $_POST['deptname'];
    $deptAbbr = $_POST['deptabbr'];

    // Handle file upload
    $image = $_FILES['dept_image']['name'];
    $imageTempName = $_FILES['dept_image']['tmp_name'];
    $imageFolder = 'upload/department/' . basename($image); // Use basename() for security

    $imageUploaded = false;

    if ($image) {
        // Validate file type and size
        $allowed_types = ['image/jpeg', 'image/png'];
        if (in_array($_FILES['dept_image']['type'], $allowed_types) && $_FILES['dept_image']['size'] <= 500000) {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($imageTempName, $imageFolder)) {
                $imageUploaded = true;
            } else {
                $_SESSION['status'] = "Failed to upload file.";
                header('Location: department.php');
                exit();
            }
        } else {
            $_SESSION['status'] = "Invalid file type or size.";
            header('Location: department.php');
            exit();
        }
    }

    // Prepare the query
    if ($imageUploaded) {
        $query = "UPDATE dept_category SET department_name=?, abbreviation=?, image=? WHERE id=?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("sssi", $deptName, $deptAbbr, $image, $id);
    } else {
        $query = "UPDATE dept_category SET department_name=?, abbreviation=? WHERE id=?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ssi", $deptName, $deptAbbr, $id);
    }

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['status'] = 'Department updated successfully!';
        header('Location: department.php');
    } else {
        $_SESSION['status'] = 'Error: ' . $stmt->error;
        header('Location: department.php');
    }

    // Close resources
    $stmt->close();
    $connection->close();
}
//Schedules
if (isset($_POST['schedulebtn'])) {
    // Fetching form data
    $from = $_POST['from'];
    $to = $_POST['to'];
    $empname = $_POST['empname'];

    // Connect to database
    $connection = mysqli_connect("localhost", "root", "", "rfidattendance_db");

    if ($connection) {
        // Insert the new schedule into the schedules table
        $query = "INSERT INTO schedules (user_id, from_time, to_time) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($connection, $query);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "iss", $empname, $from, $to);

        // Execute the query
        if ($stmt->execute()) {
            $_SESSION['success'] = "Successfully Added!";
            header('Location: schedule.php');
            exit();
        } else {
            $_SESSION['status'] = "Execution failed: " . $stmt->error;
            header('Location: schedule.php');
            exit();
        }

        // Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($connection);
    } else {
        die("Database connection failed: " . mysqli_connect_error());
    }
}



if (isset($_POST['deletesched_btn'])) {
    // Get the ID of the schedule to delete
    $delete_id = $_POST['delete_id'];

    // Database connection
    $connection = mysqli_connect("localhost", "root", "", "rfidattendance_db");

    // Check connection
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the delete statement
    $stmt = $connection->prepare("DELETE FROM schedules WHERE id = ?");

    // Check if the prepare statement was successful
    if ($stmt === false) {
        die("Prepare failed: " . $connection->error);
    }

    // Bind the parameter
    $stmt->bind_param("i", $delete_id);

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['success'] = "Schedule deleted successfully.";
    } else {
        $_SESSION['status'] = "Failed to delete schedule: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    mysqli_close($connection);

    // Redirect to the schedules page or desired location
    header('Location: schedule.php'); // Change this to the appropriate page
    exit();
}

include('ADMIN/database/dbconfig.php');
// Check connection
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>
