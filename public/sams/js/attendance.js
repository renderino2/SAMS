document.addEventListener("DOMContentLoaded", () => {
  const timeInBtn = document.getElementById("timeInBtn");
  const timeOutBtn = document.getElementById("timeOutBtn");

  const nameEl = document.getElementById("studentName");
  const idEl = document.getElementById("studentId");
  const officeEl = document.getElementById("assignedOffice");

  const todayIn = document.getElementById("todayIn");
  const todayOut = document.getElementById("todayOut");
  const todayStatus = document.getElementById("todayStatus");
  const loginStatus = document.getElementById("loginStatus");
  const dtrTable = document.getElementById("dtrTable");

  let sessionData = {};

  // Load session info
  fetch("../php/session-data.php")
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      sessionData = data;
      nameEl.textContent = data.full_name;
      idEl.textContent = data.student_id_number;
      officeEl.textContent = data.office;
      loginStatus.textContent = "🔓 Logged In";
      loadDTR();
    } else {
      alert("Session expired. Redirecting to login.");
      window.location.href = "../html/login.html";
    }
  });


  // Time In
  timeInBtn.addEventListener("click", () => {
    fetch("../php/attendance.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ action: "time_in" }),
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          todayIn.textContent = data.time_in;
          todayStatus.textContent = "✅ Present";
          loginStatus.textContent = "✅ Logged In";
          timeInBtn.disabled = true;
          timeOutBtn.disabled = false;
          loadDTR();
        } else {
          alert(data.message || "Time In failed.");
        }
      });
  });

  // Time Out
  timeOutBtn.addEventListener("click", () => {
    fetch("../php/attendance.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ action: "time_out" }),
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          todayOut.textContent = data.time_out;
          loginStatus.textContent = "✅ Logged Out";
          timeOutBtn.disabled = true;
          loadDTR();
        } else {
          alert(data.message || "Time Out failed.");
        }
      });
  });

  function loadDTR() {
    fetch("../php/attendance.php?action=fetch")
      .then(res => res.json())                                    
      .then(data => {
        dtrTable.innerHTML = "";
        data.records.forEach(r => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${r.date}</td>
            <td>${r.name}</td>
            <td>${r.student_id}</td>
            <td>${r.office}</td>
            <td>${r.time_in || '--'}</td>
            <td>${r.time_out || '--'}</td>
            <td>${r.status}</td>
          `;
          dtrTable.appendChild(row);
        });
      });
  }
});
