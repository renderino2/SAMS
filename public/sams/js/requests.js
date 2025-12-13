document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("requestForm");
  const tableBody = document.getElementById("requestsBody");

  // Load existing requests
  fetch("../php/requests-fetch.php")
    .then(res => res.json())
    .then(data => {
      tableBody.innerHTML = "";

      if (data.success && data.requests.length > 0) {
        data.requests.forEach((req, index) => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${index + 1}</td>
            <td>${req.request_type}</td>
            <td>${req.message}</td>
            <td><span class="badge ${req.status.toLowerCase()}">${req.status}</span></td>
            <td>${req.created_at}</td>
          `;
          tableBody.appendChild(row);
        });
      } else {
        tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center;">No requests submitted.</td></tr>`;
      }
    });

  // Handle new request form submission
  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const request_type = document.getElementById("request_type").value;
    const message = document.getElementById("message").value;

    fetch("../php/requests-submit.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ request_type, message })
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("✅ Request submitted.");
          location.reload(); // Reload page to show updated table
        } else {
          alert("❌ Failed to submit request.");
        }
      });
  });
});
