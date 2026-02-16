function initHeaderDropdown(container) {
    const accountIcon = container.querySelector("#accountIcon");
    const dropdownMenu = container.querySelector("#dropdownMenu");

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


function updateAuthLink(container) {
    fetch('check_session.php')
        .then(res => res.json())
        .then(session => {
            const authLink = container.querySelector('#authLink');
            if (!authLink) return;

            if (session.loggedIn) {
                authLink.textContent = "Log out";
                // authLink.href = "logout.php";
            } else {
                authLink.textContent = "Login";
                authLink.href = "login.html";
            }
        })
        .catch(err => console.error('Session check failed:', err));
}


function loadHeader(containerId = 'header') {
    const container = document.getElementById(containerId);
    if (!container) return;

    fetch('header.html')
        .then(resp => resp.text())
        .then(html => {
            container.innerHTML = html;

            // Initialize dropdown
            initHeaderDropdown(container);

            // Update login/logout link
            updateAuthLink(container);
        })
        .catch(err => console.error('Failed to load header:', err));
}

// Auto run when page loads
window.addEventListener('DOMContentLoaded', function () {
    loadHeader();
});