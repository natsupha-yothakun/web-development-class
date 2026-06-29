<?php
// Include the database connection file
require_once 'connectdb.php';

$message = '';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $s_id = $_POST['s_id'];
    $s_name = $_POST['s_name'];
    $s_address = $_POST['s_address'];
    $s_gpax = $_POST['s_gpax'];
    $f_id = $_POST['f_id'];

    // SQL statement to check if student ID already exists
    $check_sql = "SELECT s_id FROM students WHERE s_id = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("s", $s_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $message = '<div class="alert alert-danger" role="alert">
                      ไม่สามารถเพิ่มข้อมูลได้ เนื่องจากรหัสนิสิตนี้มีอยู่ในระบบแล้ว!
                    </div>';
    } else {
        // SQL statement to insert data into the students table
        $insert_sql = "INSERT INTO students (s_id, s_name, s_address, s_gpax, f_id) VALUES (?, ?, ?, ?, ?)";
        
        // Prepare and bind the statement
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssdi", $s_id, $s_name, $s_address, $s_gpax, $f_id);
        
        // Execute the statement
        if ($stmt->execute()) {
            $message = '<div class="alert alert-success" role="alert">
                          เพิ่มข้อมูลนิสิตเรียบร้อยแล้ว!
                        </div>';
        } else {
            $message = '<div class="alert alert-danger" role="alert">
                          เกิดข้อผิดพลาดในการเพิ่มข้อมูล: ' . $stmt->error . '
                        </div>';
        }
        $stmt->close();
    }
    $stmt_check->close();
}

// Fetch faculty data from the database
$faculty_sql = "SELECT f_id, f_name FROM faculty ORDER BY f_name ASC";
$result = $conn->query($faculty_sql);
$faculties = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $faculties[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <h1>ณัฐสุภา โยธากุล(บลู)</h1><hr>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลนิสิต</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2 class="text-center mb-4">ฟอร์มเพิ่มข้อมูลนิสิต</h2>
        <?php echo $message; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-3">
                <label for="s_id" class="form-label">รหัสนิสิต</label>
                <input type="text" class="form-control rounded" id="s_id" name="s_id" required>
            </div>
            <div class="mb-3">
                <label for="s_name" class="form-label">ชื่อ-สกุล</label>
                <input type="text" class="form-control rounded" id="s_name" name="s_name" required>
            </div>
            <div class="mb-3">
                <label for="s_address" class="form-label">ที่อยู่</label>
                <textarea class="form-control rounded" id="s_address" name="s_address" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="s_gpax" class="form-label">เกรดเฉลี่ย (GPAX)</label>
                <input type="number" step="0.01" class="form-control rounded" id="s_gpax" name="s_gpax" min="0.00" max="4.00" required>
            </div>
            <div class="mb-3">
                <label for="f_id" class="form-label">คณะ</label>
                <select class="form-select rounded" id="f_id" name="f_id" required>
                    <option value="">เลือกคณะ...</option>
                    <?php foreach ($faculties as $faculty): ?>
                        <option value="<?php echo $faculty['f_id']; ?>"><?php echo htmlspecialchars($faculty['f_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-block rounded">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>