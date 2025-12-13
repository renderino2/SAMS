document.addEventListener('DOMContentLoaded', () => {
  const now = new Date();
  document.getElementById("currentDate").textContent = now.toLocaleDateString();

  fetch("../php/sa-dashboard.php")
    .then(res => res.text()) // get raw text first
    .then(text => {
      console.log("🔍 Raw server response:", text); // log what PHP actually sends

      let data;
      try {
        data = JSON.parse(text); // try parsing after logging
      } catch (err) {
        console.error("⚠️ JSON parse error:", err);
        return;
      }

      if (data.error) {
        alert(data.error);
        return;
      }

      document.getElementById("timeIn").textContent = data.time_in;
      document.getElementById("timeOut").textContent = data.time_out;
      document.getElementById("totalHours").textContent = data.total_hours;
      document.getElementById("contractStatus").textContent = data.contract_status;
      document.getElementById("lastRequest").textContent = data.last_request;
    })
    .catch(err => {
      console.error("⚠️ Failed to load dashboard:", err);
    });
});
