<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo "<h3 style='text-align:center;color:red'>Chức năng này chỉ dành cho quản trị viên.</h3>";
    exit();
}

$selectedUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$username = '';
$weekData = [];
$days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
$dayShort = ['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','CN'];
$weekData = array_fill_keys($days, '-');

$recommendStmt = $conn->prepare("
    SELECT r.title, r.content, r.generated_at, u.name AS author_name
    FROM recommendations r
    JOIN users u ON r.author_id = u.id
    WHERE r.user_id = ? AND r.category = 'bmi'
    ORDER BY r.generated_at DESC
");
$recommendStmt->bind_param("i", $selectedUserId);
$recommendStmt->execute();
$recommendResult = $recommendStmt->get_result();

if ($selectedUserId > 0) {
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $selectedUserId);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $username = $row['name'];
    }

    $stmt = $conn->prepare("SELECT date, ROUND(AVG(bmi),1) AS bmi FROM body_metrics WHERE user_id = ? AND WEEK(date, 1) = WEEK(CURDATE(), 1) GROUP BY date");
    $stmt->bind_param("i", $selectedUserId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $weekday = date('l', strtotime($row['date']));
        $weekData[$weekday] = $row['bmi'];
    }
}

$bmiArray = [];
foreach ($days as $d) {
    $bmiArray[] = is_numeric($weekData[$d]) ? (float)$weekData[$d] : null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['title'], $_POST['content'], $_POST['recommendation_type'], $_POST['category'])) {
    $targetUserId = (int)$_POST['user_id'];
    $authorId = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $type = $_POST['recommendation_type'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("INSERT INTO recommendations (user_id, author_id, recommendation_type, title, content, category) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $targetUserId, $authorId, $type, $title, $content, $category);

    if ($stmt->execute()) {
        echo "✔️ Đã lưu nhận xét thành công!";
    } else {
        echo "❌ Lỗi khi lưu nhận xét.";
    }
    exit();
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thống kê BMI người dùng</title>
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
    form {
      text-align: center;
      margin-bottom: 30px;
    }
    select, button {
      padding: 8px 14px;
      font-size: 16px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      background-color: #1a92be;
      color: white;
      font-weight: bold;
      cursor: pointer;
      margin-left: 10px;
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
  <h2>Thống kê BMI theo người dùng</h2>

  <form method="GET">
    <label for="user_id">Chọn người dùng:</label>
    <select name="user_id" id="user_id">
      <option value="0">-- Chọn --</option>
      <?php
      $users = $conn->query("SELECT id, name FROM users WHERE role = 'user'");
      while ($u = $users->fetch_assoc()):
        $selected = $u['id'] == $selectedUserId ? 'selected' : '';
        echo "<option value='".$u['id']."' $selected>ID ".$u['id']." - ".htmlspecialchars($u['name'])."</option>";
      endwhile;
      ?>
    </select>
    <button type="submit">Xem</button>
  </form>

  <?php if ($selectedUserId > 0): ?>
    <table>
      <tr><th>Họ tên</th>
        <?php foreach ($dayShort as $d) echo "<th>$d</th>"; ?>
      </tr>
      <tr>
        <td><?= "ID $selectedUserId - " . htmlspecialchars($username) ?></td>
        <?php foreach ($days as $d): ?>
          <td><?= is_numeric($weekData[$d]) ? $weekData[$d] : '-' ?></td>
        <?php endforeach; ?>
      </tr>
    </table>

    <div class="chart-container">
      <canvas id="bmiChart"></canvas>
    </div>

    <?php if ($selectedUserId > 0): ?>
    <section style="max-width: 800px; margin: 50px auto 0;">
        <h3>Đánh giá và nhận xét từ Admin</h3>
        <form id="admin-feedback-form" class="feedback-form">
            <input type="hidden" name="user_id" value="<?= $selectedUserId ?>">
            <input type="hidden" name="category" value="bmi">
            <input type="hidden" name="recommendation_type" value="comment">

            <input type="text" name="title" placeholder="Tiêu đề nhận xét" required style="width:100%; padding:10px; margin-bottom:10px;">
            <textarea name="content" placeholder="Nội dung chi tiết..." rows="4" required style="width:100%; padding:10px;"></textarea>
            <button type="submit" style="padding: 10px 20px; margin-top: 10px; background: #1a92be; color:white; border:none; border-radius:6px;">Lưu nhận xét</button>

            <div class="feedback-message" style="margin-top:10px;"></div>
        </form>
    </section>

    <?php endif; ?>

    <?php if ($recommendResult->num_rows > 0): ?>
    <div style="margin-top: 50px;">
        <h3 style="color: #1a92be;">📌 Gợi ý / Nhận xét cho người dùng</h3>
        <ul style="list-style-type: none; padding: 0;">
        <?php while ($rec = $recommendResult->fetch_assoc()): ?>
            <li style="background: #f9f9f9; margin-bottom: 15px; padding: 15px; border-left: 5px solid #1a92be; border-radius: 8px;">
            <strong><?= htmlspecialchars($rec['title']) ?></strong><br>
            <small>✍️ Bởi <?= htmlspecialchars($rec['author_name']) ?> - <?= date("d/m/Y H:i", strtotime($rec['generated_at'])) ?></small>
            <p style="margin-top: 10px;"><?= nl2br(htmlspecialchars($rec['content'])) ?></p>
            </li>
        <?php endwhile; ?>
        </ul>
    </div>
    <?php else: ?>
    <p style="margin-top: 30px; color: gray;">⚠️ Chưa có gợi ý nào cho người dùng này.</p>
    <?php endif; ?>

    <script>
      const ctx = document.getElementById('bmiChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: <?= json_encode($dayShort) ?>,
          datasets: [{
            label: 'BMI theo ngày',
            data: <?= json_encode($bmiArray) ?>,
            borderColor: '#e74c3c',
            backgroundColor: 'rgba(231,76,60,0.1)',
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: false,
              title: {
                display: true,
                text: 'Chỉ số BMI'
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
      document.querySelectorAll("form.feedback-form").forEach(form => {
        form.addEventListener("submit", function(e) {
            e.preventDefault();
            const msgBox = form.querySelector(".feedback-message");
            const formData = new FormData(form);

            fetch(window.location.href, { // gửi đến chính file hiện tại
            method: "POST",
            body: formData
            })
            .then(res => res.text())
            .then(data => {
            msgBox.textContent = data;
            msgBox.style.color = data.includes("✔️") ? "green" : "red";
            form.reset();
            setTimeout(() => location.reload(), 1000);
            })
            .catch(() => {
            msgBox.textContent = "❌ Lỗi kết nối đến máy chủ.";
            msgBox.style.color = "red";
            });
        });
        });
    </script>
  <?php endif; ?>
</body>
</html>
