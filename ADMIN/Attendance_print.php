<?php
// Establish database connection
$connection = mysqli_connect("localhost", "root", "", "rfidattendance_db");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch attendance data
$query = "
    SELECT 
        attendance.id, 
        users_data.rfid_tag, 
        CONCAT(users_data.firstname, ' ', users_data.lastname) AS name, 
        attendance.date, 
        attendance.time_in, 
        attendance.lunch_start, 
        attendance.lunch_end, 
        attendance.time_out
    FROM 
        attendance
    LEFT JOIN 
        users_data ON attendance.user_id = users_data.id
";
$result = mysqli_query($connection, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Print</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <style>
        @media print {
            body {
                font-family: 'Source Sans Pro', sans-serif;
                margin: 0;
                padding: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            th, td {
                padding: 10px;
                border: 1px solid #000;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            @page {
                margin: 20mm;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Print</title>
    <link rel="icon" href="assets/image/ICLOGO.png"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Questrial&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        @media print {
            body {
                font-family: 'Source Sans Pro', sans-serif;
                margin: 0;
                padding: 20px;
            }
            h1 {
              text-align: center;
              font-size: large;
            }
            hr {
              margin: 1px solid #730000;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            th, td {
                padding: 10px;
                border: 1px solid #000;
                text-align: left;
                font-size: small;
            }
            th {
                background-color: #f2f2f2;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            @page {
                margin: 10mm;
            }
            .no-print {
                display: none;
            }
            .print-footer {
                display: block;
                text-align: center;
                position: fixed;
                bottom: 0;
                width: 100%;
                border-top: 1px solid #000;
                padding: 5px 0;
                font-size: 12px;
            }
        }
    </style>

</head>
<body>
    <h1>Attendance Report</h1>
     <hr>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>RFID Tag</th>
                <th>Date</th>
                <th>Time In</th>
                <th>Lunch Start</th>
                <th>Lunch End</th>
                <th>Time Out</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['rfid_tag']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars(date('g:i A', strtotime($row['time_in']))); ?></td>
                    <td><?php echo htmlspecialchars(date('g:i A', strtotime($row['lunch_start']))); ?></td>
                    <td><?php echo htmlspecialchars(date('g:i A', strtotime($row['lunch_end']))); ?></td>
                    <td><?php echo htmlspecialchars(date('g:i A', strtotime($row['time_out']))); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <script>
        window.print();
    </script>
</body>

<?php
mysqli_close($connection);
?>
