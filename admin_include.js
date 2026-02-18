document.addEventListener("DOMContentLoaded", function () {
    loadHeader();
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
