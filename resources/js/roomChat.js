import './bootstrap';
window.Echo.join('chat.' + roomId)
    .here(users => {
        console.log(users);
    })
    .joining(user => {
        console.log(user);

    })
    .leaving(user => {
        console.log(user);
    })


// import './bootstrap';

// document.addEventListener('DOMContentLoaded', function () {
//     if (window.Echo) {
//         window.Echo.join('chat.' + roomId)
//             .here(users => {
//                 console.log('Here:', users);
//             })
//             .joining(user => {
//                 console.log('Joining:', user);
//             })
//             .leaving(user => {
//                 console.log('Leaving:', user);
//             });
//     } else {
//         console.error('Echo chưa khởi tạo!');
//     }
// });