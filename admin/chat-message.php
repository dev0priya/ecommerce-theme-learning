<?php
require '../include/load.php';
checkLogin();

$title = "Messages";
$subTitle = "Chat App";

// 10 Premium Dummy Contacts with Unique Conversation History
$contacts = [
    [
        'id' => 1, 'name' => 'Jane Cooper', 'time' => '12:45 PM', 'online' => true, 'img' => 'https://i.pravatar.cc/150?u=1',
        'history' => [
            ['type' => 'received', 'text' => 'Hii, can you send the sales report for March?'],
            ['type' => 'sent', 'text' => 'Sure Jane, I am exporting it right now.'],
            ['type' => 'received', 'text' => 'Great! Also, please include the tax details.']
        ]
    ],
    [
        'id' => 2, 'name' => 'Guy Hawkins', 'time' => '10:20 AM', 'online' => false, 'img' => 'https://i.pravatar.cc/150?u=2',
        'history' => [
            ['type' => 'received', 'text' => 'The new dashboard design is ready!'],
            ['type' => 'sent', 'text' => 'Awesome, can you share the Figma link?'],
            ['type' => 'received', 'text' => 'Sending it in a minute.']
        ]
    ],
    [
        'id' => 3, 'name' => 'Dianne Russell', 'time' => '09:15 AM', 'online' => true, 'img' => 'https://i.pravatar.cc/150?u=3',
        'history' => [
            ['type' => 'received', 'text' => 'Are we still having the meeting at 5?'],
            ['type' => 'sent', 'text' => 'Yes, the room is already booked.']
        ]
    ],
    [
        'id' => 4, 'name' => 'Cody Fisher', 'time' => 'Yesterday', 'online' => false, 'img' => 'https://i.pravatar.cc/150?u=4',
        'history' => [
            ['type' => 'received', 'text' => 'I found a bug in the checkout page.'],
            ['type' => 'sent', 'text' => 'Is it on mobile or desktop?'],
            ['type' => 'received', 'text' => 'Mainly on iOS Safari.']
        ]
    ],
    [
        'id' => 5, 'name' => 'Robert Fox', 'time' => 'Yesterday', 'online' => true, 'img' => 'https://i.pravatar.cc/150?u=5',
        'history' => [
            ['type' => 'received', 'text' => 'Payment for Invoice #442 is successful.'],
            ['type' => 'sent', 'text' => 'Received. I will update the status now.']
        ]
    ],
    [
        'id' => 6, 'name' => 'Esther Howard', 'time' => '04 April', 'online' => false, 'img' => 'https://i.pravatar.cc/150?u=6',
        'history' => [
            ['type' => 'received', 'text' => 'Hello! Do you have the login credentials?'],
            ['type' => 'sent', 'text' => 'Sent them to your official email.']
        ]
    ],
    [
        'id' => 7, 'name' => 'Jenny Wilson', 'time' => '03 April', 'online' => true, 'img' => 'https://i.pravatar.cc/150?u=7',
        'history' => [
            ['type' => 'received', 'text' => 'The client is asking for a discount.'],
            ['type' => 'sent', 'text' => 'Maximum we can give is 10%.'],
            ['type' => 'received', 'text' => 'Okay, I will convey that.']
        ]
    ],
    [
        'id' => 8, 'name' => 'Kristin Watson', 'time' => '02 April', 'online' => false, 'img' => 'https://i.pravatar.cc/150?u=8',
        'history' => [
            ['type' => 'received', 'text' => 'Thanks for helping with the API integration.'],
            ['type' => 'sent', 'text' => 'No problem, anytime!']
        ]
    ],
    [
        'id' => 9, 'name' => 'Cameron Williamson', 'time' => '01 April', 'online' => true, 'img' => 'https://i.pravatar.cc/150?u=9',
        'history' => [
            ['type' => 'received', 'text' => 'Can we reschedule the call?'],
            ['type' => 'sent', 'text' => 'Sure, pick a time for tomorrow.']
        ]
    ],
    [
        'id' => 10, 'name' => 'Jerome Bell', 'time' => '30 March', 'online' => false, 'img' => 'https://i.pravatar.cc/150?u=10',
        'history' => [
            ['type' => 'received', 'text' => 'New server is up and running.'],
            ['type' => 'sent', 'text' => 'Perfect. Migrating the DB tonight.']
        ]
    ],
];

include '../partials/layouts/layoutTop.php'; 
?>

<style>
.dashboard-main-body { padding: 20px !important; height: calc(100vh - 100px); display: flex; flex-direction: column; }
.chat-container { display: flex; flex: 1; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
.dark .chat-container { background: #111827; border-color: rgba(255, 255, 255, 0.08); }

/* Sidebar */
.chat-sidebar { width: 340px; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; background: #f8fafc; }
.dark .chat-sidebar { background: rgba(17, 24, 39, 0.5); border-color: rgba(255, 255, 255, 0.08); }
.contact-list { flex: 1; overflow-y: auto; }
.contact-item { display: flex; align-items: center; padding: 18px 20px; cursor: pointer; transition: 0.2s; border-left: 4px solid transparent; border-bottom: 1px solid rgba(0,0,0,0.02); }
.dark .contact-item { border-bottom: 1px solid rgba(255,255,255,0.02); }
.contact-item:hover { background: rgba(99, 102, 241, 0.05); }
.contact-item.active { background: rgba(99, 102, 241, 0.1); border-left-color: #6366f1; }
.user-avatar { width: 48px; height: 48px; border-radius: 14px; object-fit: cover; margin-right: 12px; }

/* Main Chat Area */
.chat-main { flex: 1; display: flex; flex-direction: column; background: #ffffff; }
.dark .chat-main { background: #0f172a; }
.chat-header { padding: 18px 25px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; }
.dark .chat-header { border-color: rgba(255, 255, 255, 0.08); }
.chat-messages { flex: 1; padding: 25px; overflow-y: auto; display: flex; flex-direction: column; gap: 15px; background: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png'); background-blend-mode: overlay; }
.dark .chat-messages { background-color: #0f172a; opacity: 0.9; }

/* Bubbles */
.msg-bubble { max-width: 75%; padding: 12px 18px; border-radius: 18px; font-size: 14px; line-height: 1.5; position: relative; }
.msg-sent { align-self: flex-end; background: #6366f1; color: white; border-bottom-right-radius: 4px; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2); }
.msg-received { align-self: flex-start; background: #f1f5f9; color: #1e293b; border-bottom-left-radius: 4px; border: 1px solid #e2e8f0; }
.dark .msg-received { background: #1e293b; color: #f1f5f9; border-color: rgba(255,255,255,0.05); }

.chat-footer { padding: 20px; border-top: 1px solid #e2e8f0; display: flex; gap: 12px; align-items: center; }
.dark .chat-footer { border-color: rgba(255, 255, 255, 0.08); }
.chat-input { flex: 1; background: #f1f5f9; border: none; padding: 14px 20px; border-radius: 12px; outline: none; }
.dark .chat-input { background: #1e293b; color: white; }
</style>

<div class="dashboard-main-body">
    <div class="chat-container">
        <div class="chat-sidebar">
            <div class="p-5 border-b dark:border-white/10 flex justify-between items-center">
                <h5 class="font-bold dark:text-white">Messages</h5>
                <iconify-icon icon="solar:settings-bold" class="text-slate-400 cursor-pointer"></iconify-icon>
            </div>
            <div class="contact-list" id="contactList">
                <?php foreach($contacts as $index => $c): ?>
                <div class="contact-item <?= $index == 0 ? 'active' : '' ?>" 
                     onclick='openChat(this, <?= json_encode($c) ?>)'>
                    <div class="relative">
                        <img src="<?= $c['img'] ?>" class="user-avatar">
                        <?php if($c['online']): ?>
                            <span class="absolute bottom-0 right-3 w-3.5 h-3.5 bg-green-500 border-2 border-white dark:border-slate-900 rounded-full"></span>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 truncate">
                        <div class="flex justify-between">
                            <h6 class="text-sm font-bold dark:text-white"><?= $c['name'] ?></h6>
                            <span class="text-[10px] text-slate-400"><?= $c['time'] ?></span>
                        </div>
                        <p class="text-xs text-slate-500 truncate"><?= end($c['history'])['text'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="chat-main">
            <div class="chat-header">
                <div class="flex items-center gap-3">
                    <img id="headerImg" src="<?= $contacts[0]['img'] ?>" class="user-avatar" style="width: 42px; height: 42px;">
                    <div>
                        <h6 id="headerName" class="text-sm font-bold dark:text-white"><?= $contacts[0]['name'] ?></h6>
                        <span id="headerStatus" class="text-[10px] <?= $contacts[0]['online'] ? 'text-green-500' : 'text-slate-500' ?> font-bold">Online</span>
                    </div>
                </div>
                <div class="flex gap-4 text-slate-400 text-xl">
                    <iconify-icon icon="solar:phone-bold" class="cursor-pointer hover:text-indigo-500"></iconify-icon>
                    <iconify-icon icon="solar:videocamera-record-bold" class="cursor-pointer hover:text-indigo-500"></iconify-icon>
                    <iconify-icon icon="solar:info-circle-bold" class="cursor-pointer"></iconify-icon>
                </div>
            </div>

            <div class="chat-messages" id="chatWindow">
                </div>

            <div class="chat-footer">
                <iconify-icon icon="solar:add-circle-bold" class="text-2xl text-slate-400 cursor-pointer"></iconify-icon>
                <input type="text" placeholder="Write a message..." class="chat-input" id="msgInput" onkeypress="if(event.key === 'Enter') sendMsg()">
                <button class="bg-indigo-600 text-white w-12 h-12 rounded-xl flex items-center justify-center hover:bg-indigo-700 transition-all" onclick="sendMsg()">
                    <iconify-icon icon="solar:plain-bold" class="text-xl"></iconify-icon>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Load initial chat
document.addEventListener('DOMContentLoaded', () => {
    const firstContact = <?= json_encode($contacts[0]) ?>;
    loadMessages(firstContact.history, firstContact.name);
});

function openChat(element, contact) {
    document.querySelectorAll('.contact-item').forEach(item => item.classList.remove('active'));
    element.classList.add('active');

    document.getElementById('headerName').innerText = contact.name;
    document.getElementById('headerImg').src = contact.img;
    const status = contact.online ? 'Online' : 'Offline';
    document.getElementById('headerStatus').innerText = status;
    document.getElementById('headerStatus').className = "text-[10px] font-bold " + (contact.online ? 'text-green-500' : 'text-slate-500');

    loadMessages(contact.history, contact.name);
}

function loadMessages(history, name) {
    const window = document.getElementById('chatWindow');
    let html = `<div class="text-center text-[10px] text-slate-400 my-4 uppercase tracking-widest">Conversation with ${name}</div>`;
    
    history.forEach(msg => {
        html += `<div class="msg-bubble msg-${msg.type}">${msg.text}</div>`;
    });
    
    window.innerHTML = html;
    window.scrollTop = window.scrollHeight;
}

function sendMsg() {
    const input = document.getElementById('msgInput');
    const window = document.getElementById('chatWindow');
    if(input.value.trim() !== "") {
        const msg = `<div class="msg-bubble msg-sent">${input.value}</div>`;
        window.innerHTML += msg;
        input.value = "";
        window.scrollTop = window.scrollHeight;
    }
}
</script>

<?php include '../partials/layouts/layoutBottom.php'; ?>