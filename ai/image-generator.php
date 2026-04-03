<?php 
    // paths are set according to ai/ directory
    require '../include/load.php'; 
    checkLogin(); 

    $title = 'Apextreme AI Image Architect';
    include '../partials/layouts/layoutTop.php'; 
?>

<style>
    /* --- Premium AI Tools Theme --- */
    .ai-wrapper { 
        background: #f8fafc; 
        min-height: 100vh; 
        transition: all 0.3s ease; 
        padding-bottom: 80px;
    }
    .dark .ai-wrapper { 
        background: #020617; /* Deep Black for Project A */
    }

    /* AI Hero Banner */
    .ai-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        border-radius: 32px;
        padding: 60px 40px;
        color: white;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .dark .ai-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-color: #1e293b;
    }

    /* Generator Card (Glassmorphism) */
    .generator-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        padding: 40px;
        transition: 0.3s;
        height: 100%;
    }
    .dark .generator-card { 
        background: rgba(15, 23, 42, 0.6); 
        backdrop-filter: blur(10px); 
        border-color: #1e293b; 
    }

    /* AI Inputs Visibility Fix */
    .field-label {
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #64748b;
        margin-bottom: 10px;
        display: block;
    }
    .dark .field-label { color: #f1f5f9; }

    .input-premium-ai {
        width: 100%;
        padding: 16px 20px;
        background: #f1f5f9;
        border: 2px solid transparent;
        border-radius: 16px;
        font-weight: 700;
        color: #1e293b;
        transition: 0.3s;
        font-size: 14px;
    }
    .dark .input-premium-ai { 
        background: #020617; 
        color: #ffffff; 
        border-color: #334155; 
    }
    .input-premium-ai:focus { 
        border-color: #6366f1; 
        outline: none; 
        background: white; 
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.15);
    }
    .dark .input-premium-ai:focus { background: #0f172a; }

    /* Generate Button */
    .btn-generate-ai {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        color: white !important;
        padding: 18px 40px;
        border-radius: 20px;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 2px;
        transition: 0.3s;
        box-shadow: 0 10px 30px -5px rgba(168, 85, 247, 0.5);
    }
    .btn-generate-ai:hover { 
        transform: translateY(-3px); 
        box-shadow: 0 15px 40px -5px rgba(168, 85, 247, 0.6);
    }

    /* Option Cards */
    .option-card {
        background: #f1f5f9;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        border: 2px solid transparent;
        cursor: pointer;
        transition: 0.3s;
    }
    .dark .option-card { background: #0f172a; border-color: #1e293b; }
    
    .option-card:hover { transform: translateY(-5px); border-color: #6366f1; }
    
    /* Option Checked State */
    .option-radio:checked + .option-card {
        border-color: #6366f1;
        background: rgba(99, 102, 241, 0.05);
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.1);
    }

    /* Result Preview */
    .result-preview-box {
        background: #f1f5f9;
        border-radius: 20px;
        aspect-ratio: 16/10;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 2px dashed #cbd5e1;
        transition: 0.3s;
    }
    .dark .result-preview-box { background: #020617; border-color: #334155; }
    
    .loading-pulse {
        animation: pulse 1.5s infinite;
        color: #a855f7;
    }
    @keyframes pulse { 0% { opacity: 0.5; } 50% { opacity: 1; } 100% { opacity: 0.5; } }
</style>

<div class="ai-wrapper">
    <div class="px-6 py-10 max-w-7xl mx-auto">
        
        <div class="flex items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter flex items-center gap-3">
                    <iconify-icon icon="solar:pen-new-square-bold-duotone" class="text-indigo-600"></iconify-icon>
                    Apextreme AI Image Architect
                </h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-[3px] mt-1">AI-Powered Image Generation Portal</p>
            </div>
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:bolt-bold-duotone" class="text-lg text-emerald-500 loading-pulse"></iconify-icon>
                <span class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Active Model: DALL-E 3</span>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-8">
            
            <div class="col-span-12 lg:col-span-7">
                <div class="generator-card shadow-xl">
                    <form action="#" method="POST" id="aiGeneratorForm">
                        
                        <div class="mb-10">
                            <label class="field-label">Describe your vision *</label>
                            <textarea name="prompt" class="input-premium-ai" rows="5" placeholder="A futuristic city with flying cars at sunset, photorealistic, cinematic lighting..." required></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-8 mb-10">
                            <div class="col-span-2">
                                <label class="field-label">Image Style</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <label class="relative">
                                        <input type="radio" name="style" value="photorealistic" class="option-radio sr-only" checked>
                                        <div class="option-card">
                                            <iconify-icon icon="solar:camera-bold" class="text-2xl text-indigo-600 mb-2"></iconify-icon>
                                            <span class="text-xs font-black text-slate-700 dark:text-slate-300 block">Photorealistic</span>
                                        </div>
                                    </label>
                                    <label class="relative">
                                        <input type="radio" name="style" value="cinematic" class="option-radio sr-only">
                                        <div class="option-card">
                                            <iconify-icon icon="solar:clapperboard-bold" class="text-2xl text-purple-600 mb-2"></iconify-icon>
                                            <span class="text-xs font-black text-slate-700 dark:text-slate-300 block">Cinematic</span>
                                        </div>
                                    </label>
                                    <label class="relative">
                                        <input type="radio" name="style" value="digital_art" class="option-radio sr-only">
                                        <div class="option-card">
                                            <iconify-icon icon="solar:palette-bold" class="text-2xl text-rose-600 mb-2"></iconify-icon>
                                            <span class="text-xs font-black text-slate-700 dark:text-slate-300 block">Digital Art</span>
                                        </div>
                                    </label>
                                    <label class="relative">
                                        <input type="radio" name="style" value="anime" class="option-radio sr-only">
                                        <div class="option-card">
                                            <iconify-icon icon="solar:leaf-bold" class="text-2xl text-emerald-600 mb-2"></iconify-icon>
                                            <span class="text-xs font-black text-slate-700 dark:text-slate-300 block">Anime</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="col-span-1">
                                <label class="field-label">Aspect Ratio</label>
                                <select name="aspect_ratio" class="input-premium-ai appearance-none">
                                    <option value="16:9">16:9 Landscape</option>
                                    <option value="1:1">1:1 Square</option>
                                    <option value="9:16">9:16 Portrait</option>
                                </select>
                            </div>

                            <div class="col-span-1">
                                <label class="field-label">Quality Preset</label>
                                <select name="quality" class="input-premium-ai appearance-none">
                                    <option value="standard">Standard HD</option>
                                    <option value="hd">Ultra HD (4K)</option>
                                )</select>
                            </div>
                        </div>

                        <div class="mb-12">
                            <label class="field-label">Negative Prompt (Elements to avoid)</label>
                            <input type="text" name="negative_prompt" class="input-premium-ai" placeholder="blurry, low quality, distorted, watermark...">
                        </div>

                        <div class="flex justify-center">
                            <button type="submit" class="btn-generate-ai flex items-center gap-3">
                                <iconify-icon icon="solar:magic-stick-3-bold-duotone" class="text-xl"></iconify-icon>
                                Generate Artwork
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-5">
                <div class="generator-card shadow-xl flex flex-col">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white mb-8 flex items-center gap-2">
                        <iconify-icon icon="solar:tv-bold-duotone" class="text-indigo-600 text-2xl"></iconify-icon>
                        Generation Canvas
                    </h3>
                    
                    <div class="result-preview-box shadow-inner flex-grow mb-8">
                        <div class="text-center p-10" id="initialState">
                            <iconify-icon icon="solar:ghost-bold-duotone" class="text-6xl text-slate-300 dark:text-slate-700 mb-4"></iconify-icon>
                            <p class="text-sm font-bold text-slate-400 dark:text-slate-600">Your AI-generated masterpiece will appear here.</p>
                        </div>

                        <img src="" class="w-full h-full object-cover hidden" id="generatedImage">
                    </div>

                    <div class="flex items-center justify-between gap-4 mt-auto pt-8 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-bold text-slate-400">Project A AI Token Cost: 2</span>
                        <div class="flex items-center gap-3">
                            <button class="text-slate-400 hover:text-indigo-600 transition" title="Download">
                                <iconify-icon icon="solar:download-bold" class="text-xl"></iconify-icon>
                            </button>
                            <button class="text-slate-400 hover:text-indigo-600 transition" title="Share">
                                <iconify-icon icon="solar:share-bold" class="text-xl"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../partials/layouts/layoutBottom.php'; ?>