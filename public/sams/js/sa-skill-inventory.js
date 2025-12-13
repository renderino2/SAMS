document.addEventListener("DOMContentLoaded", () => {
  const skillsBody = document.getElementById("skillsBody");
  const addSkillForm = document.getElementById("addSkillForm");
  const feedback = document.getElementById("feedback");

  if (!skillsBody || !addSkillForm) {
    console.error("❌ Required DOM elements not found.");
    return;
  }

  function loadSkills() {
    fetch("../php/sa-skill-inventory.php")
      .then(res => res.json())
      .then(data => {
        skillsBody.innerHTML = "";

        if (!Array.isArray(data) || data.length === 0) {
          skillsBody.innerHTML = `<tr><td colspan="5" style="text-align:center; color:gray;">No skills recorded yet.</td></tr>`;
          return;
        }

        data.forEach((skill, index) => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${index + 1}</td>
            <td>${skill.skill_name}</td>
            <td>${skill.added_on}</td>
            <td><span class="badge ${skill.is_verified == 1 ? 'verified' : 'not-verified'}">
              ${skill.is_verified == 1 ? "✅ Verified" : "❌ Not Verified"}
            </span></td>
            <td>${skill.note || "—"}</td>
          `;
          skillsBody.appendChild(row);
        });
      })
      .catch(err => {
        console.error("Failed to load skills:", err);
        skillsBody.innerHTML = `<tr><td colspan="5" style="text-align:center; color:red;">⚠️ Error loading data</td></tr>`;
      });
  }

  addSkillForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(addSkillForm);

    fetch("../php/add-skill.php", {
      method: "POST",
      body: formData,
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          feedback.style.display = "block";
          addSkillForm.reset();
          loadSkills();
          setTimeout(() => feedback.style.display = "none", 3000);
        } else {
          alert("❌ " + (data.error || "Failed to add skill."));
        }
      })
      .catch(err => {
        console.error("Add skill error:", err);
        alert("❌ Network error.");
      });
  });

  loadSkills();
});
