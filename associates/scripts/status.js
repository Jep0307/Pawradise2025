function escapeHTML(str) {
    const element = document.createElement('div');
    if (str) {
        element.innerText = str;
        element.textContent = str;
    }
    return element.innerHTML;
}

function getApplicationStatus() {
    const userEmail = localStorage.getItem("userEmail");


    fetch('../../shared_components/applications/get_application_status.php',{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: userEmail })
    })
        .then(res => res.json())
        .then(data => {
            const applicationList = document.getElementById('applicationList');
            applicationList.innerHTML = '';

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(application => {
                    applicationList.innerHTML += `
                        <tr>
                            <td>${application.id}</td>
                            <td>${escapeHTML(application.created_at)}</td>
                            <td>${escapeHTML(application.status)}</td>
                        </tr>
                    `;
                });
            } else {
                applicationList.innerHTML = '<div>No applications found.</div>';
            }
        })
}

getApplicationStatus();
