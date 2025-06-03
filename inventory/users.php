<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
        }
        .form-container {
            background: #fff;
            width: 350px;
            margin: 40px auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin-top: 12px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 18px;
            padding: 10px;
            width: 100%;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
        }
        .switch-link {
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Register User</h2>
    <form method="POST" action="register.php">
        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Role:</label>
        <input type="text" name="role" required>

        <button type="submit">Register</button>
    </form>
</div>

<div class="form-container">
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>


