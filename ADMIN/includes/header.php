<!DOCTYPE html>
<html>

<head>
    <title>Administrators</title>
    <link rel="icon" href="assets/image/ICLOGO.png"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Questrial&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="./assets/css/style4.css">
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
        
        .main-footer {
            background-color: #f8f9fa;
            padding: 10px 0;
            text-align: center;
            color: #333;
            border-top: 1px solid #ddd;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        .main {
            flex: 1;
        }

        /* Footer Styles */
        .main-footer {
            z-index: 800;
            position: fixed;
            right: 0;
            display: grid;
            bottom: 0;
            width: 80%;
            height: 25px;
            align-items: center;
            padding: 0 10px;
            background: var(--white);
        }
        .main-footer h5 {
            color: #007bff;
            text-decoration: none;
            text-align: right;
        }
    </style>
</head>
<body>