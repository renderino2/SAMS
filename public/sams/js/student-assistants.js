// /js/student-assistants.js
document.addEventListener("DOMContentLoaded", () => {
  fetch("../php/student-assistants.php")
    .then((response) => {
      if (!response.ok) throw new Error("Network response was not ok");
      return response.json();
    })
    .then((data) => {
      const tableBody = document.querySelector("#studentAssistantsTable tbody");

      data.forEach((assistant) => {
        const row = document.createElement("tr");

        row.innerHTML = `
          <td>${assistant.student_id_number}</td>
          <td>${assistant.full_name}</td>
          <td>${assistant.email}</td>
          <td>${assistant.office}</td>
          <td>${assistant.status}</td>
        `;

        tableBody.appendChild(row);
      });
    })
    .catch((error) => {
      console.error("Fetch failed:", error);
    });
});
