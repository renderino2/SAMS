document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("saForm");
  const tableBody = document.getElementById("saTableBody");
  const toggleBtn = document.getElementById("toggleFormBtn");
  const addForm = document.getElementById("addForm");
  const searchInput = document.getElementById("searchInput");
  const officeFilter = document.getElementById("officeFilter");

  let saData = [];

  toggleBtn.addEventListener("click", () => {
    addForm.style.display = addForm.style.display === "none" ? "block" : "none";
  });

  fetchSAs();

  function fetchSAs() {
    fetch("../php/hr-student-assistants-fetch.php")
      .then(res => res.json())
      .then(data => {
        saData = data;
        renderTable(saData);
      });
  }

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    fetch("../php/hr-student-assistants.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert("✅ Added!");
          form.reset();
          addForm.style.display = "none";
          fetchSAs();
        } else {
          alert("❌ Error: " + data.error);
        }
      });
  });

  function renderTable(data) {
    tableBody.innerHTML = "";
    if (data.length === 0) {
      tableBody.innerHTML = `<tr><td colspan="7" style="text-align:center;">No records found.</td></tr>`;
      return;
    }

    data.forEach((sa, i) => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${i + 1}</td>
        <td>${sa.name}</td>
        <td>${sa.student_id}</td>
        <td>${sa.office}</td>
        <td><span class="badge ${sa.status.toLowerCase().replace(" ", "")}">${sa.status}</span></td>
        <td><button onclick="alert('Logs not yet implemented')">Logs</button></td>
        <td><button disabled>Edit</button> <button disabled>Delete</button></td>
      `;
      tableBody.appendChild(row);
    });
  }

  searchInput.addEventListener("input", filterTable);
  officeFilter.addEventListener("change", filterTable);

  function filterTable() {
    const keyword = searchInput.value.toLowerCase();
    const office = officeFilter.value;

    const filtered = saData.filter(sa =>
      (sa.name.toLowerCase().includes(keyword) || sa.student_id.includes(keyword)) &&
      (office === "" || sa.office === office)
    );

    renderTable(filtered);
  }
});
