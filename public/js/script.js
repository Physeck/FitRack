document.addEventListener('DOMContentLoaded', function () {
    function handleAlerts() {
        const alerts = document.querySelectorAll('.alert-profile');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }, 2000);
        });
    }

    function verifyPassword() {
        const verifyPasswordButton = document.getElementById('verifyPasswordButton');
        const verificationMessage = document.getElementById('verificationMessage');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const verifyPasswordRoute = "/profile/verifyPassword";

        if (!verifyPasswordButton || !verificationMessage) {
            console.error('Required elements not found in the DOM.');
            return;
        }

        verifyPasswordButton.addEventListener('click', function () {
            const currentPassword = document.getElementById('currentPassword').value;

            verificationMessage.style.display = 'none';
            verificationMessage.classList.remove('alert-danger', 'alert-success');
            verificationMessage.textContent = '';

            if (currentPassword.trim() === '') {
                verificationMessage.classList.add('alert-danger');
                verificationMessage.textContent = 'Password cannot be empty.';
                verificationMessage.style.display = 'block';
                setTimeout(() => {
                    verificationMessage.style.display = 'none';
                }, 3000);
                return;
            }

            if (currentPassword.length < 8) {
                verificationMessage.classList.add('alert-danger');
                verificationMessage.textContent = 'Password must be at least 8 characters long.';
                verificationMessage.style.display = 'block';
                setTimeout(() => {
                    verificationMessage.style.display = 'none';
                }, 3000);
                return;
            }

            const formData = new FormData();
            formData.append('password', currentPassword);
            formData.append('_token', csrfToken);

            fetch(verifyPasswordRoute, {
                method: 'POST',
                body: formData,
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Server error');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response:', data);
                    if (data.status === 'success') {
                        var verifyPasswordModal = new bootstrap.Modal(document.getElementById('verifyPasswordModal'));
                        verifyPasswordModal.hide();

                        var changePasswordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
                        changePasswordModal.show();

                        verificationMessage.classList.add('alert-success');
                        verificationMessage.textContent = 'Password verified successfully!';
                        verificationMessage.style.display = 'block';
                        setTimeout(() => {
                            verificationMessage.style.display = 'none';
                        }, 3000);
                    } else {
                        verificationMessage.classList.add('alert-danger');
                        verificationMessage.textContent = 'Incorrect password. Please try again.';
                        verificationMessage.style.display = 'block';
                        setTimeout(() => {
                            verificationMessage.style.display = 'none';
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Error during password verification:', error);
                    verificationMessage.classList.add('alert-danger');
                    verificationMessage.textContent = 'An error occurred. Please try again later.';
                    verificationMessage.style.display = 'block';
                });
        });
    }

    function updatePassword() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const updatePasswordRoute = "/profile/updatePassword";
        const verificationMessage = document.getElementById('verificationMessage2');
        const submitChangePassword = document.getElementById('submitChangePassword');

        verificationMessage.style.display = 'none';
        verificationMessage.classList.remove('alert-danger', 'alert-success');
        verificationMessage.textContent = '';

        submitChangePassword.addEventListener('click', function(){
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('newPassword_confirmation').value;

            if (newPassword.trim() === '' || confirmPassword.trim() === '') {
                verificationMessage.classList.add('alert-danger');
                verificationMessage.textContent = 'Password fields cannot be empty.';
                verificationMessage.style.display = 'block';
                return;
            }

            if (newPassword.length < 8 || confirmPassword.length < 8) {
                verificationMessage.classList.add('alert-danger');
                verificationMessage.textContent = 'Password must be at least 8 characters long.';
                verificationMessage.style.display = 'block';
                return;
            }

            if (newPassword !== confirmPassword) {
                verificationMessage.classList.add('alert-danger');
                verificationMessage.textContent = 'Passwords do not match!';
                verificationMessage.style.display = 'block';
                return;
            }

            const formData = new FormData();
            formData.append('newPassword', newPassword);
            formData.append('newPassword_confirmation', confirmPassword);
            formData.append('_token', csrfToken);

            fetch(updatePasswordRoute, {
                method: 'POST',
                body: formData,
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to update password. Please check your input.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        verificationMessage.classList.add('alert-success');
                        verificationMessage.textContent = 'Password Updated Successfully';
                        verificationMessage.style.display = 'block';

                        setTimeout(() => window.location.reload(), 500);
                    } else {
                        verificationMessage.classList.add('alert-danger');
                        verificationMessage.textContent = data.message || 'An error occurred.';
                        verificationMessage.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    verificationMessage.classList.add('alert-danger');
                    verificationMessage.textContent = 'An unexpected error occurred. Please try again later.';
                    verificationMessage.style.display = 'block';
                });
        });
    }


    handleAlerts();
    verifyPassword();
    updatePassword();
});
