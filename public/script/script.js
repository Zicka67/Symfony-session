document.addEventListener('DOMContentLoaded', function () {
            const flashMessages = document.querySelectorAll('.flash-message');
            setTimeout(function () {
                flashMessages.forEach(function (message) {
                    message.classList.add('hidden');
                });
            }, 2000);
        });


        var btnScrollTop = document.getElementById("scroll-to-top");

        btnScrollTop.addEventListener("click", function() {
            window.scrollTo({
                top: 0,
                behavior: "smooth",
                duration: 5000
            });
        });