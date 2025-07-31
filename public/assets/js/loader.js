
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(function () {
            const loader = document.getElementById("loader");
            if (loader) {
                loader.classList.add("hide");
                setTimeout(() => loader.style.display = "none", 500); // hide after fade
            }s
        }, 1000);
    });
