const requestBody = document.getElementById('requestBody');
const pendingCount = document.getElementById('pendingCount');
const searchInput = document.getElementById('searchInput');
const typeFilter = document.getElementById('typeFilter');
const statusFilter = document.getElementById('statusFilter');
let requestData = [];

async function fetchRequests() {
  try {
    const res = await fetch('../php/oh-fetch-requests.php', {
      credentials: 'include'
    });

    const contentType = res.headers.get('content-type');

    if (!contentType || !contentType.includes('application/json')) {
      const text = await res.text(); // fallback: display HTML error
      console.error("❌ Invalid JSON. Raw server response:\n", text);
      showError("⚠️ Server returned invalid JSON. Check console.");
      return;
    }

    const result = await res.json();
    console.log("✅ Fetch Result:", result);

    if (Array.isArray(result)) {
      requestData = result;
      applyFilters();
    } else {
      console.error("⚠️ JSON Error:", result.error || "Unknown error");
      showError(result.error || "⚠️ Could not load requests.");
    }
  } catch (err) {
    console.error("❌ Fetch failed:", err);
    showError("❌ Network or server error occurred.");
  }
}

async function updateStatus(id, status) {
  try {
    const formData = new FormData();
    formData.append("id", id);
    formData.append("status", status);

    const res = await fetch("../php/update_request_status.php", {
      method: "POST",
      body: formData,
      credentials: "include"
    });

    const result = await res.json();

    if (result.success) {
      await fetchRequests(); // Refresh data after status change
    } else {
      alert("⚠️ Failed to update status: " + (result.message || "Unknown error"));
    }
  } catch (error) {
    console.error("❌ Update error:", error);
    alert("❌ Failed to update status.");
  }
}

function renderRequests(data) {
  requestBody.innerHTML = '';

  if (data.length === 0) {
    requestBody.innerHTML = `<tr><td colspan="7" style="text-align:center; color:#999;">No requests found.</td></tr>`;
    pendingCount.textContent = '0';
    return;
  }

  let pending = 0;

  data.forEach(req => {
    if (req.status === 'Pending') pending++;

    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${req.name}</td>
      <td>${req.student_id}</td>
      <td>${req.type}</td>
      <td>${req.date}</td>
      <td><span class="status ${req.status.toLowerCase()}">${req.status}</span></td>
      <td>${req.reason}</td>
      <td>
        ${req.status === 'Pending' ? `
          <button onclick="updateStatus(${req.id}, 'Approved')">Approve</button>
          <button onclick="updateStatus(${req.id}, 'Rejected')">Reject</button>
        ` : '—'}
      </td>
    `;
    requestBody.appendChild(row);
  });

  pendingCount.textContent = pending;
}

function applyFilters() {
  const keyword = searchInput.value.toLowerCase();
  const type = typeFilter.value;
  const status = statusFilter.value;

  const filtered = requestData.filter(req => {
    return (
      (req.name.toLowerCase().includes(keyword) || req.student_id.toLowerCase().includes(keyword)) &&
      (type === '' || req.type === type) &&
      (status === '' || req.status === status)
    );
  });

  renderRequests(filtered);
}

function showError(message) {
  requestBody.innerHTML = `<tr><td colspan="7" style="text-align:center; color:red;">${message}</td></tr>`;
  pendingCount.textContent = '0';
}

// 👂 Event listeners
searchInput.addEventListener('input', applyFilters);
typeFilter.addEventListener('change', applyFilters);
statusFilter.addEventListener('change', applyFilters);

// 🔁 On page load
fetchRequests();
