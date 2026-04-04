<?php
require '../include/load.php';
checkLogin();

// 15 Premium Dummy Mails with Detailed Content
$emails = [
    [
        'id' => 1, 'name' => 'Support Team', 'subject' => 'Your subscription was successful', 'time' => '12:45 PM', 'starred' => true, 'tag' => 'Business', 
        'body' => "Welcome to the premium tier! Your subscription is now active until 2027. You have full access to our analytics dashboard and priority support. We've attached your invoice for this billing cycle."
    ],
    [
        'id' => 2, 'name' => 'Jane Cooper', 'subject' => 'Project Alpha: New Design Assets', 'time' => '10:20 AM', 'starred' => false, 'tag' => 'Personal', 
        'body' => "Hey! I've uploaded the latest Figma mockups for the dashboard. Please review the new sidebar layout and color contrast. I'd love to get your feedback before Friday's deadline."
    ],
    [
        'id' => 3, 'name' => 'Stripe Payments', 'subject' => 'Payout of ₹45,000 is on the way', 'time' => '09:15 AM', 'starred' => true, 'tag' => 'Business', 
        'body' => "Your weekly payout has been initiated. The funds should arrive in your bank account in 2-3 business days. You can view the breakdown in your settlement report."
    ],
    [
        'id' => 4, 'name' => 'Guy Hawkins', 'subject' => 'Dinner tonight?', 'time' => 'Yesterday', 'starred' => false, 'tag' => 'Social', 
        'body' => "Are we still on for 8 PM at the rooftop cafe? Let me know if you can make it, so I can confirm the reservation for the team."
    ],
    [
        'id' => 5, 'name' => 'Figma Notifications', 'subject' => 'New comment on Admin Dashboard', 'time' => 'Yesterday', 'starred' => false, 'tag' => 'Business', 
        'body' => "Alex Morgan left a comment: 'Can we make the sidebar slightly more compact for smaller screens?' Review it and let me know."
    ],
    [
        'id' => 6, 'name' => 'Dianne Russell', 'subject' => 'Weekly Sales Report - March', 'time' => '04 April', 'starred' => false, 'tag' => 'Promotions', 
        'body' => "The sales report for March is ready. We saw a 15% increase in conversion compared to February. Attached is the full PDF analysis."
    ],
    [
        'id' => 7, 'name' => 'Google Cloud', 'subject' => 'Usage Alert: 80% Threshold', 'time' => '03 April', 'starred' => true, 'tag' => 'Business', 
        'body' => "Your API usage has reached 80% of the monthly quota. Consider upgrading your plan to avoid any service interruptions."
    ],
    [
        'id' => 8, 'name' => 'Theresa Webb', 'subject' => 'Happy Birthday!', 'time' => '02 April', 'starred' => false, 'tag' => 'Social', 
        'body' => "Wishing you a fantastic day filled with joy and success! Hope you have a great celebration tonight."
    ],
    [
        'id' => 9, 'name' => 'Netflix', 'subject' => 'New Season of "Stranger Things"', 'time' => '01 April', 'starred' => false, 'tag' => 'Promotions', 
        'body' => "The wait is over! Stream the new season now and see what happens next in Hawkins."
    ],
    [
        'id' => 10, 'name' => 'Cameron Williamson', 'subject' => 'Bug Report: Checkout Page', 'time' => '30 March', 'starred' => true, 'tag' => 'Business', 
        'body' => "Users are reporting a 404 error when clicking the 'Pay Now' button on mobile devices. Needs urgent investigation."
    ],
    [
        'id' => 11, 'name' => 'Robert Fox', 'subject' => 'Invoice #442 Paid', 'time' => '28 March', 'starred' => false, 'tag' => 'Business', 
        'body' => "We have received your payment for Invoice #442. Your account balance is now updated. Thank you for your business."
    ],
    [
        'id' => 12, 'name' => 'Jenny Wilson', 'subject' => 'Client Feedback Meeting', 'time' => '25 March', 'starred' => false, 'tag' => 'Personal', 
        'body' => "The client wants to discuss the new feature request tomorrow at 11 AM. Are you available for a quick Zoom call?"
    ],
    [
        'id' => 13, 'name' => 'Esther Howard', 'subject' => 'Login Credentials Update', 'time' => '22 March', 'starred' => false, 'tag' => 'Business', 
        'body' => "Security update: Your login credentials for the staging server have been updated. Please check the secure vault for new passwords."
    ],
    [
        'id' => 14, 'name' => 'Kristin Watson', 'subject' => 'API Documentation Updated', 'time' => '20 March', 'starred' => true, 'tag' => 'Business', 
        'body' => "We've added new endpoints to our documentation. These will help you integrate the wishlist feature more easily into the app."
    ],
    [
        'id' => 15, 'name' => 'Jerome Bell', 'subject' => 'New server is up!', 'time' => '18 March', 'starred' => false, 'tag' => 'Business', 
        'body' => "The migration is complete. The new server is faster and more reliable. Let me know if you notice any lag during high traffic."
    ]
];

$title = "Email";
$subTitle = "Inbox";

include '../partials/layouts/layoutTop.php'; 
?>

<style>
/* FULL HEIGHT & PREMIUM DUAL THEME */
.dashboard-main-body { padding: 20px !important; height: calc(100vh - 100px); display: flex; flex-direction: column; }
.email-card-container { display: flex; flex: 1; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
.dark .email-card-container { background: #111827; border-color: rgba(255, 255, 255, 0.08); box-shadow: 0 20px 40px rgba(0,0,0,0.4); }

.email-nav { width: 260px; border-right: 1px solid #e2e8f0; padding: 24px; background: #f8fafc; }
.dark .email-nav { background: rgba(17, 24, 39, 0.5); border-color: rgba(255, 255, 255, 0.08); }

.compose-btn { background: #6366f1; color: white; padding: 14px; border-radius: 14px; font-weight: 700; text-align: center; cursor: pointer; margin-bottom: 30px; }
.nav-item { display: flex; justify-content: space-between; padding: 12px 16px; border-radius: 12px; color: #64748b; cursor: pointer; margin-bottom: 6px; font-size: 14px; }
.dark .nav-item { color: #9ca3af; }
.nav-item.active, .nav-item:hover { background: #6366f1; color: white; }

.email-content-area { flex: 1; display: flex; flex-direction: column; overflow: hidden; background: #fff; }
.dark .email-content-area { background: transparent; }
.email-list-header { padding: 20px 25px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
.dark .email-list-header { border-color: rgba(255, 255, 255, 0.08); color: #fff; }

.mail-list-wrapper { flex: 1; overflow-y: auto; }
.mail-row { display: grid; grid-template-columns: 50px 180px 1fr 100px; padding: 16px 25px; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: 0.2s; }
.dark .mail-row { border-color: rgba(255, 255, 255, 0.03); }
.mail-row:hover { background: #f8fafc; }
.dark .mail-row:hover { background: rgba(255, 255, 255, 0.02); }

.sender-name { font-weight: 600; color: #1e293b; }
.dark .sender-name { color: #f9fafb; }
.mail-subject { color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding-right: 20px; }
.dark .mail-subject { color: #9ca3af; }

#emailDetailView { display: none; padding: 40px; overflow-y: auto; background: #fff; height: 100%; }
.dark #emailDetailView { background: #0f172a; color: #fff; }
.mail-body-text { font-size: 16px; line-height: 1.8; color: #475569; white-space: pre-line; }
.dark .mail-body-text { color: #cbd5e1; }

.tag-badge { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
.tag-Business { background: #dbeafe; color: #1e40af; }
.tag-Personal { background: #8959bd; color: #6b21a8; }
.tag-Social { background: #73b289; color: #166534; }
.dark .tag-Business { background: #1e3a8a; color: #60a5fa; }
</style>

<div class="dashboard-main-body">
    <div class="email-card-container">
        <div class="email-nav">
            <div class="compose-btn">+ Compose</div>
            <div class="nav-item active"><span><iconify-icon icon="solar:inbox-bold" class="mr-2"></iconify-icon> Inbox</span> <span>15</span></div>
            <div class="nav-item"><span><iconify-icon icon="solar:star-bold" class="mr-2"></iconify-icon> Starred</span></div>
            <div class="nav-item text-rose-500"><span><iconify-icon icon="solar:trash-bin-trash-bold" class="mr-2"></iconify-icon> Trash</span></div>
        </div>

        <div class="email-content-area">
            <div id="emailListView" class="h-full flex flex-col">
                <div class="email-list-header">
                    <h5 class="font-bold text-xl">Inbox</h5>
                    <iconify-icon icon="solar:magnifer-linear" class="text-xl text-slate-400"></iconify-icon>
                </div>
                <div class="mail-list-wrapper">
                    <?php foreach($emails as $mail): ?>
                    <div class="mail-row" onclick="openMail('<?= $mail['name'] ?>', '<?= $mail['subject'] ?>', '<?= addslashes($mail['body']) ?>', '<?= $mail['tag'] ?>')">
                        <iconify-icon icon="solar:star-bold" class="text-lg <?= $mail['starred'] ? 'text-yellow-400' : 'text-slate-300 dark:text-slate-700' ?>"></iconify-icon>
                        <span class="sender-name"><?= $mail['name'] ?></span>
                        <span class="mail-subject"><span class="tag-badge tag-<?= $mail['tag'] ?> mr-3"><?= $mail['tag'] ?></span><?= $mail['subject'] ?></span>
                        <span class="text-xs text-slate-400 text-right"><?= $mail['time'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="emailDetailView">
                <button onclick="closeMail()" class="text-indigo-600 dark:text-indigo-400 font-bold mb-8 flex items-center gap-2">
                    <iconify-icon icon="solar:alt-arrow-left-outline"></iconify-icon> Back
                </button>
                <h1 id="v-subject" class="text-3xl font-black mb-6 text-slate-900 dark:text-white"></h1>
                <div class="flex items-center gap-4 border-b border-slate-100 dark:border-white/5 pb-8 mb-8">
                    <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-xl font-black" id="v-avatar"></div>
                    <div>
                        <h4 id="v-name" class="font-bold text-lg text-slate-900 dark:text-white"></h4>
                        <p class="text-sm text-slate-500">to: admin@modernstore.com</p>
                    </div>
                </div>
                <div id="v-body" class="mail-body-text"></div>
            </div>
        </div>
    </div>
</div>

<script>
function openMail(n, s, b, t) {
    document.getElementById('emailListView').style.display = 'none';
    document.getElementById('emailDetailView').style.display = 'block';
    document.getElementById('v-name').innerText = n;
    document.getElementById('v-subject').innerText = s;
    document.getElementById('v-body').innerText = b;
    document.getElementById('v-avatar').innerText = n.charAt(0);
}
function closeMail() {
    document.getElementById('emailListView').style.display = 'flex';
    document.getElementById('emailDetailView').style.display = 'none';
}
</script>

<?php include '../partials/layouts/layoutBottom.php'; ?>