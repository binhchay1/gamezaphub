document.addEventListener("DOMContentLoaded", function () {
    window.addEventListener("load", function () {
        document.getElementById("loader").style.opacity = "0";
        document.getElementById("loader").style.transition = "opacity 0.5s ease";
        setTimeout(function () {
            document.getElementById("preloader").style.display = "none";
            document.getElementById("loader").style.display = "none";
        }, 500);
    });
});