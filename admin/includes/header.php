<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>FurniCraft Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-180x180.png">
    <link rel="icon" sizes="192x192" href="https://furnicraft.techturtle.in/assets/images/favicon/favicon-192x192.png">

    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar a.active {
            background-color: #007bff;
        }
        .main-content {
            padding: 20px;
        }
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            color: #fff;
        }
        .stat-card.primary {
            background-color: #007bff;
        }
        .stat-card.success {
            background-color: #28a745;
        }
        .stat-card.warning {
            background-color: #ffc107;
        }
        .stat-card.info {
            background-color: #17a2b8;
        }
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row"> 