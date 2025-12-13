function downloadReport(type) {
  window.location.href = `../php/download_report.php?type=${type}`;
}

let students = [];
const reportTable = document.getElementById("reportTable");
const searchInput = document.getElementById("searchInput");

searchInput.addEventListener("input", () => {
  const keyword = searchInput.value.toLowerCase();
  const filtered = students.filter(s =>
    s.name.toLowerCase().includes(keyword)
  );
  renderTable(filtered);
});

function renderTable(data) {
  reportTable.innerHTML = '';
  if (data.length === 0) {
    reportTable.innerHTML = `<tr><td colspan="6" style="text-align:center; color:#888;">No data found.</td></tr>`;
    return;
  }

  data.forEach(student => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${student.name}</td>
      <td>${student.office}</td>
      <td>${student.attendance}</td>
      <td>${student.late}</td>
      <td>${student.absences}</td>
      <td>${student.evalScore}</td>
    `;
    reportTable.appendChild(row);
  });
}

function fetchData() {
  fetch('../php/report_fetch.php')
    .then(res => res.json())
    .then(data => {
      students = data;
      renderTable(students);
    })
    .catch(err => {
      console.error("Fetch error:", err);
    });
}

window.addEventListener("DOMContentLoaded", fetchData);
