<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Commerce Admin</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        /* Main admin layout */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            background: #2f2f2f;
            color: #fff;
            padding: 20px;
            flex-shrink: 0;
        }

        .sidebar h3 {
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            padding: 10px;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 6px;
        }

        .sidebar a:hover {
            background: #444;
        }

        .sidebar .logout {
            color: #ff6b6b;
        }

        /* Content area */
        .content {
            flex: 1;
            padding: 25px;
            background: #fff;
        }

        .page-header {
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
