window.onload = function () {
   const isLoggedIn = localStorage.getItem("loggedIn");
   const username = localStorage.getItem("name") || "Người dùng";

   const loginNav = document.getElementById("loginNav");

   if (isLoggedIn === "true") {
      loginNav.textContent = "ĐĂNG XUẤT";
      loginNav.href = "#";
      loginNav.addEventListener("click", function(e) {
         e.preventDefault();
         localStorage.removeItem("loggedIn");
         localStorage.removeItem("currentUser");
         window.location.reload();
      });

      // Có thể thêm lời chào
      const nav = document.querySelector(".nav");
      const li = document.createElement("li");
      li.innerHTML = `<span style="color: white">XIN CHÀO, ${username}!</span>`;
      nav.appendChild(li);
   }
}

window.addEventListener('scroll', () => {
   document.querySelectorAll('.box2').forEach((box) => {
      const boxTop = box.getBoundingClientRect().top;
      if (boxTop < window.innerHeight - 100) {
      box.style.opacity = 1;
   }
 });
});

window.addEventListener("scroll", function () {
    const aboutSection = document.querySelector(".about");
    const rect = aboutSection.getBoundingClientRect();
    const windowHeight = window.innerHeight;

    if (rect.top <= windowHeight - 100) {
        aboutSection.classList.add("show");
    }
});

const text = "Trân trọng sức khỏe hôm nay, cho một tương lai khỏe mạnh.";
const typingElement = document.getElementById("aboutTyping");

let i = 0;
function typeText() {
   if (i < text.length) {
      typingElement.textContent += text.charAt(i);
      i++;
      setTimeout(typeText, 50);
   }
}
typeText();

function toggleMenu() {
   const menu = document.querySelector('.navbar-items');
   menu.classList.toggle('active');
}

document.getElementById("submitBooking").addEventListener("click", function(e) {
  e.preventDefault();

  const name = document.getElementById("name").value.trim();
  const age = document.getElementById("age").value.trim();
  const email = document.getElementById("email").value.trim();
  const phone = document.getElementById("phone").value.trim();

  
  if (!name || !age || !email || !phone) {
    Toastify({
      text: "Vui lòng điền đầy đủ thông tin!",
      duration: 3000,
      gravity: "top",
      position: "center",
      style: { background: "linear-gradient(to right, #dc3545, #b22a2a)" }
    }).showToast();
    return;
  }

  
  if (isNaN(age) || age < 0) {
    Toastify({
      text: "Tuổi phải là số không âm!",
      duration: 3000,
      gravity: "top",
      position: "center",
      style: { background: "linear-gradient(to right, #dc3545, #b22a2a)" }
    }).showToast();
    return;
  }

  
  if (!/^\d{10}$/.test(phone)) {
    Toastify({
      text: "Số điện thoại phải đúng 10 chữ số!",
      duration: 3000,
      gravity: "top",
      position: "center",
      style: { background: "linear-gradient(to right, #dc3545, #b22a2a)" }
    }).showToast();
    return;
  }

  
  const data = { name, age, email, phone };

  fetch("booking.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams(data)
  })
  .then(res => res.text())
  .then(result => {
    if (result === "success") {
      Toastify({
        text: "Đăng ký tư vấn thành công!",
        duration: 3000,
        gravity: "top",
        position: "center",
        close: true,
        style: { background: "linear-gradient(to right, #00b09b, #96c93d)" }
      }).showToast();

      
      document.getElementById("name").value = "";
      document.getElementById("age").value = "";
      document.getElementById("email").value = "";
      document.getElementById("phone").value = "";
    } else if (result.includes("invalid email")){
      Toastify({
        text: "Email không hợp lệ!",
        duration: 3000,
        gravity: "top",
        position: "center",
        style: { background: "linear-gradient(to right, #dc3545, #b22a2a)" }
      }).showToast();
    }
  });
});
