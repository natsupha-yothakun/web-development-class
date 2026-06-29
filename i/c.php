<?php
// 1. **PHP: การตั้งค่าและการดึงข้อมูล**

// กำหนดข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "Neen@0981410093";
$dbname = "computer_shop";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . ". ตรวจสอบการตั้งค่าการเชื่อมต่อฐานข้อมูล");
}

// SQL Query: คำนวณยอดขายรวมตามชื่อสินค้า (ไม่มี ORDER BY)
$sql = "SELECT 
            p.name AS product_category_name,
            SUM(od.quantity * p.price) AS total_revenue
        FROM 
            order_details od
        JOIN 
            products p ON od.product_id = p.product_id
        GROUP BY 
            p.name"; 
// ***ไม่มี ORDER BY ในคำสั่งนี้แล้ว***

$result = $conn->query($sql);

$labels = []; // ชื่อสินค้า
$data = [];   // ยอดขายรวม

// ชุดสีสำหรับกราฟโดนัท
$backgroundColors = [
    '#FF6384', 
    '#36A2EB', 
    '#FFCE56', 
    '#4BC0C0', 
    '#9966FF', 
    '#FF9F40', 
    '#C9CBCE', 
    '#00FF00', 
    '#FF00FF', 
    '#00FFFF' 
];

// จัดการข้อมูลและเตรียมสำหรับ Chart.js
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $labels[] = $row['product_category_name'];
        $data[] = (float)$row['total_revenue'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>กราฟสัดส่วนยอดขายรวมตามชื่อสินค้า</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        body { font-family: sans-serif; }
        .chart-container {
            width: 70%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
    </style>
</head>
<body>

<div class="chart-container">
    <h2>💰 สัดส่วนยอดขายรวมตามชื่อสินค้า ณัฐสุภา โยธากุล </h2>
    <p>เนื่องจากตาราง products ไม่มีคอลัมน์ประเภทสินค้า จึงแสดงยอดขายรวมตามชื่อสินค้าแต่ละรายการ</p>
    <canvas id="salesByTypeChart"></canvas>
</div>

<script>
// 2. **JavaScript/Chart.js: การแสดงผลกราฟ**

// เตรียมข้อมูล PHP ให้อยู่ในรูปแบบ JavaScript
const productLabels = <?php echo json_encode($labels); ?>;
const revenueData = <?php echo json_encode($data); ?>;
const bgColors = <?php echo json_encode($backgroundColors); ?>;

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesByTypeChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'doughnut', // กำหนดประเภทเป็น Doughnut Chart
        data: {
            labels: productLabels, // ชื่อสินค้า
            datasets: [{
                label: 'ยอดขายรวม (บาท)',
                data: revenueData, // ยอดขายรวม
                backgroundColor: bgColors.slice(0, productLabels.length), 
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'เปอร์เซ็นต์ยอดขายรวมตามชื่อสินค้า'
                }
            }
        }
    });
});
</script>

</body>
</html>