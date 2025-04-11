document.addEventListener("DOMContentLoaded", function () {
    const btn = document.querySelector(".btn");
    
    if (btn) {
        btn.addEventListener("click", function (event) {
            alert("Redirecting to Sign Up Page!");
        });
    }

    const navLinks = document.querySelectorAll("nav ul li a");
    navLinks.forEach(link => {
        link.addEventListener("mouseover", function () {
            link.style.color = "#ff9800";
        });
        link.addEventListener("mouseout", function () {
            link.style.color = "white";
        });
    });
});
