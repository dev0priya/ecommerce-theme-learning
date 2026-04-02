<?php 
    require '../include/load.php'; 

    $title = '404 - Page Not Found';
    include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* --- Premium 404 Layout --- */
    .error-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        background: transparent;
        transition: 0.3s;
    }

    .error-container {
        text-align: center;
        max-width: 600px;
        width: 100%;
    }

    /* Subtle Floating Animation */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }

    .error-image-box {
        margin-bottom: 40px;
        animation: float 6s ease-in-out infinite;
        display: flex;
        justify-content: center;
    }

    .error-image-box iconify-icon {
        font-size: 180px;
        color: #6366f1;
        filter: drop-shadow(0 20px 30px rgba(99, 102, 241, 0.2));
    }
    .dark .error-image-box iconify-icon {
        color: #818cf8;
        filter: drop-shadow(0 0 50px rgba(99, 102, 241, 0.1));
    }

    /* Text Styling */
    .error-code {
        font-size: 120px;
        font-weight: 900;
        line-height: 1;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 10px;
        letter-spacing: -5px;
    }

    .error-title {
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 16px;
        letter-spacing: -0.5px;
    }
    .dark .error-title { color: #f8fafc; }

    .error-desc {
        font-size: 16px;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 40px;
        line-height: 1.6;
    }
    .dark .error-desc { color: #94a3b8; }

    /* Premium Subtle Button */
    .btn-back-home {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background: #6366f1;
        color: white !important;
        padding: 16px 36px;
        border-radius: 18px;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1.5px;
        transition: 0.3s;
        box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
    }
    .btn-back-home:hover {
        transform: translateY(-3px);
        background: #4f46e5;
        box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.5);
    }

    /* Quick Links Footer */
    .error-footer {
        margin-top: 60px;
        padding-top: 30px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: center;
        gap: 24px;
    }
    .dark .error-footer { border-top-color: #1e293b; }

    .footer-link {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        color: #94a3b8;
        letter-spacing: 1px;
        transition: 0.3s;
    }
    .footer-link:hover { color: #6366f1; }
</style>

<div class="dashboard-main-body">
    <div class="error-wrapper">
        <div class="error-container">
            
            <div class="error-image-box">
                <iconify-icon icon="solar:ghost-bold-duotone"></iconify-icon>
            </div>

            <div class="error-code">404</div>
            <h2 class="error-title">Oops! Lost in Space?</h2>
            <p class="error-desc">
                The page you are looking for might have been removed, <br> 
                had its name changed, or is temporarily unavailable.
            </p>

            <a href="../index.php" class="btn-back-home">
                <iconify-icon icon="solar:home-2-bold" class="text-xl"></iconify-icon>
                Return to Dashboard
            </a>

            <div class="error-footer">
                <a href="../pages/faq.php" class="footer-link">Help Center</a>
                <a href="mailto:support@project-a.com" class="footer-link">Contact Support</a>
                <a href="#" onclick="history.back()" class="footer-link">Go Back</a>
            </div>

        </div>
    </div>
</div>

<?php include '../partials/layouts/layoutBottom.php'; ?>