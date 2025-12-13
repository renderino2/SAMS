document.addEventListener("DOMContentLoaded", () => {
  fetch('php/get_hr_profile.php')
    .then(res => res.json())
    .then(data => {
      if (!data.error) {
        document.getElementById('hrName').value = data.full_name;
        document.getElementById('hrEmail').value = data.email;
        document.getElementById('profilePreview').src = `assets/uploads/${data.profile_picture}`;
      }
    });
});

// Update Profile Info
document.getElementById("hrInfoForm").addEventListener("change", () => {
  const name = document.getElementById("hrName").value;
  const email = document.getElementById("hrEmail").value;

  fetch("php/update_hr_profile.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ name, email }),
  }).then(res => res.text()).then(alert);
});

// Update Password
document.getElementById("passwordChangeForm").addEventListener("submit", e => {
  e.preventDefault();
  const current = document.getElementById("currentPassword").value;
  const newPass = document.getElementById("newPassword").value;
  const confirm = document.getElementById("confirmPassword").value;

  if (newPass !== confirm) return alert("❌ Passwords do not match");

  fetch("php/update_hr_password.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ current, new: newPass }),
  }).then(res => res.text()).then(response => {
    if (response === "success") {
      alert("✅ Password updated!");
    } else {
      alert("❌ Current password is incorrect");
    }
  });
});

// Upload Profile Picture
document.getElementById("profilePicture").addEventListener("change", function () {
  const file = this.files[0];
  const formData = new FormData();
  formData.append("file", file);

  fetch("php/upload_profile_picture.php", {
    method: "POST",
    body: formData,
  }).then(res => res.text()).then(() => {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById("profilePreview").src = e.target.result;
    };
    reader.readAsDataURL(file);
  });
});

// Deactivate Account
document.querySelector(".deactivate-btn").addEventListener("click", () => {
  if (confirm("Are you sure you want to deactivate your account?")) {
    fetch("php/deactivate_hr.php")
      .then(res => res.text())
      .then(msg => {
        alert("Account deactivated.");
        window.location.href = "logout.php";
      });
  }
});
