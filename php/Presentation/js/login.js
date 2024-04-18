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
                window.location.href = "http://localhost/Dorm-System/php/Presentation/MachineStatusView.php"
            }
            else if (data.includes("incorrect")) {
                alert("Incorrect User Credentials");
            }
            else if (data.includes("staff")) {
                window.location.href = "http://localhost/Dorm-System/php/Presentation/AuthenticateTicketView.php"
            }
            else {
                console.log(data);
            }
        })
}