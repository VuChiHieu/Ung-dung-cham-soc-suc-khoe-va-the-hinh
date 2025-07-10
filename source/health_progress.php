<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    echo "<h3 style='text-align:center;color:red'>Trang này chỉ dành cho người dùng đã đăng nhập.</h3>";
    exit();
}

$user_id = $_SESSION['user_id'];
$author_id = $_SESSION['user_id'];

// ======= 1. Xử lý lưu đánh giá (POST) =======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['content'], $_POST['recommendation_type'], $_POST['category'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $type = $_POST['recommendation_type'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("INSERT INTO recommendations (user_id, author_id, recommendation_type, title, content, category) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("iissss", $user_id, $user_id, $type, $title, $content, $category);

    if ($stmt->execute()) {
        echo "✔️ Đánh giá đã được lưu!";
    } else {
        echo "❌ Lỗi khi lưu đánh giá.";
    }
    exit(); // Trả kết quả, không hiển thị HTML nữa
}

// ======= 2. Lấy tên người dùng =======
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$username = $stmt->get_result()->fetch_assoc()['name'];

// ======= 3. Lấy dữ liệu thống kê tuần này =======
$sql = "SELECT 
            date,
            ROUND(AVG(systolic)) AS systolic,
            ROUND(AVG(diastolic)) AS diastolic,
            ROUND(AVG(heart_rate)) AS heart_rate
        FROM health_metrics 
        WHERE user_id = ? AND WEEK(date, 1) = WEEK(CURDATE(), 1)
        GROUP BY date";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$dayMap = [
    'Monday'    => 'Thứ 2',
    'Tuesday'   => 'Thứ 3',
    'Wednesday' => 'Thứ 4',
    'Thursday'  => 'Thứ 5',
    'Friday'    => 'Thứ 6',
    'Saturday'  => 'Thứ 7',
    'Sunday'    => 'CN'
];

$weekData = [];
foreach ($dayMap as $en => $vn) {
    $weekData[$vn] = ['sys' => '-', 'dia' => '-', 'hr' => '-'];
}

while ($row = $result->fetch_assoc()) {
    $dayEn = date('l', strtotime($row['date']));
    $dayVN = $dayMap[$dayEn];
    $weekData[$dayVN] = [
        'sys' => $row['systolic'],
        'dia' => $row['diastolic'],
        'hr'  => $row['heart_rate']
    ];
}

// ======= 4. Lấy các đánh giá cũ của user =======
$stmt = $conn->prepare("
    SELECT r.title, r.content, r.generated_at, u.name AS author_name
    FROM recommendations r
    JOIN users u ON r.author_id = u.id
    WHERE r.user_id = ? AND r.category = 'blood_pressure'
    ORDER BY r.generated_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recommendations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>


  <!DOCTYPE html>
  <html lang="vi">
  <head>
    <meta charset="UTF-8">
    <title>Thống kê huyết áp & nhịp tim</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
      body {
        font-family: 'Segoe UI', sans-serif;
        background: #f0f9ff;
        padding: 40px;
      }
      h2 {
        text-align: center;
        color: #1a92be;
        margin-bottom: 30px;
      }
      table {
        width: 90%;
        max-width: 1000px;
        margin: auto;
        border-collapse: collapse;
        background: #fff;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
      }
      th, td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
      }
      th {
        background: #1a92be;
        color: white;
      }
      tr:nth-child(even) {
        background: #f9f9f9;
      }
      .chart-container {
        width: 90%;
        max-width: 900px;
        margin: 40px auto;
      }
    </style>
  </head>
  <body>
    <h2>Thống kê tuần này: <?= htmlspecialchars($username) ?></h2>

    <table>
      <thead>
        <tr>
          <th>Ngày</th>
          <th>Huyết áp (mmHg)</th>
          <th>Nhịp tim (bpm)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($weekData as $day => $val): ?>
          <tr>
            <td><?= $day ?></td>
            <td>
              <?php 
                $s = $val['sys'];
                $d = $val['dia'];
                echo ($s !== '-' && $d !== '-') ? "$s / $d" : "-";
              ?>
            </td>
            <td><?= $val['hr'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="chart-container">
      <canvas id="bpChart"></canvas>
    </div>

    <section style="margin-top: 40px; max-width: 800px; margin-left: auto; margin-right: auto;">
      <h3>Đánh giá và nhận xét</h3>
      <form id="feedback-form">
        <input type="hidden" name="category" value="blood_pressure">
        <input type="hidden" name="recommendation_type" value="health">
        <input type="text" name="title" placeholder="Tiêu đề đánh giá" required style="width:100%; padding:10px; margin-bottom:10px;">
        <textarea name="content" placeholder="Nội dung chi tiết..." rows="4" required style="width:100%; padding:10px;"></textarea>
        <button type="submit" style="padding: 10px 20px; margin-top: 10px; background: #1a92be; color:white; border:none; border-radius:6px;">Lưu đánh giá</button>
      </form>
      <div id="feedback-message" style="margin-top:10px; color: green;"></div>
    </section>

    <?php if (!empty($recommendations)): ?>
      <section style="max-width:800px; margin:40px auto;">
        <h3>📝 Các đánh giá trước đó</h3>
        <?php foreach ($recommendations as $rec): ?>
          <div style="border-left: 4px solid #1a92be; padding-left: 10px; margin-bottom: 20px;">
            <strong><?= htmlspecialchars($rec['title']) ?></strong><br>
            <small>✍️ Bởi <?= htmlspecialchars($rec['author_name']) ?> - <?= date('d/m/Y H:i', strtotime($rec['generated_at'])) ?></small>
            <p><?= nl2br(htmlspecialchars($rec['content'])) ?></p>
          </div>
        <?php endforeach; ?>
      </section>
    <?php endif; ?>

    <script>
      const bpChart = new Chart(document.getElementById("bpChart"), {
        type: 'line',
        data: {
          labels: <?= json_encode(array_keys($weekData)) ?>,
          datasets: [
            {
              label: "Huyết áp trên (SYS)",
              data: <?= json_encode(array_map(fn($d) => is_numeric($d['sys']) ? $d['sys'] : null, $weekData)) ?>,
              borderColor: "#e74c3c",
              fill: false
            },
            {
              label: "Huyết áp dưới (DIA)",
              data: <?= json_encode(array_map(fn($d) => is_numeric($d['dia']) ? $d['dia'] : null, $weekData)) ?>,
              borderColor: "#f39c12",
              fill: false
            },
            {
              label: "Nhịp tim",
              data: <?= json_encode(array_map(fn($d) => is_numeric($d['hr']) ? $d['hr'] : null, $weekData)) ?>,
              borderColor: "#1a92be",
              fill: false
            }
          ]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: false
            }
          }
        }
      });

      document.getElementById("feedback-form").addEventListener("submit", function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        fetch(window.location.href, {
          method: "POST",
          body: formData
        })
        .then(res => res.text())
        .then(data => {
          document.getElementById("feedback-message").innerHTML = data;
          form.reset();
        })
        .catch(() => {
          document.getElementById("feedback-message").textContent = "Lỗi kết nối máy chủ.";
        });
      });

    </script>
  </body>
  </html>
