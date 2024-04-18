
function Request(event) {
    event.preventDefault();

    fetch('GenerateMaintenanceUI.php', {
        method: 'POST',
        body: new FormData(event.target),
    })
        .then(response => response.text())
        .then(data => {
            if (data.includes('stored')) {
                alert("Request Submitted");
            }
            else if (data.includes("incorrect")) {
                alert("Please Complete Form");
            }
            else {
                alert("Error Occured")
                console.log(data);
            }
        })
}