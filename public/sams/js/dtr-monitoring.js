document.addEventListener("DOMContentLoaded", () => {
  fetch("../php/fetch-dtr.php")
    .then(res => res.json())
    .then(data => {
      console.log("✅ Parsed JSON:", data);
      const tbody = document.getElementById("dtrTableBody");
      tbody.innerHTML = "";

      if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="9">No DTR records found.</td></tr>`;
        return;
      }

      let pending = 0;
      data.forEach((entry, index) => {
        if (entry.status === "Absent") pending++;

        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${index + 1}</td>
          <td>${entry.name}</td>
          <td>${entry.student_id}</td>
          <td>${entry.office}</td>
          <td>${entry.date}</td>
          <td>${entry.time_in}</td>
          <td>${entry.time_out}</td>
          <td><span class="status ${entry.status.toLowerCase()}">${entry.status}</span></td>
          <td><button class="review-btn" data-id="${entry.id}" data-name="${entry.name}" data-office="${entry.office}" data-date="${entry.date}">Review</button></td>
        `;
        tbody.appendChild(row);
      });

      document.getElementById("pendingCount").textContent = pending;

      // Attach click handlers after buttons are inserted
      const reviewButtons = document.querySelectorAll(".review-btn");
      reviewButtons.forEach(button => {
        button.addEventListener("click", () => {
          const id = button.dataset.id;
          const name = button.dataset.name;
          const office = button.dataset.office;
          const date = button.dataset.date;

          showDetails(id, name, office, date);
        });
      });
    })
    .catch(err => {
      console.error("⚠️ Error fetching DTR:", err);
    });
});

let selectedId = null;

function showDetails(id, name, office, date) {
  selectedId = id;
  document.getElementById("detailName").textContent = name;
  document.getElementById("detailOffice").textContent = office;
  document.getElementById("detailDate").textContent = date;
  document.getElementById("dtrDetailsPanel").style.display = "block";
}

function submitDTR(action) {
  const remarks = document.getElementById("remarksText").value;
  fetch("../php/update-dtr-status.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id: selectedId, action, remarks })
  })
    .then(res => res.json())
    .then(res => {
      alert(res.success ? "✅ Updated!" : "❌ " + res.error);
      if (res.success) location.reload();
    })
    .catch(err => {
      console.error("❌ Error updating DTR:", err);
    });
}
