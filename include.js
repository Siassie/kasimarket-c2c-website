document.addEventListener("DOMContentLoaded", function () {

    // Load Header
    fetch("header.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("header").innerHTML = data;

            // 🔥 INIT DROPDOWN AFTER HEADER LOADS
            initHeaderDropdown();
        });

    // Load Footer (if you have one)
    fetch("footer.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("footer").innerHTML = data;
        });

});
