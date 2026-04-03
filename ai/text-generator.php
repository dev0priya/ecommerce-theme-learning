<?php 
    require '../include/load.php'; 
    checkLogin(); 

    $title = 'Apextreme AI Text Architect';
    include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* --- Premium AI Text Theme --- */
    .ai-wrapper { 
        background: #f8fafc; 
        min-height: 100vh; 
        transition: all 0.3s ease; 
        padding-bottom: 50px;
    }
    .dark .ai-wrapper { background: #020617; }

    /* Glassmorphism Sidebar & Card */
    .generator-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        transition: 0.3s;
        height: 100%;
        overflow: hidden;
    }
    .dark .generator-card { 
        background: rgba(15, 23, 42, 0.6); 
        backdrop-filter: blur(10px); 
        border-color: #1e293b; 
    }

    /* Chat List Styling */
    .ai-chat-list li a {
        display: block;
        padding: 10px 15px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        transition: 0.3s;
    }
    .ai-chat-list li a:hover {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1 !important;
    }

    /* Message Bubbles */
    .message-box {
        padding: 20px;
        border-radius: 20px;
        margin-bottom: 20px;
        border: 1px solid transparent;
    }
    .user-msg {
        background: #f1f5f9;
        border-color: #e2e8f0;
    }
    .dark .user-msg { background: #0f172a; border-color: #1e293b; }

    .ai-msg {
        background: white;
        border-color: #6366f1;
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.05);
    }
    .dark .ai-msg { background: #020617; border-color: #312e81; }

    /* Input Styling */
    .chat-input-area {
        background: #f1f5f9;
        border-radius: 16px;
        padding: 10px 20px;
        border: 2px solid transparent;
        transition: 0.3s;
    }
    .dark .chat-input-area { background: #020617; border-color: #334155; }
    .chat-input-area:focus-within { border-color: #6366f1; background: white; }
    .dark .chat-input-area:focus-within { background: #0f172a; }

    .btn-send {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        color: white !important;
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 15px rgba(168, 85, 247, 0.3);
    }
</style>

<div class="ai-wrapper">
    <div class="px-6 py-10 max-w-7xl mx-auto">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter flex items-center gap-3 italic">
                    <iconify-icon icon="solar:chat-round-dots-bold-duotone" class="text-indigo-600"></iconify-icon>
                    Apextreme Text Architect
                </h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-[3px] mt-1">Advanced Language Model</p>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">
            
            <div class="col-span-12 lg:col-span-4 2xl:col-span-3">
                <div class="generator-card shadow-xl p-6">
                    <a href="#" class="btn bg-indigo-600 text-white w-full py-4 rounded-2xl font-black uppercase tracking-widest text-xs flex items-center justify-center gap-2 mb-8 hover:bg-indigo-700 transition-all">
                        <iconify-icon icon="solar:add-circle-bold" class="text-lg"></iconify-icon> New Session
                    </a>

                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[3px] mb-4">Recent Conversations</h4>
                    <ul class="ai-chat-list space-y-2 overflow-y-auto max-h-[500px] pr-2">
                        <li><a href="#" class="text-slate-600 dark:text-slate-400">UI/UX Design Roadmap 2026</a></li>
                        <li><a href="#" class="text-slate-600 dark:text-slate-400">E-commerce DB Optimization</a></li>
                        <li><a href="#" class="text-slate-600 dark:text-slate-400">Product SEO Keywords</a></li>
                        <li><a href="#" class="text-slate-600 dark:text-slate-400">Customer Support Scripts</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
                <div class="generator-card shadow-xl flex flex-col h-[700px]">
                    
                    <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl flex items-center justify-center text-indigo-600 text-xl">
                                <iconify-icon icon="solar:ghost-bold-duotone"></iconify-icon>
                            </div>
                            <div>
                                <h6 class="font-black text-slate-800 dark:text-white mb-0">Project A AI Bot</h6>
                                <span class="text-[10px] font-bold text-emerald-500 uppercase">Online & Ready</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex-grow overflow-y-auto p-8 space-y-6">
                        <div class="message-box user-msg ml-auto max-w-[80%]">
                            <div class="flex items-center gap-3 mb-3">
                                <img src="../assets/images/chat/1.png" class="w-6 h-6 rounded-full border border-white">
                                <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tighter italic">Adam Milner</span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">Can you write a premium product description for a wireless earbud set for my E-commerce Project A?</p>
                        </div>

                        <div class="message-box ai-msg mr-auto max-w-[80%]">
                            <div class="flex items-center gap-3 mb-4">
                                <img src="../assets/images/wow-dash-favicon.png" class="w-6 h-6 rounded-full border border-indigo-200">
                                <span class="text-xs font-black text-indigo-600 uppercase tracking-widest">Architect AI</span>
                            </div>
                            <div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed space-y-4">
                                <p>Certainly! Here is a premium description optimized for your platform:</p>
                                <p class="font-bold border-l-4 border-indigo-600 pl-4">"Experience sonic purity with the Apextreme Pro Buds. Crafted for the modern minimalist, these buds offer 40H battery life and active noise cancellation."</p>
                            </div>
                            
                            <div class="mt-6 flex items-center gap-4 pt-4 border-t border-slate-50 dark:border-slate-800">
                                <button class="text-slate-400 hover:text-indigo-600 transition"><iconify-icon icon="solar:copy-bold"></iconify-icon></button>
                                <button class="text-slate-400 hover:text-rose-600 transition"><iconify-icon icon="solar:dislike-bold"></iconify-icon></button>
                                <button class="text-slate-400 hover:text-indigo-600 transition ml-auto flex items-center gap-2 text-[10px] font-black uppercase tracking-widest">Regenerate <iconify-icon icon="solar:repeat-bold"></iconify-icon></button>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                        <form class="flex items-center gap-4">
                            <div class="chat-input-area flex-grow flex items-center">
                                <textarea class="w-full bg-transparent border-0 focus:ring-0 text-sm font-bold text-slate-600 dark:text-slate-300 resize-none py-1" rows="1" placeholder="Describe what you want to write..."></textarea>
                                <div class="flex items-center gap-3 text-slate-400 px-2">
                                    <iconify-icon icon="solar:gallery-bold" class="cursor-pointer hover:text-indigo-600"></iconify-icon>
                                    <iconify-icon icon="solar:link-bold" class="cursor-pointer hover:text-indigo-600"></iconify-icon>
                                </div>
                            </div>
                            <button type="submit" class="btn-send">
                                <iconify-icon icon="solar:plain-bold" class="text-xl"></iconify-icon>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../partials/layouts/layoutBottom.php'; ?>