// =============================
// AUTO RUN
// =============================
document.addEventListener("DOMContentLoaded", function () {
    loadHeader();
    loadFooter();
    loadItems();
    initImageUploader();
    initAddToCart();
});

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
    fetch('../php/user_info.php')
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
                authLink.href = "../php/logout.php";

            } else {

                fullName.textContent = "Guest";
                email.textContent = "Please log in";

                authLink.textContent = "Login";
                authLink.href = "../html/login.html";
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

    return fetch('../html/header.html')
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

    fetch('../html/footer.html')
        .then(resp => resp.text())
        .then(html => {
            container.innerHTML = html;
        })
        .catch(err => console.error('Failed to load footer:', err));
}

async function loadItems() {
    try {
        const response = await fetch("../php/get_items_for_index.php");
        const items = await response.json();

        // const text = await response.text();   // change this
        // console.log(text); 

        const container = document.getElementById("items-container");

        container.innerHTML = "";

        items.forEach(item => {
            const card = createItemCard(item);
            container.appendChild(card);
        });

    } catch (error) {
        console.error("Error loading items:", error);
    }
}

function createItemCard(item) {

    const div = document.createElement("div");
    div.classList.add("item-card");

    const image = item.photo 
        ? `../uploads/items/${item.photo}` 
        : "../images/no-image.png";

    div.innerHTML = `
        <img src="${image}" class="item-image">
        <h3>${item.title}</h3>
        <p>${item.category}</p>
        <strong>R ${item.price}</strong>
    `;

    // Redirect when clicked
    div.addEventListener("click", () => {
        window.location.href = `item.html?id=${item.id}`;
    });

    return div;
}

// =============================
// IMAGE UPLOAD + PREVIEW
// =============================
function initImageUploader() {

    const placeholder = document.getElementById("div-placeholder");
    const fileInput = document.getElementById("item-photos");
    const preview = document.getElementById("image-preview");

    // Prevent errors if page doesn't contain the form
    if (!placeholder || !fileInput) return;

    // Click placeholder to open file dialog
    placeholder.addEventListener("click", () => {
        fileInput.click();
    });

    // Preview selected images
    fileInput.addEventListener("change", () => {

        if (!preview) return;

        preview.innerHTML = "";

        const files = fileInput.files;

        if (files.length > 5) {
            alert("Maximum 5 images allowed");
            fileInput.value = "";
            return;
        }

        Array.from(files).forEach(file => {

            if (!file.type.startsWith("image/")) return;

            const reader = new FileReader();

            reader.onload = function (e) {

                const img = document.createElement("img");
                img.src = e.target.result;
                img.classList.add("preview-image");

                preview.appendChild(img);
            };

            reader.readAsDataURL(file);

        });
    });
}

// =============================
// SET ITEM ID FOR ADD TO CART
// =============================
function initAddToCart() {

    const form = document.getElementById("add-to-cart");
    const hiddenInput = document.getElementById("item-id");

    // If page doesn't contain cart form, exit
    if (!form || !hiddenInput) return;

    // Get ID from URL
    const params = new URLSearchParams(window.location.search);
    const itemId = params.get("id");

    if (!itemId) {
        console.error("No item ID found in URL");
        return;
    }

    // Insert ID into hidden input
    hiddenInput.value = itemId;
}