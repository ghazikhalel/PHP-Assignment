<?php
// تمثيل جهة الاتصال
class Contact
{
    private $name;
    private $email;
    private $phone;

    //   تابع باني 
    public function __construct($name, $email, $phone)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }

    // دوال  جيتر
    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhone()
    {
        return $this->phone;
    }
}

// تعريف الكلاس  لإدارة  
class ContactManager
{
    //[جهات الاتصال]
    private $contacts = [];

    // إضافة جهة اتصال جديدة
    public function addContact(Contact $contact)
    {
        $this->contacts[] = $contact;
    }

    // الحصول على جميع جهات الاتصال
    public function getAllContacts()
    {
        return $this->contacts;
    }
}

// للتعامل مع الملف 
class FileHandler
{
    //تعريف ملف
    private $filename;
     
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    // حفظ جهات الاتصال في الملف
    public function saveContactsToFile($contacts)
    {
        //البيانات
        $data = [];
          //بتمرق على كل جهة اتصال وبتجيبو بالجيت
        foreach ($contacts as $contact) {
            $data[] = [
                'name' => $contact->getName(),
                'email' => $contact->getEmail(),
                'phone' => $contact->getPhone()
            ];
        }
         //تخزين فالملف
        $json = json_encode($data);
         // تنسيق غير صحيح او في مشكلة فالاذونات مثلا
        if ($json === false) {
            throw new Exception('Error encoding contacts to JSON.');
        }

        if (file_put_contents($this->filename, $json) === false) {
            throw new Exception('Error saving contacts to file.');
        }
    }

    // قراءة جهات الاتصال من الملف
    public function readContactsFromFile()
    {
        //اذا الملف غير موجود برجع مصفوفة فاضي
        if (!file_exists($this->filename)) {
            return [];
        }

        $json = file_get_contents($this->filename);
         //اذا قراءة الملف فشلت
        if ($json === false) {
            throw new Exception('Error reading contacts from file.');
        }

        $data = json_decode($json, true);
        //فشل في فهم النص 
        if ($data === null) {
            throw new Exception('Error decoding contacts.');
        }

        return $data;
    }
}

// اسم ملف البيانات
$filename = 'contacts.txt';

// إنشاء  كائنين    
$contactManager = new ContactManager();
$fileHandler = new FileHandler($filename);

//بيقرأ جهة الاتصال من الملف وبضيفها فالكائن الجديد 
try {
    $data = $fileHandler->readContactsFromFile();
     //كرمال تمرق على الاسم والبريد والهاتف
    foreach ($data as $contactData) {
        $contact = new Contact($contactData['name'], $contactData['email'], $contactData['phone']);
        $contactManager->addContact($contact);
    }
} catch (Exception $e) {
    echo 'An error occurred while reading contacts: ' . $e->getMessage();
}

// مستخدمين بوست هون 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // فحص البيانات المدخلة ان كانت فارغة واستخدمت رسالة توضيحية باللون الاحمر
    if (empty($name) || empty($email) || empty($phone)) {
        echo '<div class="alert alert-danger" role="alert">
        <strong>Error!</strong> Please fill in all fields.
      </div>';
    } 
     //الفلترة لازم يحوي على @ ويكون مقبول
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email address.';
    } else {
        $existingContacts = $contactManager->getAllContacts();

        // للتحقق من عدم وجود بريد إلكتروني مكرر
        foreach ($existingContacts as $existingContact) {
            if ($existingContact->getEmail() === $email) {
                echo 'Email address already exists.';
                exit;
            }
        }

        //  إضافة جهة اتصال جديدة اذا مافي بريد مكرر  
        $contact = new Contact($name, $email, $phone);
        $contactManager->addContact($contact);

        try {
            // حفظ البيانات  في الملف واضافة رسالة توضيحية باللون الاخضر يعني تمام
            $fileHandler->saveContactsToFile($contactManager->getAllContacts());
            echo '<div id="alert-message" class="alert alert-success">
        <strong>Success!</strong> Contact saved successfully.
      </div>';

        } catch (Exception $e) {
            echo 'An error occurred while saving the contact: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
     <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <div class="container-fluid">
        <a class="navbar-brand" href="javascript:void(0)">غازي خليل </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
          <span class="navbar-toggler-icon"></span>
        </div>
        </nav>
        <br>
        <h2>wellcom to my website ☺♥☺</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="mb-3 mt-3">
                <label for="name" class="form-label" required>Name:</label>
                <input type="text" class="form-control" id="name" placeholder="Enter name" name="name">
            </div>
            <div class="mb-3">
                <label   class="form-label" required>Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label" required>Phone:</label>
                <input type="text" class="form-control" id="phone" placeholder="Enter phone" name="phone">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <br>
        <!--هون كرمال يعرض جهات الاتصال خليتو يشير لملف show-->

        <a href="show.php" class="btn btn-success" target="_blank">Show me all accounts</a>
    </div>
    <script>
        // اخفاء الرسالة بعد ثانيتين
        function hideAlertMessage() {
            var alertMessage = document.getElementById('alert-message');
            if (alertMessage) {
                setTimeout(function() {
                    alertMessage.style.display = 'none';
                }, 2000);
            }
        }
        // استدعاء عند تحميل الصفحة
        window.onload = hideAlertMessage;
    </script>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
