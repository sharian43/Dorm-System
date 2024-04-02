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

function canceller(event) {
    // Check if the clicked element has the class "sideLinks" and the text content is "cancel reservation"
    const cancelSlots = document.querySelectorAll(".timeSlotted");
    cancelSlots.forEach(slot => {
        slot.addEventListener("click", function () {
            const triumph = this;
            const slotInfo = this.textContent.split(' ');
            const time = slotInfo[0];
            const machine = slotInfo[1];
            const machineNumber = slotInfo[2];
            const day = slotInfo[3];
            console.log("Clicked:", machine);
            let cancelRequest = new XMLHttpRequest();
            cancelRequest.open('POST', 'CancelUI.php', true);
            cancelRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            cancelRequest.onreadystatechange = function () {
                if (cancelRequest.readyState === XMLHttpRequest.DONE) {
                    if (cancelRequest.status === 200) {
                        let opps = cancelRequest.responseText;
                        if (opps.includes("success")) {
                            triumph.classList.add('Unavailable');
                            alert("Reservation cancelled");
                        }
                        else {
                            console.log(opps);
                        }
                    }
                }
            }

            let trifecta = "time=" + encodeURIComponent(time) + "&machine=" + encodeURIComponent(machine + " " + machineNumber) + "&day=" + encodeURIComponent(day);
            cancelRequest.send(trifecta);
        })
    })
}