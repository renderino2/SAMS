document.addEventListener('DOMContentLoaded', () => {
    fetch('http://localhost/sams/php/hr-dashboard.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error ${response.status}`);
            }
            return response.json(); // This will throw if the response is HTML (e.g., PHP error)
        })
        .then(data => {
            const tableBody = document.getElementById('attendance-summary-body');
            tableBody.innerHTML = ''; // Clear table body before inserting

            if (Array.isArray(data) && data.length > 0) {
                data.forEach((record, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${record.student_id}</td>
                        <td>${record.full_name}</td>
                        <td>${record.total_attendance}</td>
                        <td>${record.incomplete_logs}</td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                const row = document.createElement('tr');
                row.innerHTML = `<td colspan="5">No attendance data found.</td>`;
                tableBody.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Dashboard fetch error:', error);
            const tableBody = document.getElementById('attendance-summary-body');
            tableBody.innerHTML = `<tr><td colspan="5">Error loading data.</td></tr>`;
        });
});
