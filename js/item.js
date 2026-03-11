document.addEventListener("DOMContentLoaded", loadItem);

async function loadItem() {

    const params = new URLSearchParams(window.location.search);
    const itemId = params.get("id");

    if (!itemId) return;

    const response = await fetch(`../php/get_single_item.php?id=${itemId}`);
    const item = await response.json();

    document.getElementById("item-title").textContent = item.title;
    document.getElementById("item-price").textContent = "R " + item.price;

    // adding css class to item-price element and styling them in style.css
    const priceElement = document.getElementById("item-price");
    priceElement.classList.add("price-style");

    // Adding category label and styling it in style.css
    document.getElementById("item-category").innerHTML = '<span class="item-category">Category: ' + item.category + '</span>';

    // Adding condition label and styling it in style.css
    document.getElementById("item-condition").innerHTML = '<span class="item-category">Condition: ' + item.item_condition + '</span>';

    // Adding location icon and styling it in style.css
    document.getElementById("item-location").innerHTML = '<i class="bi bi-geo-alt"></i> ' + item.item_location;    
    document.getElementById("item-description").textContent = item.item_description;

    const imageContainer = document.getElementById("item-images");

    const photos = JSON.parse(item.photos || "[]");

    photos.forEach(photo => {

        const img = document.createElement("img");
        img.src = `../uploads/items/${photo}`;
        img.classList.add("item-page-image");

        imageContainer.appendChild(img);

    });

}