document.addEventListener("DOMContentLoaded", function () {
    const side = document.getElementById("sidebar")
    const close = document.getElementById("close")

    function slideBar() {
        side.style.left = "0";
    }


    function closer() {
        side.style.left = "-300px";
    }
    close.addEventListener("click", closer);

    menu.addEventListener("click", slideBar)
})

function machineStatusChange(event) {
    event.preventDefault();
    const selectElement = event.target.querySelector('select');
    const selectedOptionText = selectElement.options[selectElement.selectedIndex].text;

    const correspondingSpan = findSpanByTextContent(selectedOptionText);
    const parentDiv = correspondingSpan.parentElement;
    const machineImage = parentDiv.querySelector('img');
    const formdata = new FormData(event.target);
    let stat = "available";
    if (machineImage.src.includes("red")) {
        stat = "unavailable";
    }
    formdata.append("status", stat);
    fetch('machinestatusui.php', {
        method: 'POST',
        body: formdata,
    })
        .then(response => response.text())
        .then(data => {
            if (data.includes('red')) {
                correspondingSpan.parentElement.classList.remove('Available');
                machineImage.src = "img/washingred.png";
            } else if (data.includes("green")) {
                correspondingSpan.parentElement.classList.add('Available');
                machineImage.src = "img/washing.png";
            }
            else {
                console.log(data);
            }
        })
}
//find the machnine span that is equivalent to the selection text content
function findSpanByTextContent(textContent) {
    const machineSpans = document.querySelectorAll('.Machine span');
    for (const span of machineSpans) {
        if (span.textContent === textContent) {
            return span;
        }
    }
    return null;
}