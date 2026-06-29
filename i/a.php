<?php
// 1. **PHP: การตั้งค่าและการดึงข้อมูล**
// กำหนดข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost"; // เปลี่ยนเป็น server name ของคุณ
$username = "root";       // เปลี่ยนเป็น username ของคุณ
$password = "Neen@0981410093";           // เปลี่ยนเป็น password ของคุณ
$dbname = "computer_shop";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    // แสดงข้อความในรูปแบบที่เข้าใจง่าย หากเกิดข้อผิดพลาดในการเชื่อมต่อ
    die("Connection failed: " . $conn->connect_error . ". โปรดตรวจสอบการตั้งค่า \$servername, \$username, \$password และ \$dbname ในไฟล์ PHP");
}

// SQL Query: ดึง 5 อันดับแรกของสินค้าที่ขายดีที่สุด
// โดยรวมจำนวนสินค้าที่ขาย (SUM(quantity)) และจัดเรียงจากมากไปน้อย
$sql = "SELECT 
            p.name AS product_name,
            SUM(od.quantity) AS total_sold_quantity
        FROM 
            order_details od
        JOIN 
            products p ON od.product_id = p.product_id
        GROUP BY 
            p.product_id, p.name
        ORDER BY 
            total_sold_quantity DESC
        LIMIT 5"; // จำกัดเพียง 5 อันดับแรก

$result = $conn->query($sql);

$labels = []; // ชื่อสินค้าสำหรับแกน X
$data = [];   // จำนวนรวมที่ขายได้สำหรับแกน Y

// เตรียมชุดสีสำหรับแต่ละแท่ง (เพื่อให้แต่ละแท่งมีสีต่างกัน)
$colors = [
    'rgba(255, 99, 132, 0.7)',   // แดง
    'rgba(54, 162, 235, 0.7)',   // น้ำเงิน
    'rgba(255, 206, 86, 0.7)',   // เหลือง
    'rgba(75, 192, 192, 0.7)',   // เขียวอมฟ้า
    'rgba(153, 102, 255, 0.7)'   // ม่วง
];

$backgroundColors = [];
$borderColors = [];
$color_index = 0;

// จัดการข้อมูลและเตรียมสำหรับ Chart.js
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $labels[] = $row['product_name'];
        $data[] = (int)$row['total_sold_quantity'];
        
        // กำหนดสีหมุนเวียน/ชุดสีสำหรับ 5 อันดับ
        $backgroundColors[] = $colors[$color_index % count($colors)];
        $borderColors[] = str_replace('0.7', '1', $colors[$color_index % count($colors)]); // ขอบสีทึบกว่า
        $color_index++;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>กราฟ 5 อันดับสินค้าขายดี</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        body { font-family: sans-serif; }
        .chart-container {
            width: 70%;
            max-width: 800px;
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
    <h2>📊 5 อันดับสินค้าขายดีที่สุด (นับจากจำนวนที่ขายได้) ณัฐสุภา โยธากุล </h2>
    <canvas id="topProductsChart"></canvas>
</div>

<script>
// 2. **JavaScript/Chart.js: การแสดงผลกราฟ**

// เตรียมข้อมูล PHP ให้อยู่ในรูปแบบ JavaScript
const productLabels = <?php echo json_encode($labels); ?>;
const productData = <?php echo json_encode($data); ?>;
const bgColors = <?php echo json_encode($backgroundColors); ?>;
const bdrColors = <?php echo json_encode($borderColors); ?>;

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('topProductsChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar', // กำหนดประเภทเป็น Bar Chart
        data: {
            labels: productLabels, // ชื่อสินค้า
            datasets: [{
                label: 'จำนวนรวมที่ขายได้ (ชิ้น)',
                data: productData, // จำนวนที่ขาย
                backgroundColor: bgColors, // สีที่กำหนดไว้
                borderColor: bdrColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'จำนวนสินค้าที่ขายได้รวม 5 อันดับแรก'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'จำนวนที่ขายได้ (ชิ้น)'
                    },
                    ticks: {
                        // กำหนดให้แกน Y แสดงเป็นจำนวนเต็มเท่านั้น
                        stepSize: 1
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'สินค้า'
                    }
                }
            }
        }
    });
});
</script>

</body>
</html>