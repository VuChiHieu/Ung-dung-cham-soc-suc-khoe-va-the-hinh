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

// Lấy tên người dùng
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result()->fetch_assoc();
$username = $user_result['name'];

// Mảng thứ và khởi tạo dữ liệu tuần
$days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
$dayShort = ['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','CN'];
$weekData = array_fill_keys($days, ['in' => 0, 'out' => 0]);

// Truy vấn dữ liệu tuần hiện tại
$sql = "SELECT date, calories_in, calories_out FROM calories_tracker
        WHERE user_id = ? AND WEEK(date, 1) = WEEK(CURDATE(), 1)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $weekday = date('l', strtotime($row['date']));
    $weekData[$weekday] = [
        'in' => (int)$row['calories_in'],
        'out' => (int)$row['calories_out']
    ];
}

$caloInArray = [];
$caloOutArray = [];
foreach ($days as $d) {
    $caloInArray[] = $weekData[$d]['in'];
    $caloOutArray[] = $weekData[$d]['out'];
}

$stmt = $conn->prepare("
    SELECT r.title, r.content, r.generated_at, u.name AS author_name
    FROM recommendations r
    JOIN users u ON r.author_id = u.id
    WHERE r.user_id = ? AND r.category = 'calories'
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
  <title>Tiến trình calo trong tuần</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      padding: 40px;
      background: #f0f9ff;
    }
    h2 {
      color: #1a92be;
      text-align: center;
      margin-bottom: 30px;
    }
    table {
      width: 100%;
      max-width: 1000px;
      margin: auto;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      margin-bottom: 40px;
    }
    th, td {
      padding: 12px;
      text-align: center;
      border: 1px solid #ccc;
    }
    th {
      background-color: #1a92be;
      color: white;
    }
    .chart-container {
      width: 90%;
      max-width: 1000px;
      margin: auto;
    }
  </style>
</head>
<body>
  <h2>Tiến trình calo trong tuần: <?= htmlspecialchars($username) ?></h2>

  <table>
    <tr><th>Họ tên</th>
      <?php foreach ($dayShort as $d) echo "<th>$d</th>"; ?>
    </tr>
    <tr>
      <td><?= htmlspecialchars($username) ?></td>
      <?php foreach ($days as $d): ?>
        <td><?= $weekData[$d]['in'] ?>/<?= $weekData[$d]['out'] ?></td>
      <?php endforeach; ?>
    </tr>
  </table>

  <div class="chart-container">
    <canvas id="caloChart"></canvas>
  </div>

  <section style="margin-top: 40px; max-width: 800px; margin-left: auto; margin-right: auto;">
      <h3>Đánh giá và nhận xét</h3>
      <form id="feedback-form">
        <input type="hidden" name="category" value="calories">
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
    const ctx = document.getElementById('caloChart').getContext('2d');
    const caloChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?= json_encode($dayShort) ?>,
        datasets: [
          {
            label: 'Calo nạp vào',
            data: <?= json_encode($caloInArray) ?>,
            borderColor: '#1a92be',
            backgroundColor: 'rgba(26, 146, 190, 0.1)',
            tension: 0.3
          },
          {
            label: 'Calo tiêu hao',
            data: <?= json_encode($caloOutArray) ?>,
            borderColor: '#f39c12',
            backgroundColor: 'rgba(243, 156, 18, 0.1)',
            tension: 0.3
          }
        ]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Số calo'
            }
          }
        },
        plugins: {
          legend: {
            position: 'bottom'
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
