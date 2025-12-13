document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("updateForm");
  const msg = document.getElementById("updateMsg");

  // Fetch profile data
  fetch("php/profile.php")
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        alert("❌ " + data.error);
        return;
      }

      document.getElementById("id").textContent = data.student_id_number;
      document.getElementById("fullName").textContent = data.full_name;
      document.getElementById("email").textContent = data.email;
      document.getElementById("contact").textContent = data.contact;
      document.getElementById("course").textContent = data.course;
      document.getElementById("yearLevel").textContent = data.year_level;
      document.getElementById("section").textContent = data.section;
    })
    .catch(() => {
      alert("⚠️ Failed to load profile.");
    });

  // Update contact
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(form);

    fetch("php/profile-update.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        msg.textContent = data.message;
        msg.style.color = data.success ? "green" : "red";
        if (data.success) {
          form.reset();
          document.getElementById("contact").textContent = formData.get("newContact");
        }
      })
      .catch(() => {
        msg.textContent = "⚠️ Failed to update contact.";
        msg.style.color = "red";
      });
  });
});
