document.addEventListener("DOMContentLoaded", () => {
  fetch("../php/office-head-account-settings.php")
    .then(res => {
      if (!res.ok) {
        window.location.href = "../login.html";
      }
      return res.json();
    })
    .then(data => {
      document.getElementById("ohName").value = data.full_name;
      document.getElementById("ohEmail").value = data.email;
      document.getElementById("notifyAttendance").checked = data.notify_attendance == 1;
      document.getElementById("notifyEvaluation").checked = data.notify_evaluation == 1;
      document.getElementById("notifyWeekly").checked = data.notify_weekly_summary == 1;
      document.getElementById("twoFASelect").value = data.two_fa == 1 ? "enabled" : "disabled";
    });

  document.getElementById("passwordForm").addEventListener("submit", e => {
    e.preventDefault();
    const currentPassword = document.getElementById("currentPassword").value;
    const newPassword = document.getElementById("newPassword").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    if (newPassword !== confirmPassword) {
      alert("❌ Passwords do not match!");
      return;
    }

    fetch("../php/office-head-account-settings.php", {
      method: "PUT",
      body: new URLSearchParams({ currentPassword, newPassword })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert("✅ Password updated!");
        e.target.reset();
      } else {
        alert("❌ " + data.error);
      }
    });
  });

  const saveSettings = () => {
    const payload = {
      name: document.getElementById("ohName").value,
      email: document.getElementById("ohEmail").value,
      twoFA: document.getElementById("twoFASelect").value,
      notifyAttendance: document.getElementById("notifyAttendance").checked,
      notifyEvaluation: document.getElementById("notifyEvaluation").checked,
      notifyWeekly: document.getElementById("notifyWeekly").checked
    };

    fetch("../php/office-head-account-settings.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    }).then(res => res.json()).then(data => {
      if (data.success) console.log("✅ Settings updated.");
    });
  };

  document.getElementById("ohInfoForm").addEventListener("change", saveSettings);
  document.getElementById("notificationsForm").addEventListener("change", saveSettings);
  document.getElementById("twoFAForm").addEventListener("change", saveSettings);
});

function logout() {
  if (confirm("Are you sure you want to log out?")) {
    window.location.href = "../php/logout.php";
  }
}
