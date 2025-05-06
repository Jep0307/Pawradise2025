document.addEventListener('DOMContentLoaded', () => {
    const usersList = document.getElementById('usersList');

    function escapeHTML(str) {
        if (!str) return '';
        return str.replace(/[&<>"]|'/g, m => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[m]));
    }

    function loadUsers(search = '') {
        const url = search ? `../components/get_staff-and-staff-admin.php?search=${encodeURIComponent(search)}` : '../components/get_staff-and-staff-admin.php';

        fetch(url)
            .then(res => res.json())
            .then(data => {
                usersList.innerHTML = '';

                if (data.length === 0) {
                    usersList.innerHTML = '<tr><td colspan="7" style="padding: 1rem; text-align: center;">No users found.</td></tr>';
                    return;
                }

                data.forEach(users => {
                    usersList.innerHTML += `
                        <tr>
                            <td>${users.id}</td>
                            <td>${escapeHTML(users.email)}</td>
                            <td>${escapeHTML(users.password)}</td>
                            <td>${escapeHTML(users.created_at)}</td>
                            <td>${escapeHTML(users.role)}</td>
                        </tr>
                    `;
                });
                
            })
            .catch(err => {
                console.error('Error loading pets:', err);
            });
    }

    loadUsers();
});

