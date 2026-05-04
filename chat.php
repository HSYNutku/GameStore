<?php
require_once 'config.php';
checkLogin();

$db = Database::getInstance()->getConnection();

$friend_id = (int)($_GET['id'] ?? 0);

include 'includes/header.php';
?>

<div class="container mt-4">

<h3>💬 Sohbet</h3>

<div id="chat-box" style="height:400px;overflow-y:auto;background:#111;padding:10px;color:#fff;">
</div>

<form id="chat-form">
    <input type="hidden" name="receiver_id" value="<?= $friend_id ?>">
    <input type="text" name="message" class="form-control mt-2" placeholder="Mesaj yaz..." required>
</form>

</div>

<script>

// mesaj gönder
document.getElementById('chat-form').addEventListener('submit', function(e){
    e.preventDefault();

    let formData = new FormData(this);

    fetch('send_message.php', {
        method: 'POST',
        body: formData
    });

    this.reset();
});

// mesajları çek
function loadMessages(){
    fetch('get_messages.php?user_id=<?= $friend_id ?>')
    .then(res => res.text())
    .then(data => {
        document.getElementById('chat-box').innerHTML = data;
    });
}

setInterval(loadMessages, 2000);
loadMessages();

</script>

<?php include 'includes/footer.php'; ?>