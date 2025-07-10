const currentPath = window.location.pathname.split("/").pop();
if (localStorage.getItem("loggedIn") !== "true") {
  window.location.href = `login.html?redirect=${currentPath}`;
}
