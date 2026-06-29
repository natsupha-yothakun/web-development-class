<?php
// กำหนดค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost"; // เปลี่ยนเป็นชื่อเซิร์ฟเวอร์ของคุณ
$username = "root";      // เปลี่ยนเป็นชื่อผู้ใช้ฐานข้อมูลของคุณ
$password = "Neen@0981410093";          // เปลี่ยนเป็นรหัสผ่านฐานข้อมูลของคุณ
$dbname = "computer_shop";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// กำหนด charset เป็น utf8mb4 เพื่อรองรับภาษาไทย
$conn->set_charset("utf8mb4");

// 1. สร้างคำสั่ง SQL เพื่อดึงข้อมูลสินค้าขายดี 5 อันดับแรก
// โดยนับจาก 'จำนวน' สินค้าที่ถูกสั่งซื้อ (quantity)
$sql = "
    SELECT 
        p.name AS product_name,
        SUM(od.quantity) AS total_quantity_sold
    FROM 
        order_details od
    JOIN 
        products p ON od.product_id = p.product_id
    GROUP BY 
        p.name
    LIMIT 5
";

$result = $conn->query($sql);

$productNames = [];
$quantities = [];
$backgroundColor = [
    'rgba(255, 99, 132, 0.6)',  // แดง
    'rgba(54, 162, 235, 0.6)',  // น้ำเงิน
    'rgba(255, 206, 86, 0.6)',  // เหลือง
    'rgba(75, 192, 192, 0.6)',  // เขียว
    'rgba(153, 102, 255, 0.6)'  // ม่วง
];
$borderColor = [
    'rgba(255, 99, 132, 1)',
    'rgba(54, 162, 235, 1)',
    'rgba(255, 206, 86, 1)',
    'rgba(75, 192, 192, 1)',
    'rgba(153, 102, 255, 1)'
];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productNames[] = $row['product_name'];
        $quantities[] = (int)$row['total_quantity_sold']; // แปลงเป็น int
    }
}

// 2. แปลงข้อมูล PHP array ให้เป็น JSON สำหรับนำไปใช้ใน JavaScript
$productNames_json = json_encode($productNames, JSON_UNESCAPED_UNICODE);
$quantities_json = json_encode($quantities);
$backgroundColor_json = json_encode($backgroundColor);
$borderColor_json = json_encode($borderColor);

// ปิดการเชื่อมต่อ
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สินค้าขายดี 5 อันดับแรก </title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }
        .chart-container {
            width: 80%;
            max-width: 900px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="chart-container">
        <h1>📊 สินค้าขายดี 5 อันดับแรก (นับตามจำนวนสินค้า) ณัฐสุภา โยธากุล</h1>
        <canvas id="bestSellersChart"></canvas>
    </div>

    <script>
        // 4. รับข้อมูล JSON จาก PHP มาเก็บในตัวแปร JavaScript
        const productLabels = <?php echo $productNames_json; ?>;
        const salesData = <?php echo $quantities_json; ?>;
        const backgroundColors = <?php echo $backgroundColor_json; ?>;
        const borderColors = <?php echo $borderColor_json; ?>;

        // 5. ตั้งค่าและสร้าง Bar Chart
        const ctx = document.getElementById('bestSellersChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: productLabels,
                datasets: [{
                    label: 'จำนวนสินค้าที่ขายได้ (หน่วย)',
                    data: salesData,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'จำนวน (หน่วย)'
                        },
                        // กำหนดให้แกน Y เป็นจำนวนเต็มเท่านั้น (ถ้าข้อมูลเป็นจำนวนเต็ม)
                        ticks: {
                            callback: function(value) {
                                if (value % 1 === 0) {
                                    return value;
                                }
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'ชื่อสินค้า'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true
                    },
                    title: {
                        display: true,
                        text: 'รายงานสินค้าขายดี 5 อันดับแรก',
                        font: {
                            size: 18
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>