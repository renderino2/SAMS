document.addEventListener('DOMContentLoaded', function () {
  fetch('../php/hr-skill-inventory.php')
    .then(response => {
      if (!response.ok) {
        throw new Error("Failed to fetch skill data.");
      }
      return response.json();
    })
    .then(data => {
      const tableBody = document.getElementById('skillTableBody');
      tableBody.innerHTML = '';

      if (!Array.isArray(data) || data.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="8">No skill data found.</td>`;
        tableBody.appendChild(row);
        return;
      }

      let unverifiedCount = 0;
      data.forEach((skill, index) => {
        if (skill.status === 'Not Verified') unverifiedCount++;

        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${index + 1}</td>
          <td>${skill.full_name}</td>
          <td>${skill.student_id_number}</td>
          <td>${skill.office || '-'}</td>
          <td>${skill.skill_name}</td>
          <td>${skill.updated_at}</td>
          <td>${skill.status}</td>
          <td>${skill.note || ''}</td>
        `;
        tableBody.appendChild(row);
      });

      document.getElementById('pending-count').textContent = unverifiedCount;
    })
    .catch(error => {
      console.error("Error:", error);
      alert("Failed to load skill data.");
    });
});
