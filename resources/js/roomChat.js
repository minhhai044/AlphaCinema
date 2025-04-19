import './bootstrap';

window.Echo.join('chat.' + roomId)
    .here((users) => {
        $('#listOnline').empty();
        users.forEach(user => {
            $('#listOnline').append(renderOnlineUser(user));
        });
    })
    .joining((user) => {
        $('#listOnline').append(renderOnlineUser(user));
    })
    .leaving((user) => {
        $(`#user-${user.id}`).remove();
    })
    .listen(".real-time-chat", function (event) {
        appendMessage(event.messengerChat, false);
    })
    .error((error) => {
        console.error('Lỗi khi nhận sự kiện:', error);
    });



function renderOnlineUser(user) {
    return `
        <li id="user-${user.id}" class="p-2">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 avatar-sm me-3">
                    <img src="${user.avatar ? user.avatar : 'https://graph.facebook.com/4/picture?type=small'}"
                        class="avatar-sm rounded-circle" />
                </div>
                <div class="flex-grow-1">
                    <h5 class="font-size-14 mb-0">${user.name}</h5>
                </div>
            </div>
        </li>
    `;
}

function appendMessage(message, isMine = true) {
    let time = new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    let avatar = message.avatar || 'https://graph.facebook.com/4/picture?type=small';
    let name = isMine ? 'Bạn' : message.name;

    let html = `
        <li class="${isMine ? 'right' : ''}">
            <div class="conversation-list d-flex ${isMine ? 'justify-content-end' : ''}">
                ${!isMine ? `
                    <div class="flex-shrink-0 me-2">
                     <img src="${avatar}" alt="Avatar" class="rounded-circle" style="height: 2rem; width: 2rem;">
                    </div>
                ` : ''}
                <div class="ctext-wrap">
                    <div class="ctext-wrap-content">
                        <h5 class="conversation-name">
                            <a href="#" class="user-name">${name}</a>
                            <span class="time">${time}</span>
                        </h5>
                        <p class="mb-0">${message.messenge}</p>
                    </div>
                </div>
            </div>
        </li>
    `;

    $('.chat-conversation ul').append(html);
}

$('#sendMessenger').click(function (e) {
    let messenge = $('#messenger').val();
    if (!messenge) {
        toastr.warning('Không được để trống tin nhắn nhaa !!!');
        return;
    }
    let user_id = UserId;
    let room_chat_id = roomId;

    let formData = new FormData();
    formData.append('messenge', messenge);
    formData.append('user_id', user_id);
    formData.append('room_chat_id', room_chat_id);

    window.axios.post(routeMessenger, formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'Accept': 'application/json'
        }
    })
        .then(function (response) {
            $('#messenger').val('');
            console.log('Gửi thành công:', response.data);

            let mess = response.data.data;

            mess.avatar = window.avatarUrl || 'https://graph.facebook.com/4/picture?type=small';
            mess.name = 'Bạn';

            appendMessage(mess, true);
        })
        .catch(function (error) {
            if (error.response && error.response.status === 422) {
                let errors = error.response.data.errors;
                for (let field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        toastr.error(errors[field][0], 'Lỗi xác thực');
                    }
                }
            } else {
                toastr.error('Đã có lỗi xảy ra. Vui lòng thử lại!', 'Lỗi hệ thống');
            }
            console.log('Lỗi gửi tin nhắn:', error.response.data);
        });
});
