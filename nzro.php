<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SweetAlert2 in PHP Example</title>
    <!-- Include SweetAlert2 from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <?php
    // Check if the form is submitted
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Simulate a login process
        if ($username == 'admin' && $password == 'password123') {
            // Login is successful, show SweetAlert2 success message
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Welcome back, $username!',
                        confirmButtonText: 'OK'
                    });
                  </script>";
        } else {
            // Login failed, show SweetAlert2 error message
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: 'Invalid username or password.',
                        confirmButtonText: 'Try Again'
                    });
                  </script>";
        }
    }
    ?>


    <!-- Simple Login Form -->
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit" name="submit">Login</button>
    </form>

</body>
</html>
