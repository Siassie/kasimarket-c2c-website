// =============================
// DROPDOWN LOGIC
// =============================
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


// =============================
// SESSION CHECK
// =============================
function updateUserInfo(container) {
    fetch('user_info.php')
        .then(res => res.json())
        .then(user => {

            const fullName = container.querySelector('#userFullName');
            const email = container.querySelector('#userEmail');
            const authLink = container.querySelector('#authLink');

            if (!fullName || !email || !authLink) return;

            if (user.name !== 'Guest') {

                // Update name
                fullName.textContent = user.name + " " + user.surname;

                // Update email
                email.textContent = user.email;

                // Update link
                authLink.textContent = "Logout";
                authLink.href = "logout.php";

            } else {

                fullName.textContent = "Guest";
                email.textContent = "Please log in";

                authLink.textContent = "Login";
                authLink.href = "login.html";
            }
        })
        .catch(err => console.error('User info failed:', err));
}


// =============================
// LOAD HEADER
// =============================
function loadHeader(containerId = 'header') {
    const container = document.getElementById(containerId);
    if (!container) return Promise.resolve();

    return fetch('header.html')
        .then(resp => resp.text())
        .then(html => {
            container.innerHTML = html;

            initHeaderDropdown(container);
            updateUserInfo(container);
        })
        .catch(err => console.error('Failed to load header:', err));
}


// =============================
// LOAD FOOTER
// =============================
function loadFooter(containerId = 'footer') {
    const container = document.getElementById(containerId);
    if (!container) return;

    fetch('footer.html')
        .then(resp => resp.text())
        .then(html => {
            container.innerHTML = html;
        })
        .catch(err => console.error('Failed to load footer:', err));
}


// =============================
// AUTO RUN
// =============================
document.addEventListener("DOMContentLoaded", function () {
    loadHeader();
    loadFooter();
});