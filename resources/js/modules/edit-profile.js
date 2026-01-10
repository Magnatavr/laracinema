export default function editProfile() {
    // Предпросмотр аватарки
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');

    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    avatarPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Маска для телефона
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');

            if (value.length === 11 && value.startsWith('8')) {
                value = '7' + value.slice(1);
            }

            if (value.length === 10) {
                value = '7' + value;
            }

            if (value.length > 0 && !value.startsWith('7')) {
                value = '7' + value;
            }

            if (value.length > 11) {
                value = value.slice(0, 11);
            }

            if (value.length > 1) {
                value = '+' + value;
            }

            e.target.value = value;
        });
    }
}
