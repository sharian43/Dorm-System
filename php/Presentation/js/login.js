function Login(event) {
    event.preventDefault();

    fetch('LoginUI.php', {
        method: 'POST',
        body: new FormData(event.target),
    })
        .then(response => response.text())
        .then(data => {
            if (data.includes('time')) {
                alert("Correct Credentials");
                window.location.href = "http://localhost/dorm-System/php/presentation/timeslotView.php";

            }
            else if (data.includes("status")) {
                alert("correct Credentials");
                window.location.href = "http://localhost/Dorm-System/php/Presentation/MachineStatusView.php";
            }

            else if (data.includes("staff")) {
                alert("Correct Crentials");
                window.location.href = "http://localhost/Dorm-System/php/Presentation/GenerateMaintenanceView.php";
            }
            else if (data.includes("incorrect")) {
                alert("Incorrect User Credentials");
            }
            else {
                console.log(data);
            }
        })
}