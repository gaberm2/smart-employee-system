<?php
include('../database/connect2.php');
include('../inc/topbar.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // التحقق من وجود البريد الإلكتروني
    $query = "SELECT * FROM tblemployee WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // إنشاء رمز إعادة تعيين كلمة المرور
        $reset_token = bin2hex(random_bytes(32));
        $expiry_time = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // حفظ الرمز في قاعدة البيانات
        $update_query = "UPDATE tblemployee SET reset_token = ?, reset_token_expiry = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sss", $reset_token, $expiry_time, $email);
        $update_stmt->execute();

        // إرسال الرابط عبر البريد الإلكتروني
        $to = "your-email@example.com";
$subject = "ahjbs30@gmail.com";
$message = "ahjbs30@gmail.com.";
$headers = "From: no-reply@yourwebsite.com";

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}
    } else {
        echo "Email address not found.";
    }
}
?>
