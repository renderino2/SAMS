document.addEventListener('DOMContentLoaded', loadEvaluations);
const tableBody = document.querySelector('.data-table tbody');
const searchInput = document.getElementById('searchName');
const filterOffice = document.getElementById('filterOffice');
const filterStatus = document.getElementById('filterStatus');

let evaluations = [];

function loadEvaluations() {
  fetch('../php/eval_fetch.php')  // 🔄 revised path
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        evaluations = res.data;
        renderTable(evaluations);
      } else {
        tableBody.innerHTML = `<tr><td colspan="7" class="no-data">No evaluation records found.</td></tr>`;
      }
    })
    .catch(err => {
      tableBody.innerHTML = `<tr><td colspan="7" class="no-data">Failed to load evaluations.</td></tr>`;
      console.error('Fetch error:', err);
    });
}

function renderTable(data) {
  tableBody.innerHTML = '';
  if (!data.length) {
    tableBody.innerHTML = `<tr><td colspan="7" class="no-data">No evaluation records found.</td></tr>`;
    return;
  }
  data.forEach(evalRec => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${evalRec.evaluation_date}</td>
      <td>${evalRec.student_name}</td>
      <td>${evalRec.office}</td>
      <td>${evalRec.rated_by}</td>
      <td>${evalRec.average_rating}</td>
      <td>${evalRec.status}</td>
      <td>
        ${evalRec.status === 'Pending' ? `<button data-id="${evalRec.id}" class="review-btn">Mark Reviewed</button>` : '—'}
      </td>`;
    tableBody.appendChild(tr);
  });
}

tableBody.addEventListener('click', e => {
  if (e.target.classList.contains('review-btn')) {
    const id = e.target.dataset.id;
    fetch('../php/eval_action.php', {  // 🔄 revised path
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'markReviewed', id })
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) loadEvaluations();
    });
  }
});

function applyFilters() {
  const term = searchInput.value.toLowerCase();
  const office = filterOffice.value.toLowerCase();
  const status = filterStatus.value.toLowerCase();
  const filtered = evaluations.filter(ev => {
    const matchesSearch = ev.student_name.toLowerCase().includes(term) || (ev.student_id && ev.student_id.toLowerCase().includes(term));
    const matchesOffice = !office || ev.office.toLowerCase() === office;
    const matchesStatus = !status || ev.status.toLowerCase() === status;
    return matchesSearch && matchesOffice && matchesStatus;
  });
  renderTable(filtered);
}

searchInput.addEventListener('input', applyFilters);
filterOffice.addEventListener('change', applyFilters);
filterStatus.addEventListener('change', applyFilters);
