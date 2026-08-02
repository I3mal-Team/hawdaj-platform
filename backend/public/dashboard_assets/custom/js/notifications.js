if (AuthId != null) {
    window.Echo.channel(`events-${AuthId}`)
        .listen('.RealTimeMessage', (e) => {
            let data = e.data;

            let locale = LANG === 'ar' ? 'ar-EG' : 'en-GB';
            let created_at_date = new Date().toLocaleDateString(locale, {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            let created_at_time = new Date().toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            }).replace('AM', 'am').replace('PM', 'pm');

            if (LANG === 'ar') {
                created_at_time = created_at_time.replace('am', 'ص').replace('pm', 'م');
            }

            let notificationElm = $('.notification-number');
            if (Number.parseInt(notificationElm.text().trim()) >= 99) {
                notificationElm.text('99+');
                notificationElm.css({
                    'width': '22px',
                    'height': '22px',
                    'line-height': '22px',
                    'font-size': '.8rem'
                });
            } else {
                notificationElm.text(+notificationElm.text().trim() + 1);
            }

            if ($('.notification-img').length !== 0) {
                $('.notification-img').parent().remove();
                $('.notification-footer').css('display', 'block');
            }

            let fullUrl = 'javascript:;';
            if (data.url !== 'javascript:;' || data.url !== undefined) {
                fullUrl = HOST_URL + data.url;
            }

        //     let oldNotificationMarkup = `<div class="d-flex align-items-center mb-6 new-notification">
        //     <!--begin::Text-->
        //     <div class="d-flex flex-column font-weight-bold" style="width: 100% !important;">
        //         <a href="${fullUrl}" class="text-dark text-hover-primary mb-1 font-size-lg"> ${data.title}</a>
        //         <span class="text-muted"> ${data.message}</span>
        //             <span class="d-flex justify-content-between align-items-center">
        //                 <span class="text-dark-50  text-right mt-1">${created_at_date}</span>
        //                 <span class="text-dark-50  text-right mt-1">${created_at_time}</span>
        //             </span>
        //         </span>
        //     </div>
        // </div>`;

            let notificationMarkup = `<a href="{{$notifyUrl}}" class="dropdown-item new-notification">
                        <div class="content-cont">
                            <h4 class=" title">${data.title}</h4>
                            <p class="content">${data.message}</p>
                            <div class="time">
                                <span class="text-muted  mt-1">
                                    <i class="flaticon-event-calendar-symbol" ></i>
                                    ${created_at_date}
                                </span>
                                <span class="text-muted  mt-1">
                                    <i class="fa fa-clock" aria-hidden="true"></i>
                                     ${created_at_time}
                                </span>
                            </div>
                        </div>
                    </a>`

            if ($('.notification-dropdown .dropdown-items-container').children().length >= 15) {
                $('.notification-dropdown .dropdown-items-container').children().last().remove();
            }
            $('.notification-dropdown .dropdown-items-container').prepend(notificationMarkup);
        });

    $('#notification_button').click(function () {
        $.ajax({
            url: `${HOST_URL}/${LANG}/dashboard/notifications`,
            type: 'get',
            data: {
                '_token': $('meta[name="csrf-token"]').attr("content")
            },
            success: function (data) {
                $('.notification-number').text('0');
            }
        });
    });
}
