
document.addEventListener("DOMContentLoaded", function () {
    const side = document.getElementById("sidebar")
    const close = document.getElementById("close")
    console.log("Work");

    function slideBar() {
        side.style.left = "0";
    }


    function closer() {
        side.style.left = "-300px";
    }
    close.addEventListener("click", closer);

    menu.addEventListener("click", slideBar)
})