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

// SQL Query: ดึง 6 ลูกค้าที่มียอดสั่งซื้อรวมสูงสุด
$sql = "SELECT 
            c.name AS customer_name,
            SUM(od.quantity * p.price) AS total_spent /* ใช้ p.price ที่ถูกต้อง */
        FROM 
            customers c
        JOIN 
            orders o ON c.customer_id = o.customer_id
        JOIN 
            order_details od ON o.order_id = od.order_id
        JOIN 
            products p ON od.product_id = p.product_id 
        GROUP BY 
            c.customer_id, c.name
        ORDER BY 
            total_spent DESC
        LIMIT 6"; // <-- จำกัดที่ 6 คนแรก

$result = $conn->query($sql);

$labels = []; // ชื่อลูกค้า (แกน Y)
$data = [];   // ยอดสั่งซื้อรวม (แกน X)

// ชุดสีสำหรับกราฟ Horizontal Bar 6 แท่ง
$backgroundColor = [
    'rgba(255, 99, 132, 0.8)',   // Red
    'rgba(54, 162, 235, 0.8)',   // Blue
    'rgba(255, 206, 86, 0.8)',   // Yellow
    'rgba(75, 192, 192, 0.8)',   // Teal
    'rgba(153, 102, 255, 0.8)',  // Purple
    'rgba(255, 159, 64, 0.8)'    // Orange
];
$borderColor = [
    'rgba(255, 99, 132, 1)',
    'rgba(54, 162, 235, 1)',
    'rgba(255, 206, 86, 1)',
    'rgba(75, 192, 192, 1)',
    'rgba(153, 102, 255, 1)',
    'rgba(255, 159, 64, 1)'
];

// จัดการข้อมูลและเตรียมสำหรับ Chart.js
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $labels[] = $row['customer_name'];
        $data[] = (float)$row['total_spent'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>กราฟลูกค้าที่มียอดสั่งซื้อสูงสุด 6 คนแรก (Horizontal Bar Chart)</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        body { font-family: sans-serif; }
        .chart-container {
            width: 80%;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="chart-container">
    <h2>👑 ยอดสั่งซื้อรวมสูงสุด 6 อันดับแรกของลูกค้า ณัฐสุภา โยธากุล</h2>
    <canvas id="topCustomersHorizontalChart"></canvas>
</div>

<script>
// 2. **JavaScript/Chart.js: การแสดงผลกราฟ**

// เตรียมข้อมูล PHP ให้อยู่ในรูปแบบ JavaScript
const customerLabels = <?php echo json_encode($labels); ?>;
const spentData = <?php echo json_encode($data); ?>;
const bgColors = <?php echo json_encode($backgroundColor); ?>;
const bdrColors = <?php echo json_encode($borderColor); ?>;

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('topCustomersHorizontalChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar', // กำหนดประเภทเป็น Bar Chart
        data: {
            labels: customerLabels, // ชื่อลูกค้า
            datasets: [{
                label: 'ยอดสั่งซื้อรวม (บาท)',
                data: spentData, // ยอดสั่งซื้อรวม
                backgroundColor: bgColors.slice(0, customerLabels.length),
                borderColor: bdrColors.slice(0, customerLabels.length),
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y', // <-- ทำให้แท่งกราฟวางในแนวนอน
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'ลูกค้า 6 คนแรกที่มียอดสั่งซื้อรวมสูงสุด'
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'ยอดสั่งซื้อรวม (บาท)'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'ชื่อลูกค้า'
                    }
                }
            }
        }
    });
});
</script>

</body>
</html>