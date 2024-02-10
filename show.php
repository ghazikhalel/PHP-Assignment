<!DOCTYPE html>
<html lang="en">
<head>
  <title>قائمة المشتركين</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-3">
  <h2>قائمة المشتركين</h2>
  <br><br>
  <style>
    .custom-table {
      border-spacing: 30px; /* إضافة مسافة بين الخلايا */
    }
  </style>
      <!-- انشاء تيبل -->
  <table class="table table-bordered custom-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $file = "contacts.txt";
      // قراءة محتوى الملف   
      $jsonData = file_get_contents($file);
      $data = json_decode($jsonData, true);

      // فحص إذا كان في محتوى 
      if ($data != null) {
        foreach ($data as $contact) {
            //وحذف الفراغات الطويلة
          echo '<tr>';
          echo '<td>' . htmlspecialchars($contact['name']) . '</td>';
          echo '<td>' . htmlspecialchars($contact['email']) . '</td>';
          echo '<td>' . htmlspecialchars($contact['phone']) . '</td>';
          echo '</tr>';
        }
      } 
      //ما اشتغل معي
      else {
        echo 'No contacts found.';
      }
      ?>
    </tbody>
  </table>

  <!-- زر البحث -->
  <div class="input-group mb-3">
    <input type="text" id="searchName" class="form-control" placeholder="اسم الشخص">
    <button class="btn btn-primary" onclick="searchByName()">ابحث</button>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    //دالة البحث
  function searchByName() {
    // جيب  الاسم الي تم إدخاله
    var nameToSearch = document.getElementById('searchName').value.toLowerCase();

    // جيب الصف  
    var rows = document.querySelectorAll('table tbody tr');

    // اخفِ كل الصفوف اول الشي
    for (var i = 0; i < rows.length; i++) {
      rows[i].style.display = 'none';
    }

    //  اظهر الصف الي بيحوي على الاسم المبحوث عنه
    for (var i = 0; i < rows.length; i++) {
      var nameCell = rows[i].getElementsByTagName('td')[0]; // الخلية التي تحتوي على الاسم
      if (nameCell) {
        //استخراج الاسم باحرف صغيرة
        var name = nameCell.textContent.toLowerCase();
        //اذا الاسم المبحوث عنه من مضمون البحث اعرضه
        if (name.includes(nameToSearch)) {
          rows[i].style.display = '';
        }
      }
    }
  }
</script>
</body>
</html>
