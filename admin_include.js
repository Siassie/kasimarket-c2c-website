document.addEventListener("DOMContentLoaded", function () {
    loadHeader();
    loadItems();
    loadUsers();
});

function loadHeader(containerId = 'admin-header') {
    const container = document.getElementById(containerId);
    if (!container) {
        console.error("Header container not found.");
        return;
    }

    fetch('admin_header.html')
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to fetch header file.");
            }
            return response.text();
        })
        .then(html => {
            container.innerHTML = html;
        })
        .catch(error => {
            console.error("Error loading header:", error);
        });
}

function loadItems() {
    fetch('get_items.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector("#items-table tbody");
            tableBody.innerHTML = "";

            data.forEach(item => {
                const row = `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.title}</td>
                        <td>${item.price}</td>
                        <td>${item.category}</td>
                        <td>${item.item_condition}</td>
                        <td>${item.item_location}</td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error("Error loading items:", error));
}

function loadUsers() {
    fetch('get_users.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector("#items-table tbody");
            tableBody.innerHTML = "";

            data.forEach(item => {
                const row = `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.name}</td>
                        <td>${item.surname}</td>
                        <td>${item.email}</td>
                        <td>${item.password}</td>
                        <td>${item.role}</td>
                        <td>${item.created_at}</td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error("Error loading users:", error));
}
