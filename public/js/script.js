document.addEventListener("DOMContentLoaded", function() {

    const formRegis = document.getElementById('form-regis');
    
    if (formRegis) {
        formRegis.addEventListener('submit', function(event) {
            event.preventDefault(); 

            const fullname = document.getElementById('fullname').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirm_password = document.getElementById('confirm_password').value;

            if (password.length < 8) {
                alert('Pendaftaran Gagal: Kata sandi harus terdiri dari minimal 8 karakter!');
                return;
            }

            if (password !== confirm_password) {
                alert('Pendaftaran Gagal: Kata sandi dan konfirmasi kata sandi tidak cocok!');
                return;
            }

            let users = JSON.parse(localStorage.getItem('si_aksi_users')) || [];

            const userExists = users.find(u => u.email === email);
            if (userExists) {
                alert('Pendaftaran Gagal: Email ini sudah terdaftar!');
                return;
            }

            const newUser = {
                fullname: fullname,
                email: email,
                password: password,
                isVerified: false 
            };
            
            users.push(newUser);
            localStorage.setItem('si_aksi_users', JSON.stringify(users));

            alert('Pendaftaran Berhasil!\n\nAkun Anda telah masuk ke sistem, namun masih menunggu verifikasi dari Administrator agar dapat digunakan.');
            window.location.href = 'index.html'; 
        });
    }

    const formLogin = document.getElementById('form-login');
    
    if (formLogin) {
        formLogin.addEventListener('submit', function(event) {
            event.preventDefault(); 

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            let users = JSON.parse(localStorage.getItem('si_aksi_users')) || [];
            const validUser = users.find(u => u.email === email && u.password === password);

            if (validUser) {
                if (validUser.isVerified === true) {
                    localStorage.setItem('si_aksi_loggedIn', JSON.stringify(validUser));
                    alert('Login Berhasil! Selamat datang, ' + validUser.fullname);
                    window.location.href = 'dashboard-user.html'; 
                } else {
                    alert('AKSES DITOLAK: Akun Anda belum diverifikasi oleh Administrator. Silakan tunggu atau hubungi admin.');
                }
            } else {
                alert('LOGIN GAGAL: Email atau Kata Sandi salah!');
            }
        });
    }

    const userTableBody = document.getElementById('user-table-body');
    
    if (userTableBody) {
        function renderTable() {
            let users = JSON.parse(localStorage.getItem('si_aksi_users')) || [];
            userTableBody.innerHTML = ''; 

            if (users.length === 0) {
                userTableBody.innerHTML = '<tr><td colspan="4" class="text-empty">Belum ada pengguna yang mendaftar.</td></tr>';
                return;
            }

            users.forEach((user, index) => {
                let statusText = user.isVerified ? 'Aktif' : 'Menunggu';
                let statusClass = user.isVerified ? 'status-active' : 'status-pending';
                
                let actionButton = '';
                if (!user.isVerified) {
                    actionButton = `<button class="btn-approve" onclick="approveUser(${index})">Approve</button>`;
                } else {
                    actionButton = `<span class="text-approved">✓ Disetujui</span>`;
                }

                let row = `
                    <tr>
                        <td>${user.fullname}</td>
                        <td>${user.email}</td>
                        <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                        <td>${actionButton}</td>
                    </tr>
                `;
                userTableBody.innerHTML += row;
            });
        }

        renderTable();

        window.approveUser = function(userIndex) {
            let users = JSON.parse(localStorage.getItem('si_aksi_users')) || [];
            users[userIndex].isVerified = true;
            localStorage.setItem('si_aksi_users', JSON.stringify(users));
            alert(`Akun ${users[userIndex].fullname} berhasil disetujui!`);
            renderTable();
        };
    }

});