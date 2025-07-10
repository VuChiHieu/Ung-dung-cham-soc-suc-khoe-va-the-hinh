function toggleMenu() {
   const menu = document.querySelector('.navbar-items');
   menu.classList.toggle('active');
}

function showToast(message, duration = 3000, type = 'info') {
   const toast = document.getElementById("toast");
   toast.textContent = message;
   toast.style.backgroundColor = "#333";

   if (type === 'success') toast.style.backgroundColor = "#28a745";
   else if (type === 'error') toast.style.backgroundColor = "red";
   else if (type === 'warning') toast.style.backgroundColor = "#ffc107";

   toast.classList.add("show");

   setTimeout(() => {
      toast.classList.remove("show");
   }, duration);
}

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

      const nav = document.querySelector(".nav");
      const li = document.createElement("li");
      li.innerHTML = `<span style="color: white">XIN CHÀO, ${username.toUpperCase()}!</span>`;
      nav.appendChild(li);
   }
}

window.addEventListener("DOMContentLoaded", function () {
    const message = localStorage.getItem("toastMessage");
    const type = localStorage.getItem("toastType");

    if (message) {
        showToast(message, 3000, type || 'info');
        localStorage.removeItem("toastMessage");
        localStorage.removeItem("toastType");
    }
});
