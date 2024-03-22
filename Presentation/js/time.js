document.addEventListener("DOMContentLoaded", function () {
    const daysOfWeek = {
        "Sunday": 0,
        "Monday": 1,
        "Tuesday": 2,
        "Wednesday": 3,
        "Thursday": 4,
        "Friday": 5,
        "Saturday": 6
      };

    function scheduleDynam() {
        if (schedule.readyState === XMLHttpRequest.DONE) {
            if (schedule.status === 200) {
                let scheduletimes = schedule.responseText;
                dynamic.innerHTML = scheduletimes;
                setupEventListeners();
            }
        }
    }

    function findMachineElement(element) {
        while (element && !element.querySelector('span')) {
            element = element.previousElementSibling;
        }
        return element;
    }

    function setupEventListeners() {
        const days = document.querySelectorAll(".days");
        days.forEach(day => {
            day.addEventListener("click", function () {
                let dayName = this.textContent.trim();
                let dayNumber = daysOfWeek[dayName];
                const scheduleRequest = new XMLHttpRequest();
                scheduleRequest.open('POST', 'TimeslotUI.php', true);
                scheduleRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                scheduleRequest.onreadystatechange = function () {
                    if (scheduleRequest.readyState === XMLHttpRequest.DONE) {
                        if (scheduleRequest.status === 200) {
                            let scheduletimes = scheduleRequest.responseText;
                            console.log(scheduletimes);
                            dynamic.innerHTML = scheduletimes;
                            setupEventListeners(); // Re-attach event listeners
                        } else {
                            alert("Error Occurred");
                        }
                    }
                };
                let datas = "selectedDay=" + encodeURIComponent(dayNumber);
                scheduleRequest.send(datas);
            });
        });

        const timeSlots = document.querySelectorAll(".timeSlot");
        timeSlots.forEach(slot => {
            slot.addEventListener("click", function () {
                const fixed = this;
                let timeSlot = this.textContent.trim();

                //Find the span element to determine which machine in the database's timeslot
                let machineFinder = findMachineElement(this);
                let machine = machineFinder.querySelector('span').textContent;

                let timeRequest = new XMLHttpRequest();
                timeRequest.open('POST', 'TimeslotUI.php', true);
                timeRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                timeRequest.onreadystatechange = function () {
                    if (timeRequest.readyState === XMLHttpRequest.DONE) {
                        if (timeRequest.status === 200) {
                            let scheduler = timeRequest.responseText;
                            if (scheduler == "success") {
                                alert("Timeslot Reserved")
                                fixed.classList.add('selected');
                            } else if (scheduler == "unavailable") {
                                alert("Timeslot Not Available");
                            } else if (scheduler = "limited") {
                                alert("Timeslot Reservation Limit Reached")
                            } else {
                                console.log(scheduler);
                                alert("Failed To Reserve Timeslot.");
                            }
                        } else {
                            alert("Error Occurred");
                        }
                    }
                };
                if(typeof dayNumber === undefined){
                    dayNumber = null
                }
                let data = "timeslot=" + encodeURIComponent(timeSlot) + "&machine=" + encodeURIComponent(machine) + "&selectedDay=" + encodeURIComponent(dayNumber);
                timeRequest.send(data);
            });
        });
    }
})

function updateLocalTime() {
    const timeNow = new Date();
    const current = timeNow.toLocaleString();

    document.getElementById("currTime").textContent = current;
}

setInterval(updateLocalTime, 1000);