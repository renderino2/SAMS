// js/contract.js

document.addEventListener('DOMContentLoaded', fetchContracts);

function fetchContracts() {
    fetch('../php/contract-fetch.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error! status: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (!Array.isArray(data)) {
                throw new Error('Invalid response format');
            }
            renderContracts(data);
        })
        .catch(error => {
            console.error("Fetch failed:", error);
            document.querySelector('#contractList').innerHTML = `
                <tr><td colspan="7">Error loading contracts.</td></tr>
            `;
        });
}

function renderContracts(contracts) {
    const tableBody = document.getElementById('contractList');
    tableBody.innerHTML = '';

    if (contracts.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="7" class="no-data">No contract records found.</td></tr>`;
        return;
    }

    contracts.forEach(contract => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${contract.full_name}</td>
            <td>${contract.student_id_number}</td>
            <td>${contract.office}</td>
            <td>${contract.start_date}</td>
            <td>${contract.end_date}</td>
            <td>${contract.status}</td>
            <td><button>Edit</button></td>
        `;
        tableBody.appendChild(row);
    });
}
