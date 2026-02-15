function initHeaderDropdown() {
    const accountIcon = document.getElementById("accountIcon");
    const dropdownMenu = document.getElementById("dropdownMenu");

    if (!accountIcon || !dropdownMenu) return;

    accountIcon.addEventListener("click", function (e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle("show");
    });

    document.addEventListener("click", function () {
        dropdownMenu.classList.remove("show");
    });

    dropdownMenu.addEventListener("click", function (e) {
        e.stopPropagation();
    });
}

function loadHeader(containerId = 'header') {
    const container = document.getElementById(containerId);
    if (!container) return;

    // Load header.html into the container
    fetch('header.html')
        .then(resp => resp.text())
        .then(html => {
            container.innerHTML = html;

            // Initialize dropdown functionality
            initHeaderDropdown();

            // Fetch user info and replace Demo User content
            fetch('user_info.php')
                .then(res => res.json())
                .then(user => {
                    const nameEl = container.querySelector('#dropdownMenu .dropdown-header strong');
                    const emailEl = container.querySelector('#dropdownMenu .dropdown-header small');
                    if (nameEl && emailEl) {
                        nameEl.innerText = user.name + (user.surname ? " " + user.surname : "");
                        emailEl.innerText = user.email;
                    }
                })
                .catch(err => console.error('Failed to fetch user info:', err));
        })
        .catch(err => console.error('Failed to load header:', err));
}

// Automatically load header when the page loads
window.addEventListener('load', function () {
    loadHeader();
});