<?php 
// Include config file
include 'config.php';
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Retrieve the hashed password and role from the database
    $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword, $role);
        $stmt->fetch();

        // Verify the password
        if (password_verify($pass, $hashedPassword)) {
            // Store user information and role in the session
            $_SESSION['username'] = $user;
            $_SESSION['role'] = $role;

            // Redirect based on the role
            if ($role == 'admin') {
                header("Location: index.php");
            } elseif ($role == 'editor') {
                header("Location: index.php");
            }
            exit(); // Ensure no further code is executed
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }
        .card-custom {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #007bff;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .text-custom {
            color: #007bff;
        }
        .form-control-custom {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            color: #495057;
        }
        .form-control-custom:focus {
            border-color: #007bff;
            box-shadow: none;
        }
        a {
            text-decoration: none;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card-custom rounded-4 p-4">
            <div class="card-body text-center">
                <h2 class="card-title mb-4 text-custom">Login</h2>
                <form method="POST">
                    <div class="mb-3 text-start">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" class="form-control form-control-custom" id="username" name="username" placeholder="Enter Your Username" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control form-control-custom" id="password" name="password" placeholder="Enter Your Password" required>
                    </div>
                    <button class="btn btn-custom w-100 py-2 text-white" type="submit">Log In</button>
                    <p class="mt-3">Don't Have An Account? <a class="text-custom" href="register.php">Register Here</a></p>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
