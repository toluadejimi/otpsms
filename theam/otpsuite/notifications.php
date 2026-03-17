<?php
$page_name = "Notifications";
include 'include/header-main.php';
?>
<div id="app">
    <div class="container-fluid p-0">
        <div class="appHeader">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <i class="ri-arrow-left-line icon md"></i>
                </a>
                <div class="pageTitle">Notifications</div>
            </div>
        </div>
    </div>

    <div id="appCapsule">
         <input type="hidden" id="token" value="<?= $_SESSION['token']?>">
        <ul class="listview image-listview flush" id="notificationList">
            <li class="text-center py-3 text-muted">
                Loading notifications...
            </li>
        </ul>
    </div>

    <?php include 'include/bottom-menu.php'; ?>
</div>

<!-- VIEW NOTIFICATION MODAL -->
<div class="modal fade modalbox" id="notificationModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notifTitle"></h5>
                <a href="#" data-bs-dismiss="modal">Close</a>
            </div>
            <div class="modal-body">
                <p id="notifBody"></p>
                <small class="text-muted" id="notifTime"></small>
            </div>
        </div>
    </div>
</div>

<script>
const token = $('#token').val();
/* Load notifications */
let notificationsMap = {};

function loadNotifications(){
    $.getJSON("api/notifications/getNotifications.php",{token},res=>{
        if(!res || res.length === 0){
            $("#notificationList").html(`
                <li class="text-center py-4">
                    <strong>No Notifications</strong><br>
                    <small class="text-muted">You're all caught up 🎉</small>
                </li>
            `);
            return;
        }

        let html = "";
        notificationsMap = {};

        res.forEach(n=>{
            notificationsMap[n.user_notification_id] = n;

            html += `
            <li ${n.is_read == 0 ? 'class="active"' : ''}>
                <a href="#"
                   class="item openNotification"
                   data-id="${n.user_notification_id}">
                    <div class="in">
                        <div>
                            <div class="mb-05"><strong>${escapeHtml(n.title)}</strong></div>
                            <div class="text-small mb-05">${escapeHtml(n.preview)}</div>
                            <div class="text-xsmall">${n.created_at}</div>
                        </div>
                        ${n.is_read == 0 ? `<span class="badge badge-primary badge-empty"></span>` : ``}
                    </div>
                </a>
            </li>`;
        });

        $("#notificationList").html(html);
    });
}


/* Open modal */
$(document).on("click",".openNotification",function(){
    const id = $(this).data("id");
    const n = notificationsMap[id];

    $("#notifTitle").text(n.title);
    $("#notifBody").html(n.body); // FULL HTML, SAFE
    $("#notifTime").text(n.created_at);

    $("#notificationModal").modal("show");

    const li = $(this).closest("li");

    $.post("api/notifications/markRead.php",{id,token});
    li.removeClass("active");
    li.find(".badge").remove();
});


/* Escape helper */
function escapeHtml(text){
    return $('<div>').text(text).html();
}

loadNotifications();
</script>


<?php include 'include/footer-main.php'; ?>
