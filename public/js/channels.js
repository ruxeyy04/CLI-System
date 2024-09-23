$(document).ready(function() {
    window.Echo.channel('numbers-channel')
        .listen('.number.posted', (e) => {
            const number = e.number;
            const word = e.word;

            console.log(`Number: ${number}`);
            console.log(`Word: ${word}`);
        });
    window.Echo.private('user-session.' + session_id)
        .listen('.session.logout', (e) => {
            Swal.fire({
                icon: 'info',
                title: 'Logging out...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: false,
                willClose: () => {
                    window.location.href = "{{ route('login') }}";
                }
            });
        });

});

