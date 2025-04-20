<?php
include('../database/connect2.php');
include('../inc/topbar.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];

    // التحقق من صحة الرمز
    $query = "SELECT * FROM tblemployee WHERE reset_token = ? AND reset_token_expiry > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        die("Invalid or expired token.");
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    // تحديث كلمة المرور
    $update_query = "UPDATE tblemployee SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ss", $new_password, $token);

    if ($update_stmt->execute()) {
        echo "Password updated successfully!";
    } else {
        echo "Failed to update password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>
<body class="d-flex flex-column justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="width: 400px;">
        <h3 class="text-center">Reset Password</h3>
        <form action="" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3">Reset Password</button>
        </form>
    </div>
</body>
</html>
