<?php 
    require '../include/load.php'; 
    // checkLogin(); // Project A security toggle

    $title = 'Help & Support Center';
    include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* --- Premium Support Theme --- */
    .support-body { 
        background: #f8fafc; 
        min-height: 100vh; 
        transition: 0.3s; 
        padding-bottom: 80px;
    }
    .dark .support-body { background: #020617; }

    /* Hero Section */
    .support-hero {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border-radius: 40px;
        padding: 80px 40px;
        text-align: center;
        margin-bottom: -60px;
        position: relative;
        z-index: 1;
        overflow: hidden;
    }

    /* Content Wrapper */
    .support-container {
        max-width: 1000px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
        padding: 0 20px;
    }

    /* Quick Cards */
    .quick-card {
        background: white;
        border-radius: 24px;
        padding: 30px;
        border: 1px solid #e2e8f0;
        text-align: center;
        transition: 0.3s;
    }
    .dark .quick-card { background: #0f172a; border-color: #1e293b; }
    .quick-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.05); }

    /* FAQ Category Titles */
    .faq-category-title {
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        color: #6366f1;
        margin-bottom: 20px;
        margin-top: 50px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* FAQ Accordion */
    .faq-item {
        background: white;
        border-radius: 20px;
        margin-bottom: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: 0.3s;
    }
    .dark .faq-item { background: #0f172a; border-color: #1e293b; }
    
    .faq-question {
        padding: 24px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        font-weight: 800;
        color: #1e293b;
    }
    .dark .faq-question { color: #f1f5f9; }

    .faq-answer {
        padding: 0 30px 24px 30px;
        color: #64748b;
        font-size: 14px;
        line-height: 1.7;
        display: none;
        border-top: 1px solid #f8fafc;
    }
    .dark .faq-answer { color: #94a3b8; border-top-color: #1e293b; }

    .active .faq-answer { display: block; }
    .active .faq-icon { transform: rotate(180deg); color: #6366f1; }

    /* Bottom Support Box Fix */
    .support-footer-card {
        background: white;
        border: 1px solid #f1f5f9;
        transition: 0.3s ease;
    }
    .dark .support-footer-card {
        background: #0f172a;
        border-color: #1e293b;
    }
</style>

<div class="support-body">
    <div class="px-6">
        <div class="support-hero shadow-xl">
            <h1 class="text-5xl font-black text-white tracking-tighter mb-4">How can we help?</h1>
            <p class="text-indigo-100 font-bold opacity-80 uppercase tracking-[4px] text-xs">Project A Support Center</p>
        </div>
    </div>

    <div class="support-container">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            <div class="quick-card">
                <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-indigo-600 text-3xl">
                    <iconify-icon icon="solar:box-bold-duotone"></iconify-icon>
                </div>
                <h4 class="font-black text-slate-800 dark:text-white">Orders</h4>
                <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-widest">Real-time Tracking</p>
            </div>
            <div class="quick-card">
                <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-emerald-600 text-3xl">
                    <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                </div>
                <h4 class="font-black text-slate-800 dark:text-white">Refunds</h4>
                <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-widest">Instant Returns</p>
            </div>
            <div class="quick-card">
                <div class="w-14 h-14 bg-rose-50 dark:bg-rose-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-rose-600 text-3xl">
                    <iconify-icon icon="solar:shield-keyhole-bold-duotone"></iconify-icon>
                </div>
                <h4 class="font-black text-slate-800 dark:text-white">Security</h4>
                <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-widest">Verified Payments</p>
            </div>
        </div>

        <div class="mb-10">
            <h2 class="text-3xl font-black text-slate-900 dark:text-white mb-2 flex items-center gap-3 italic">
                Frequently Asked Questions
            </h2>
            <p class="text-slate-400 font-bold text-sm mb-8 tracking-wide">Detailed support guides for Project A</p>

            <div class="faq-list">
                
                <div class="faq-category-title">
                    <iconify-icon icon="solar:delivery-bold"></iconify-icon> Shipping & Delivery
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>How long does delivery take?</span>
                        <iconify-icon icon="solar:alt-arrow-down-bold" class="faq-icon transition-transform"></iconify-icon>
                    </div>
                    <div class="faq-answer">Standard delivery takes 3-5 business days. We offer express 24-hour delivery in major cities.</div>
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>Can I change my shipping address after ordering?</span>
                        <iconify-icon icon="solar:alt-arrow-down-bold" class="faq-icon transition-transform"></iconify-icon>
                    </div>
                    <div class="faq-answer">Yes, as long as the order hasn't been dispatched. Please contact support immediately for address updates.</div>
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>Do you provide international shipping?</span>
                        <iconify-icon icon="solar:alt-arrow-down-bold" class="faq-icon transition-transform"></iconify-icon>
                    </div>
                    <div class="faq-answer">Yes, we ship globally! Shipping costs and custom duties will be calculated at the checkout based on your currency.</div>
                </div>

                <div class="faq-category-title">
                    <iconify-icon icon="solar:card-bold"></iconify-icon> Payments & Gateways
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>What payment methods are supported?</span>
                        <iconify-icon icon="solar:alt-arrow-down-bold" class="faq-icon transition-transform"></iconify-icon>
                    </div>
                    <div class="faq-answer">We support PayPal and RazorPay for high-security transactions.</div>
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>How do I change the store currency?</span>
                        <iconify-icon icon="solar:alt-arrow-down-bold" class="faq-icon transition-transform"></iconify-icon>
                    </div>
                    <div class="faq-answer">You can switch between USD, INR, or BDT using the currency switcher in the dashboard header.</div>
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>Is it safe to pay on Project A?</span>
                        <iconify-icon icon="solar:alt-arrow-down-bold" class="faq-icon transition-transform"></iconify-icon>
                    </div>
                    <div class="faq-answer">Absolutely. We use 256-bit SSL encryption and never store your sensitive card details on our servers.</div>
                </div>

                <div class="faq-category-title">
                    <iconify-icon icon="solar:refresh-circle-bold"></iconify-icon> Returns & Refunds
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>What is your return policy?</span>
                        <iconify-icon icon="solar:alt-arrow-down-bold" class="faq-icon transition-transform"></iconify-icon>
                    </div>
                    <div class="faq-answer">We offer a 7-day hassle-free return policy if the product is in its original condition.</div>
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>How long does the refund take?</span>
                        <iconify-icon icon="solar:alt-arrow-down-bold" class="faq-icon transition-transform"></iconify-icon>
                    </div>
                    <div class="faq-answer">Once we receive and inspect the product, the refund is initiated within 5-7 business days to your original payment method.</div>
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>Can I cancel my order?</span>
                        <iconify-icon icon="solar:alt-arrow-down-bold" class="faq-icon transition-transform"></iconify-icon>
                    </div>
                    <div class="faq-answer">Orders can be canceled directly from the dashboard before they are processed for shipping.</div>
                </div>

                <div class="faq-category-title">
                    <iconify-icon icon="solar:user-bold"></iconify-icon> Account & Support
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>How do I reset my password?</span>
                        <iconify-icon icon="solar:alt-arrow-down-bold" class="faq-icon transition-transform"></iconify-icon>
                    </div>
                    <div class="faq-answer">Go to Profile Settings and click on 'Change Password'. You can also use the 'Forgot Password' link on the login page.</div>
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>How do I enable 2FA security?</span>
                        <iconify-icon icon="solar:alt-arrow-down-bold" class="faq-icon transition-transform"></iconify-icon>
                    </div>
                    <div class="faq-answer">Visit your security settings to enable Two-Factor Authentication for maximum account protection.</div>
                </div>
                <div class="faq-item" onclick="toggleFaq(this)">
                    <div class="faq-question">
                        <span>How do I contact live support?</span>
                        <iconify-icon icon="solar:alt-arrow-down-bold" class="faq-icon transition-transform"></iconify-icon>
                    </div>
                    <div class="faq-answer">Our support team is available 24/7. Use the 'Live Chat' button below to start a conversation instantly.</div>
                </div>

            </div>
        </div>

        <div class="support-footer-card text-center mt-20 p-12 rounded-[32px] shadow-sm transition-all">
            <p class="text-slate-500 dark:text-slate-400 font-bold text-sm uppercase tracking-widest">Still have questions?</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white mt-2 mb-10 tracking-tight">
                Our Support Team is here 24/7
            </h3>
            
            <div class="flex flex-wrap justify-center gap-6">
                <a href="mailto:support@project-a.com" class="inline-flex items-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-indigo-500/20">
                    Email Support <iconify-icon icon="solar:letter-bold" class="text-lg"></iconify-icon>
                </a>
                <a href="#" class="inline-flex items-center gap-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all">
                    Live Chat <iconify-icon icon="solar:chat-round-dots-bold" class="text-lg text-indigo-600"></iconify-icon>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFaq(element) {
        const isActive = element.classList.contains('active');
        document.querySelectorAll('.faq-item').forEach(item => {
            item.classList.remove('active');
        });
        if (!isActive) {
            element.classList.add('active');
        }
    }
</script>

<?php include '../partials/layouts/layoutBottom.php'; ?>