document.getElementById('role').addEventListener('change', function () {
  const officeGroup = document.getElementById('officeGroup');
  officeGroup.style.display = (this.value === 'Office Head' || this.value === 'Student Assistant') ? 'block' : 'none';
});

document.getElementById('registerForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const student_id_number = document.getElementById('studentId').value.trim();
  const full_name = document.getElementById('fullName').value.trim();
  const email = document.getElementById('email').value.trim();
  const role = document.getElementById('role').value;
  const office = document.getElementById('office').value;
  const password = document.getElementById('password').value;
  const confirm_password = document.getElementById('confirmPassword').value;
  const message = document.getElementById('message');

  message.textContent = "";

  if (!student_id_number || !full_name || !email || !role || !password || !confirm_password) {
    message.style.color = 'red';
    message.textContent = "❌ Please complete all required fields.";
    return;
  }

  if (password !== confirm_password) {
    message.style.color = 'red';
    message.textContent = "❌ Passwords do not match!";
    return;
  }

  try {
    const response = await fetch("../php/register.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        student_id_number,
        full_name,
        email,
        role,
        office,
        password,
        confirm_password
      }),
    });

    const result = await response.json();

    if (result.success) {
      window.location.href = "../html/login.html";
    } else {
      message.style.color = "red";
      message.textContent = result.error || "❌ Registration failed.";
    }
  } catch (error) {
    console.error("🚨 JS Fetch Error:", error);
    message.style.color = "red";
    message.textContent = "⚠️ Network error. Please try again.";
  }
});
