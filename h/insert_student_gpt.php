<?php
include 'connectdb.php';

// ถ้ามีการส่งข้อมูลฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $s_id = $_POST['s_id'];
    $s_name = $_POST['s_name'];
    $s_address = $_POST['s_address'];
    $s_gpax = $_POST['s_gpax'];
    $f_id = $_POST['f_id'];

    // เพิ่มข้อมูลนิสิต
    $sql = "INSERT INTO students (s_id, s_name, s_address, s_gpax, f_id) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdi", $s_id, $s_name, $s_address, $s_gpax, $f_id);

    if ($stmt->execute()) {
        $message = "✅ เพิ่มข้อมูลนิสิตเรียบร้อยแล้ว!";
    } else {
        $message = "❌ เกิดข้อผิดพลาด: " . $conn->error;
    }

    $stmt->close();
}

// ดึงข้อมูลคณะ
$faculties = [];
$sql = "SELECT * FROM faculty ORDER BY f_name ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $faculties[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <h1>ณัฐสุภา โยธากุล(บลู)</h1><hr>
    <title>เพิ่มข้อมูลนิสิต</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">ฟอร์มเพิ่มข้อมูลนิสิต</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($message)): ?>
                <div class="alert alert-info"><?= $message ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label for="s_id" class="form-label">รหัสนิสิต</label>
                    <input type="text" class="form-control" id="s_id" name="s_id" required>
                </div>

                <div class="mb-3">
                    <label for="s_name" class="form-label">ชื่อนิสิต</label>
                    <input type="text" class="form-control" id="s_name" name="s_name" required>
                </div>

                <div class="mb-3">
                    <label for="s_address" class="form-label">ที่อยู่</label>
                    <textarea class="form-control" id="s_address" name="s_address" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="s_gpax" class="form-label">เกรดเฉลี่ย (GPAX)</label>
                    <input type="number" step="0.01" min="0" max="4" class="form-control" id="s_gpax" name="s_gpax" required>
                </div>

                <div class="mb-3">
                    <label for="f_id" class="form-label">คณะ</label>
                    <select class="form-select" id="f_id" name="f_id" required>
                        <option value="" selected disabled>-- กรุณาเลือกคณะ --</option>
                        <?php foreach ($faculties as $faculty): ?>
                            <option value="<?= $faculty['f_id'] ?>"><?= $faculty['f_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">บันทึกข้อมูล</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
