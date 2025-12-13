document.getElementById("loginForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const studentId = document.getElementById("studentId").value.trim();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value.trim();
  const role = document.getElementById("role").value;
  const message = document.getElementById("message");

  if (!studentId || !email || !password || !role) {
    message.textContent = "⚠️ All fields are required.";
    message.style.color = "red";
    return;
  }

  fetch("../php/login.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ studentId, email, password, role }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        window.location.href = "../html/" + data.redirect;
      } else {
        message.textContent = "⚠️ " + data.error;
        message.style.color = "red";
      }
    })
    .catch((err) => {
      console.error("Login error:", err);
      message.textContent = "⚠️ Network error.";
      message.style.color = "red";
    });
});
