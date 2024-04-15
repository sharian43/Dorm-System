function submitRequest() {
    const issueDescription = document.getElementById('issue_description').value;
    const formalRequest = new XMLHttpRequest;
    formalRequest.open('POST', 'maintenanceRequest.php', true);
    formalRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    formalRequest.onreadystatechange = function () {
        if (formalRequest.readyState === XMLHttpRequest.DONE) {
            if (formalRequest.status === 200) {
                let formResponse = formalRequest.responseText;
                if (formResponse.includes("success")) {
                    document.getElementById('requestForm').style.display = 'None';
                    document.getElementById('confirmationMessage').style.display = 'block';
                } else {
                    console.log(formResponse);
                    alert("Request not submitted");
                }
            } else {
                alert("Error Occurred");
            }
        }
    };

    let data = "issue=" + encodeURIComponent(issueDescription);
    formalRequest.send(data);
}