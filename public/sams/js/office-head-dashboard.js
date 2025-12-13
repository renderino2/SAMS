document.addEventListener("DOMContentLoaded", () => {
  fetch("../php/office-head-dashboard.php")
    .then(res => {
      if (!res.ok) throw new Error("Failed to fetch");
      return res.json();
    })
    .then(data => {
      if (data.success) {
        document.getElementById("assignedCount").textContent = data.assistants;
        document.getElementById("attendanceCount").textContent = data.present;
        document.getElementById("evalStatus").textContent = data.evaluated;

        const tableBody = document.getElementById("dtrTableBody");
        tableBody.innerHTML = ""; // Clear before insert

        data.dtr.forEach(row => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${row.name}</td>
            <td>${row.date}</td>
            <td>${row.time_in} / ${row.time_out}</td>
            <td>${row.status}</td>
          `;
          tableBody.appendChild(tr);
        });
      } else {
        alert("❌ Failed to load data.");
      }
    })
    .catch(err => {
      console.error("Error loading dashboard data:", err);
      alert("❌ Error loading dashboard data. See console for details.");
    });
});

function approveOvertime() {
  alert("✅ Overtime Approved.");
}

function markAbsence() {
  alert("⚠️ Absence Marked.");
}
