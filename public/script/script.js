// Code pour les message flash
document.addEventListener('DOMContentLoaded', function () {
    const flashMessages = document.querySelectorAll('.flash-message');
    setTimeout(function () {
        flashMessages.forEach(function (message) {
            message.classList.add('hidden');
        });
    }, 2000);
});
