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


