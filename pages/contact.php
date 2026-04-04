<?php
session_start();
require '../include/load.php';
include '../partials/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<style>
:root {
    --bg-main: #020617;
    --bg-card: rgba(30, 41, 59, 0.4);
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --neon-blue: #00f2ff;
    --neon-green: #00ff88;
    --border: rgba(255, 255, 255, 0.08);
    --glass-bg: blur(20px);
}

/* Light Theme Variables */
.light body, body.light {
    --bg-main: #f8fafc;
    --bg-card: rgba(255, 255, 255, 0.8);
    --text-main: #0f172a;
    --text-muted: #64748b;
    --border: rgba(0, 0, 0, 0.05);
}

body { 
    margin: 0; 
    font-family: 'Plus Jakarta Sans', sans-serif; 
    background: var(--bg-main); 
    color: var(--text-main);
    transition: background 0.3s ease;
}

.contact-wrapper {
    max-width: 1200px;
    margin: 80px auto;
    padding: 0 20px;
}

.header-section {
    text-align: center;
    margin-bottom: 60px;
}

.section-label { 
    font-size: 11px; 
    font-weight: 900; 
    text-transform: uppercase; 
    color: var(--text-muted); 
    letter-spacing: 5px; 
    display: block; 
    margin-bottom: 10px; 
}

.page-title { 
    font-size: 42px; 
    font-weight: 900; 
    margin: 0;
    background: linear-gradient(to right, var(--text-main), var(--neon-blue));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* GRID LAYOUT */
.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 40px;
    align-items: stretch;
}

/* CARDS */
.glass-card {
    background: var(--bg-card);
    backdrop-filter: var(--glass-bg);
    border: 1px solid var(--border);
    border-radius: 35px;
    padding: 40px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.1);
}

/* LEFT SIDE: INFO */
.info-item {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
    padding: 20px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid transparent;
    transition: 0.3s;
}
.info-item:hover { border-color: var(--neon-blue); transform: translateX(10px); }

.icon-box {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    background: rgba(0, 242, 255, 0.1);
    color: var(--neon-blue);
}

.info-text h4 { margin: 0; font-size: 14px; text-transform: uppercase; color: var(--text-muted); letter-spacing: 1px; }
.info-text p { margin: 5px 0 0; font-size: 16px; font-weight: 700; }

/* RIGHT SIDE: FORM */
.form-group { margin-bottom: 25px; }
.form-label { font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 10px; display: block; }

.input-premium {
    width: 100%;
    background: rgba(0, 0, 0, 0.2);
    border: 1px solid var(--border);
    padding: 16px 20px;
    border-radius: 16px;
    color: var(--text-main);
    font-weight: 600;
    transition: 0.3s;
}
.light .input-premium { background: #fff; }
.input-premium:focus { border-color: var(--neon-blue); outline: none; box-shadow: 0 0 20px rgba(0, 242, 255, 0.1); }

/* NEON BUTTONS */
.btn-neon {
    width: 100%;
    padding: 18px;
    border: none;
    border-radius: 18px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 2px;
    cursor: pointer;
    transition: 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 15px;
}

.btn-mail { background: var(--neon-blue); color: #000; box-shadow: 0 10px 30px rgba(0, 242, 255, 0.3); }
.btn-whatsapp { background: var(--neon-green); color: #000; box-shadow: 0 10px 30px rgba(0, 255, 136, 0.2); }

.btn-neon:hover { transform: translateY(-3px); opacity: 0.9; }

/* RESPONSIVE */
@media (max-width: 991px) {
    .contact-grid { grid-template-columns: 1fr; }
    .page-title { font-size: 32px; }
}
</style>

<div class="contact-wrapper">
    <div class="header-section">
        <span class="section-label">Connect with us</span>
        <h1 class="page-title italic">Get In Touch</h1>
    </div>

    <div class="contact-grid">
        <div class="glass-card">
            <h3 style="margin-bottom: 30px; font-weight: 800;">Contact Details</h3>
            
            <div class="info-item">
                <div class="icon-box"><iconify-icon icon="solar:letter-bold-duotone"></iconify-icon></div>
                <div class="info-text">
                    <h4>Official Mail</h4>
                    <p>support@yourdomain.com</p>
                </div>
            </div>

            <div class="info-item" style="border-color: rgba(0, 255, 136, 0.1);">
                <div class="icon-box" style="color: var(--neon-green); background: rgba(0, 255, 136, 0.1);"><iconify-icon icon="solar:whatsapp-bold-duotone"></iconify-icon></div>
                <div class="info-text">
                    <h4>Instant Chat</h4>
                    <p>+91 98765 43210</p>
                </div>
            </div>

            <div class="info-item">
                <div class="icon-box" style="color: #ff007a; background: rgba(255, 0, 122, 0.1);"><iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon></div>
                <div class="info-text">
                    <h4>Our Office</h4>
                    <p>Cyber Hub, DLF Phase 3, Gurgaon</p>
                </div>
            </div>

            <div style="margin-top: 40px;">
                <p style="font-size: 13px; color: var(--text-muted); font-weight: 600;">Prefer instant messaging?</p>
                <a href="https://wa.me/919876543210" style="text-decoration: none;">
                    <button class="btn-neon btn-whatsapp">
                        <iconify-icon icon="logos:whatsapp-icon"></iconify-icon>
                        WhatsApp Chat
                    </button>
                </a>
            </div>
        </div>

        <div class="glass-card">
            <h3 style="margin-bottom: 30px; font-weight: 800;">Send us a Message</h3>
            
            <form action="send_mail_logic.php" method="POST">
                <div class="form-group">
                    <label class="form-label">Your Name</label>
                    <input type="text" name="name" class="input-premium" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="input-premium" placeholder="name@example.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label">How can we help?</label>
                    <textarea name="message" class="input-premium" rows="5" placeholder="Tell us about your query..." required></textarea>
                </div>

                <button type="submit" class="btn-neon btn-mail">
                    <iconify-icon icon="solar:plain-bold-duotone"></iconify-icon>
                    Send Message
                </button>
            </form>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>