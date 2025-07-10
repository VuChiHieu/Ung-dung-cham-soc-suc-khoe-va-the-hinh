function showToast(message, duration = 3000, type = 'info') {
   const toast = document.getElementById("toast");
   toast.textContent = message;

   // Reset màu nền
   toast.style.backgroundColor = "#333";

   if (type === 'success') toast.style.backgroundColor = "#28a745";
   else if (type === 'error') toast.style.backgroundColor = "red";
   else if (type === 'warning') toast.style.backgroundColor = "#ffc107";

   toast.classList.add("show");

   setTimeout(() => {
      toast.classList.remove("show");
   }, duration);
}

const passwordAccess = (loginPass, loginEye) =>{
   const input = document.getElementById(loginPass),
         iconEye = document.getElementById(loginEye)

   iconEye.addEventListener('click', () =>{
      input.type === 'password' ? input.type = 'text'
						              : input.type = 'password'
      iconEye.classList.toggle('ri-eye-fill')
      iconEye.classList.toggle('ri-eye-off-fill')
   })
}
passwordAccess('password','loginPassword')

const passwordRegister = (loginPass, loginEye) =>{
   const input = document.getElementById(loginPass),
         iconEye = document.getElementById(loginEye)

   iconEye.addEventListener('click', () =>{
      // Change password to text
      input.type === 'password' ? input.type = 'text'
						              : input.type = 'password'

      // Icon change
      iconEye.classList.toggle('ri-eye-fill')
      iconEye.classList.toggle('ri-eye-off-fill')
   })
}
passwordRegister('passwordCreate','loginPasswordCreate')

/*=============== SHOW HIDE LOGIN & CREATE ACCOUNT ===============*/
const loginAcessRegister = document.getElementById('loginAccessRegister'),
      buttonRegister = document.getElementById('loginButtonRegister'),
      buttonAccess = document.getElementById('loginButtonAccess')

buttonRegister.addEventListener('click', () => {
   loginAcessRegister.classList.add('active')
})

buttonAccess.addEventListener('click', () => {
   loginAcessRegister.classList.remove('active')
})

document.querySelector('#registerForm').addEventListener("submit", function (e) {
    e.preventDefault();
    const name = document.getElementById("names").value.trim();
    console.log("Tên:", name);
    const surname = document.getElementById("surnames").value.trim();
    const email = document.getElementById("emailCreate").value.trim();
    const password = document.getElementById("passwordCreate").value.trim();

    if (!name || !surname || !email || !password) {
        showToast("Vui lòng nhập đầy đủ thông tin!", 3000, "warning");
        return;
    }

    const data = {
        name,
        surname,  
        email,
        password
    };

    fetch("register.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams(data),
    })
    .then(res => res.text())
    .then(result => {
        if (result === "success") {
            showToast("Tạo tài khoản thành công!", 3000, "success");

            localStorage.setItem("name", name);
            localStorage.setItem("email", email); // bạn có thể lưu nếu muốn hiện lời chào sau này

            // Chuyển về form đăng nhập
            loginAcessRegister.classList.remove('active');
        } else {
            showToast("Đăng ký thất bại.", 3000, "error");
        }
    });
});

// Xử lý đăng nhập
const loginForm = document.querySelector(".login__access .login__form");

loginForm.addEventListener("submit", function(e) {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    fetch("login_user.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            email,
            password
        })
    })
    .then(res => res.json())
    .then(result => {
        if (result.status === "success") {
            localStorage.setItem("toastMessage", "Đăng nhập thành công!");
            localStorage.setItem("toastType", "success");
            localStorage.setItem("loggedIn", "true");
            localStorage.setItem("currentUser", email);
            localStorage.setItem("name", result.name);

            if (result.role === "admin") {
                window.location.href = "fitlife_users.php";
            } else {
                window.location.href = "index.html";
            }
        } else if (result.status === "wrong-password") {
            showToast("Sai mật khẩu!", 3000, "error");
        } else if (result.status === "not-found") {
            showToast("Không tìm thấy tài khoản!", 3000, "error");
        } else {
            showToast("Lỗi hệ thống!", 3000, "error");
        }
    })
    .catch(() => {
        showToast("Lỗi kết nối máy chủ!", 3000, "error");
    });
});
