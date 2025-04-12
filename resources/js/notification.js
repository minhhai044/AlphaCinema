import './bootstrap';

console.log('[Realtime] Echo setup...');

// Bộ nhớ để lưu ID thông báo đã xử lý (dùng Set để tránh trùng)
const handledNotifications = new Set();

// Đăng ký kênh phù hợp với quyền của user
if (!window.hasSetupEchoListener) {
    window.hasSetupEchoListener = true;

    if (!window.user.branch_id && !window.user.cinema_id) {
        Echo.channel('notification.all')
            .listen('RealTimeNotificationEvent', (e) => handleRealtimeNotification(e));
    } else if (window.user.branch_id && !window.user.cinema_id) {
        Echo.channel(`notification.branch.${window.user.branch_id}`)
            .listen('RealTimeNotificationEvent', (e) => handleRealtimeNotification(e));
    } else if (window.user.cinema_id && !window.user.branch_id) {
        Echo.channel(`notification.cinema.${window.user.cinema_id}`)
            .listen('RealTimeNotificationEvent', (e) => handleRealtimeNotification(e));
    }
}

// Hàm xử lý thông báo realtime
function handleRealtimeNotification(e) {
    const notification = e.notification;
    const idStr = notification.id.toString(); // đảm bảo là chuỗi

    // 1. Nếu ID đã xử lý → bỏ qua
    if (handledNotifications.has(idStr)) {
        console.log(`⏭️ Đã xử lý notification ${idStr}, bỏ qua`);
        return;
    }
    handledNotifications.add(idStr);

    const $outer = $('#listNotifications');
    const $container = $('#listNotifications .simplebar-content');
    const $badge = $('#page-header-notifications-dropdown .badge');

    // 2. Nếu đã tồn tại trong DOM → bỏ qua
    if ($container.find(`[data-id="${notification.id}"]`).length > 0) {
        console.log(`⏭️ Notification ${idStr} đã có trong DOM, bỏ qua`);
        return;
    }

    // 3. Xóa dòng "Không có thông báo nào"
    $container.find('.no-notification').remove();

    // 4. Tăng badge số lượng
    if ($badge.length) {
        const count = parseInt($badge.text() || '0') + 1;
        $badge.text(count).removeClass('d-none');
    }

    // 5. Tạo thông báo mới
    const $newNotification = $(`
        <a href="${notification.link ?? 'javascript:void(0)'}" class="text-reset notification-item" data-id="${notification.id}">
            <div class="d-flex">
                <div class="flex-shrink-0 avatar-sm me-3">
                    <span class="avatar-title bg-info rounded-circle font-size-16">
                        <i class="bx bx-bell"></i>
                    </span>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 fw-bold">${notification.title}</h6>
                    <div class="font-size-13 text-muted">
                        <p class="mb-1 fw-semibold">${notification.content}</p>
                        <p class="mb-0">
                            <i class="mdi mdi-clock-outline"></i>
                            <span class="badge bg-secondary text-light ms-2">Vừa xong</span>
                        </p>
                    </div>
                </div>
            </div>
        </a>
    `);

    // 6. Thêm vào danh sách
    $container.prepend($newNotification.hide().slideDown('fast'));

    // 7. Cập nhật lại SimpleBar nếu có
    if ($outer[0] && $outer[0].SimpleBar) {
        $outer[0].SimpleBar.recalculate();
    }

    console.log(`✅ Thêm notification ${idStr} thành công`);
}



















// import './bootstrap';

// console.log('[Realtime] Echo setup...');

// // Bộ nhớ tạm để chặn thông báo bị lặp
// const handledNotifications = new Set();

// // Đăng ký lắng nghe kênh phù hợp với quyền người dùng
// if (!window.hasSetupEchoListener) {
//     window.hasSetupEchoListener = true;

//     if (!window.user.branch_id && !window.user.cinema_id) {
//         Echo.channel('notification.all')
//             .listen('RealTimeNotificationEvent', (e) => handleRealtimeNotification(e));
//     } else if (window.user.branch_id && !window.user.cinema_id) {
//         Echo.channel(`notification.branch.${window.user.branch_id}`)
//             .listen('RealTimeNotificationEvent', (e) => handleRealtimeNotification(e));
//     } else if (window.user.cinema_id && !window.user.branch_id) {
//         Echo.channel(`notification.cinema.${window.user.cinema_id}`)
//             .listen('RealTimeNotificationEvent', (e) => handleRealtimeNotification(e));
//     }
// }

// // Xử lý khi có thông báo realtime đến
// function handleRealtimeNotification(e) {
//     const notification = e.notification;

//     // ⚠️ Chặn trùng bằng ID
//     if (handledNotifications.has(notification.id)) {
//         return;
//     }
//     handledNotifications.add(notification.id);

//     const $outer = $('#listNotifications');
//     const $container = $('#listNotifications .simplebar-content'); // vùng scroll thực
//     const $badge = $('#page-header-notifications-dropdown .badge');

//     // Xóa dòng "Không có thông báo nào"
//     $container.find('.no-notification').remove();

//     // Cập nhật badge
//     if ($badge.length) {
//         const count = parseInt($badge.text() || '0') + 1;
//         $badge.text(count).removeClass('d-none');
//     }

//     // HTML của thông báo mới
//     const $newNotification = $(`
//         <a href="${notification.link ?? 'javascript:void(0)'}" class="text-reset notification-item" data-id="${notification.id}">
//             <div class="d-flex">
//                 <div class="flex-shrink-0 avatar-sm me-3">
//                     <span class="avatar-title bg-info rounded-circle font-size-16">
//                         <i class="bx bx-bell"></i>
//                     </span>
//                 </div>
//                 <div class="flex-grow-1">
//                     <h6 class="mb-1 fw-bold">${notification.title}</h6>
//                     <div class="font-size-13 text-muted">
//                         <p class="mb-1 fw-semibold">${notification.content}</p>
//                         <p class="mb-0">
//                             <i class="mdi mdi-clock-outline"></i>
//                             <span class="badge bg-secondary text-light ms-2">Vừa xong</span>
//                         </p>
//                     </div>
//                 </div>
//             </div>
//         </a>
//     `);

//     // Nếu chưa có thì thêm
//     $container.prepend($newNotification.hide().slideDown('fast'));

//     // Cập nhật lại SimpleBar nếu cần
//     if ($outer[0] && $outer[0].SimpleBar) {
//         $outer[0].SimpleBar.recalculate();
//     }
// }















// import './bootstrap';
// console.log('[Realtime] Echo setup...');

// if (!window.hasSetupEchoListener) {
//     window.hasSetupEchoListener = true;

//     if (!window.user.branch_id && !window.user.cinema_id) {
//         Echo.channel('notification.all') // system admin
//             .listen('RealTimeNotificationEvent', (e) => handleRealtimeNotification(e));
//     } else if (window.user.branch_id && !window.user.cinema_id) {
//         Echo.channel(`notification.branch.${window.user.branch_id}`) // quản lý chi nhánh
//             .listen('RealTimeNotificationEvent', (e) => handleRealtimeNotification(e));
//     } else if (window.user.cinema_id && !window.user.branch_id) {
//         Echo.channel(`notification.cinema.${window.user.cinema_id}`) // quản lý rạp
//             .listen('RealTimeNotificationEvent', (e) => handleRealtimeNotification(e));
//     }
// }

// function handleRealtimeNotification(e) {
//     const notification = e.notification;
//     const $outer = $('#listNotifications');
//     const $container = $('#listNotifications .simplebar-content'); // vùng scroll thực
//     const $badge = $('#page-header-notifications-dropdown .badge');

//     $container.find('.no-notification').remove();

//     // if ($container.find(`[data-id="${notification.id}"]`).length > 0) {
//     //     return;
//     // }

//     // Cập nhật badge
//     if ($badge.length) {
//         const count = parseInt($badge.text() || '0') + 1;
//         $badge.text(count).removeClass('d-none');
//     }

//     // HTML của thông báo mới
//     const $newNotification = $(`
//         <a href="${notification.link ?? 'javascript:void(0)'}" class="text-reset notification-item" data-id="${notification.id}">
//             <div class="d-flex">
//                 <div class="flex-shrink-0 avatar-sm me-3">
//                     <span class="avatar-title bg-info rounded-circle font-size-16">
//                         <i class="bx bx-bell"></i>
//                     </span>
//                 </div>
//                 <div class="flex-grow-1">
//                     <h6 class="mb-1 fw-bold">${notification.title}</h6>
//                     <div class="font-size-13 text-muted">
//                         <p class="mb-1 fw-semibold">${notification.content}</p>
//                         <p class="mb-0">
//                             <i class="mdi mdi-clock-outline"></i>
//                             <span class="badge bg-secondary text-light ms-2">Vừa xong</span>
//                         </p>
//                     </div>
//                 </div>
//             </div>
//         </a>
//     `);

//     // Nếu chưa có thì thêm
//     if ($container.find(`[data-id="${notification.id}"]`).length === 0) {
//         $container.prepend($newNotification.hide().slideDown('fast'));

//         // Cập nhật lại SimpleBar
//         if ($outer[0] && $outer[0].SimpleBar) {
//             $outer[0].SimpleBar.recalculate();
//         }
//     }
// }


// function handleRealtimeNotification(e) {
//     const notification = e.notification;
//     const $container = $('#listNotifications');
//     const $badge = $('#page-header-notifications-dropdown .badge');

//     // Cập nhật badge
//     if ($badge.length) {
//         const count = parseInt($badge.text() || '0') + 1;
//         $badge.text(count).removeClass('d-none');
//     }

//     // HTML của thông báo mới
//     const $newNotification = $(`
//         <a href="${notification.link ?? 'javascript:void(0)'}" class="text-reset notification-item" data-id="${notification.id}">
//             <div class="d-flex">
//                 <div class="flex-shrink-0 avatar-sm me-3">
//                     <span class="avatar-title bg-info rounded-circle font-size-16">
//                         <i class="bx bx-bell"></i>
//                     </span>
//                 </div>
//                 <div class="flex-grow-1">
//                     <h6 class="mb-1 fw-bold">${notification.title}</h6>
//                     <div class="font-size-13 text-muted">
//                         <p class="mb-1 fw-semibold">${notification.content}</p>
//                         <p class="mb-0 d-flex align-items-center gap-2">
//                             <i class="mdi mdi-clock-outline"></i>
//                             <span>Vừa xong</span>
//                             <span class="badge bg-secondary text-light ms-2">Chưa xem</span>
//                         </p>
//                     </div>
//                 </div>
//             </div>
//         </a>
//     `);

//     // Nếu chưa tồn tại thì thêm vào đầu
//     if ($container.find(`[data-id="${notification.id}"]`).length === 0) {
//         $container.prepend($newNotification.hide().slideDown('fast'));

//         // Cập nhật lại SimpleBar
//         if ($container[0] && $container[0].SimpleBar) {
//             $container[0].SimpleBar.recalculate();
//         }
//     }
// }

// function handleRealtimeNotification(e) {
//     const notification = e.notification;
//     const container = document.querySelector('[data-simplebar]');
//     const badge = document.querySelector('#page-header-notifications-dropdown .badge');

//     // Cập nhật badge
//     if (badge) {
//         const count = parseInt(badge.textContent || '0') + 1;
//         badge.textContent = count;
//         badge.classList.remove('d-none');
//     }

//     // HTML chuẩn như Blade render
//     const html = `
//         <a href="${notification.link ?? 'javascript:void(0)'}" class="text-reset notification-item">
//             <div class="d-flex">
//                 <div class="flex-shrink-0 avatar-sm me-3">
//                     <span class="avatar-title bg-info rounded-circle font-size-16">
//                         <i class="bx bx-bell"></i>
//                     </span>
//                 </div>
//                 <div class="flex-grow-1">
//                     <h6 class="mb-1 fw-bold"> ${notification.title}</h6>
//                     <div class="font-size-13 text-muted">
//                         <p class="mb-1 fw-semibold">${notification.content}</p>
//                         <p class="mb-0 d-flex align-items-center gap-2">
//                             <i class="mdi mdi-clock-outline"></i>
//                             <span>Vừa xong</span>
//                             <span class="badge bg-secondary text-light ms-2">Chưa xem</span>
//                         </p>
//                     </div>
//                 </div>
//             </div>
//         </a>
//     `;

//     container.insertAdjacentHTML('afterbegin', html);
// }




// console.log(window.user,'window.user');

// if (!window.user.branch_id && !window.user.cinema_id) {
//     // system admin
//     Echo.channel('notification.all')
//         .listen('RealTimeNotificationEvent', (e) => {
//             console.log('📢 Admin nhận:', e.notification);
//         });
// } else if (window.user.branch_id && !window.user.cinema_id) {
//     // quản lý chi nhánh
//     Echo.channel(`notification.branch.${window.user.branch_id}`)
//         .listen('RealTimeNotificationEvent', (e) => {
//             console.log('📢 Quản lý chi nhánh nhận:', e.notification);
//         });
// } else if (window.user.cinema_id && !window.user.branch_id) {
//     // quản lý rạp
//     Echo.channel(`notification.cinema.${window.user.cinema_id}`)
//         .listen('RealTimeNotificationEvent', (e) => {
//             console.log('📢 Quản lý rạp nhận:', e.notification);
//         });
// }



// function handleRealtimeNotification(e) {
//     console.log("📥 Nhận thông báo mới:", e.notification);

//     const notification = e.notification;
//     const container = document.querySelector('[data-simplebar]');
//     const badge = document.querySelector('#page-header-notifications-dropdown .badge');

//     // Tạo HTML notification mới
//     const html = `
//         <a href="${notification.link ?? 'javascript:void(0)'}" class="text-reset notification-item">
//             <div class="d-flex">
//                 <div class="flex-shrink-0 avatar-sm me-3">
//                     <span class="avatar-title bg-info rounded-circle font-size-16">
//                         <i class="bx bx-bell"></i>
//                     </span>
//                 </div>
//                 <div class="flex-grow-1">
//                     <h6 class="mb-1 fw-bold">${notification.title}</h6>
//                     <div class="font-size-13 text-muted">
//                         <p class="mb-1 fw-semibold">${notification.content}</p>
//                         <p class="mb-0">
//                             <i class="mdi mdi-clock-outline"></i>
//                             <span>Vừa xong</span>
//                             <span class="badge bg-secondary text-light ms-2">Chưa xem</span>
//                         </p>
//                     </div>
//                 </div>
//             </div>
//         </a>
//     `;

//     // Chèn vào đầu danh sách
//     container.insertAdjacentHTML('afterbegin', html);

//     // Cập nhật số lượng badge
//     let count = parseInt(badge?.textContent || 0);
//     if (!isNaN(count)) {
//         badge.textContent = count + 1;
//         badge.classList.remove('d-none');
//     }
// }
