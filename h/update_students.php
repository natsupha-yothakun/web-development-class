<?php
include 'connectdb.php';

$student = null;
$message = "";

// รับค่า id จาก query string
if (isset($_GET['id'])) {
    $s_id = $_GET['id'];

    // ถ้ามีการ POST เพื่อแก้ไข
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $s_name = $_POST['s_name'];
        $s_address = $_POST['s_address'];
        $s_gpax = $_POST['s_gpax'];
        $f_id = $_POST['f_id'];

        // อัปเดตข้อมูล
        $sql = "UPDATE students SET s_name=?, s_address=?, s_gpax=?, f_id=? WHERE s_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdiss", $s_name, $s_address, $s_gpax, $f_id, $s_id);

        if ($stmt->execute()) {
            $message = "✅ แก้ไขข้อมูลนิสิตเรียบร้อยแล้ว!";
        } else {
            $message = "❌ เกิดข้อผิดพลาด: " . $conn->error;
        }

        $stmt->close();
    }

    // ดึงข้อมูลนิสิตตามรหัส
    $sql = "SELECT * FROM students WHERE s_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $s_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();

    if (!$student) {
        die("❌ ไม่พบนิสิตที่ต้องการแก้ไข");
    }
} else {
    die("❌ ไม่พบพารามิเตอร์ id");
}

// ดึงข้อมูลคณะทั้งหมด
$faculties = [];
$sql = "SELECT * FROM faculty ORDER BY f_name ASC";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $faculties[] = $row;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลนิสิต</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">ฟอร์มแก้ไขข้อมูลนิสิต</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($message)): ?>
                <div class="alert alert-info"><?= $message ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">รหัสนิสิต</label>
                    <input type="text" class="form-control" value="<?= $student['s_id'] ?>" disabled>
                    <input type="hidden" name="s_id" value="<?= $student['s_id'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">ชื่อนิสิต</label>
                    <input type="text" class="form-control" name="s_name" value="<?= htmlspecialchars($student['s_name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">ที่อยู่</label>
                    <textarea class="form-control" name="s_address" rows="3" required><?= htmlspecialchars($student['s_address']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">เกรดเฉลี่ย (GPAX)</label>
                    <input type="number" step="0.01" min="0" max="4" class="form-control" name="s_gpax" value="<?= $student['s_gpax'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">คณะ</label>
                    <select class="form-select" name="f_id" required>
                        <option value="" disabled>-- กรุณาเลือกคณะ --</option>
                        <?php foreach ($faculties as $faculty): ?>
                            <option value="<?= $faculty['f_id'] ?>" <?= ($faculty['f_id'] == $student['f_id']) ? 'selected' : '' ?>>
                                <?= $faculty['f_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                <a href="students_list.php" class="btn btn-secondary">กลับ</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
