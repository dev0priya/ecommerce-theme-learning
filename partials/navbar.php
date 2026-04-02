<nav class="h-[70px] flex items-center justify-between px-6 bg-[#1e293b] border-b border-slate-700 transition-colors duration-300">
    
    <div class="flex items-center gap-6 flex-1">
        <button class="text-slate-400 hover:text-white">
            <i class="ri-menu-2-line text-2xl"></i>
        </button>
        
        <div class="flex items-center gap-3 w-full max-w-[450px]">
            <input type="text" 
                   placeholder="Search" 
                   class="flex-1 h-11 bg-[#28334e] border border-slate-600 rounded-lg px-4 text-sm text-slate-200 focus:outline-none focus:border-blue-500 placeholder:text-slate-500 transition-all">

            <button id="search-btn" class="w-11 h-11 flex items-center justify-center rounded-lg bg-[#28334e] border border-slate-600 transition-all">
                <i class="ri-search-line text-lg"></i>
            </button>
        </div>
    </div>

    <div class="flex items-center gap-4">
        
        <button type="button" id="theme-toggle" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-800/80 border border-slate-700">
            <iconify-icon id="theme-icon" icon="ri:moon-line" class="text-lg"></iconify-icon>
        </button>

        <div class="relative">
            <button data-dropdown-toggle="langDropdown" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-800/80 overflow-hidden border border-slate-700">
                <img id="selectedFlag" src="https://flagcdn.com/w40/us.png" class="w-7 h-5 object-cover">
            </button>
            <div id="langDropdown" class="hidden absolute right-0 mt-4 w-72 bg-white dark:bg-slate-800 text-black dark:text-white rounded-lg shadow-lg p-3 z-50 border border-gray-200 dark:border-slate-700">
                <button onclick="changeFlag('us')" class="flex items-center gap-3 w-full px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded text-black dark:text-white">
                    <img src="https://flagcdn.com/w40/us.png" class="w-6 h-4">
                    <span>English (US)</span>
                </button>
            </div>
        </div>

        <div class="relative">
            <button id="mail-btn" data-dropdown-toggle="messageDropdown" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-800/80 border border-slate-700">
                <i class="ri-mail-line text-lg"></i>
            </button>
            <div id="messageDropdown" class="hidden absolute right-0 mt-4 w-64 bg-white dark:bg-slate-800 text-black dark:text-white rounded-lg shadow-lg p-3 z-50">No messages</div>
        </div>

        <div class="relative">
            <button id="bell-btn" data-dropdown-toggle="notificationDropdown" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-800/80 border border-slate-700">
                <i class="ri-notification-3-line text-lg"></i>
            </button>
            <div id="notificationDropdown" class="hidden absolute right-0 mt-4 w-64 bg-white dark:bg-slate-800 text-black dark:text-white rounded-lg shadow-lg p-3 z-50">No notifications</div>
        </div>

        <div class="relative flex items-center ml-2 border-l border-slate-700 pl-4">
            <button id="profile-btn" data-dropdown-toggle="profileDropdown" class="w-10 h-10 rounded-full overflow-hidden border border-slate-600 focus:ring-2 focus:ring-blue-500 transition-all flex items-center justify-center bg-slate-700">
                <img src="<?= BASE_URL ?>assets/uploads/user-icon.jpg" alt="User Profile" class="min-w-full min-h-full w-full h-full object-cover block">
            </button>

            <div id="profileDropdown" class="hidden absolute right-0 top-[calc(100%+2rem)] w-64 rounded-xl shadow-2xl z-[9999] overflow-hidden border border-gray-200" style="background-color: white !important; display: none;">
                
                <div class="p-4" style="background-color: #3b4b70 !important; color: white !important;">
                    <div class="flex justify-between items-start">
                        <div>
                            <h6 class="font-bold text-lg mb-0 leading-none" style="color: white !important; margin: 0;">Shahidul Islam</h6>
                            <span class="text-xs" style="color: #cbd5e1 !important;">Admin</span>
                        </div>
                        <button onclick="document.getElementById('profileDropdown').classList.add('hidden')" style="color: white !important; background: transparent; border: none;">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="p-2" style="background-color: white !important;">
                    <a href="<?= BASE_URL ?>view-myprofile.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition-colors group text-decoration-none">
                        <i class="ri-user-line text-lg" style="color: #64748b !important;"></i>
                        <span class="font-medium text-sm" style="color: #1e293b !important;">My Profile</span>
                    </a>

                    <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition-colors group text-decoration-none">
                        <i class="ri-mail-line text-lg" style="color: #64748b !important;"></i>
                        <span class="font-medium text-sm" style="color: #1e293b !important;">Inbox</span>
                    </a>

                    <a href="<?= BASE_URL ?>company.php" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 rounded-lg transition-colors group text-decoration-none">
                        <i class="ri-settings-4-line text-lg" style="color: #64748b !important;"></i>
                        <span class="font-medium text-sm" style="color: #1e293b !important;">Setting</span>
                    </a>

                    <hr class="my-2 border-gray-100">

                    <a href="<?= BASE_URL ?>logout.php" class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 rounded-lg transition-colors group text-decoration-none">
                        <i class="ri-logout-box-r-line text-lg" style="color: #dc2626 !important;"></i>
                        <span class="font-medium text-sm" style="color: #dc2626 !important;">Log Out</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</nav>

<script>
// THEME LOGIC
const html = document.documentElement;
const toggleBtn = document.getElementById("theme-toggle");
const themeIcon = document.getElementById("theme-icon");

const iconsToColor = [
    document.querySelector("#search-btn i"),
    document.querySelector("#mail-btn i"),
    document.querySelector("#bell-btn i")
];

function applyTheme(theme) {
    if (theme === "dark") {
        html.classList.add("dark");
        html.classList.remove("light");
        themeIcon.setAttribute("icon", "ri:sun-line");
        iconsToColor.forEach(i => { if(i) i.style.color = "#ffffff"; });
    } else {
        html.classList.remove("dark");
        html.classList.add("light");
        themeIcon.setAttribute("icon", "ri:moon-line");
        iconsToColor.forEach(i => { if(i) i.style.color = "#000000"; });
    }
}

const savedTheme = localStorage.getItem("theme") || "light";
applyTheme(savedTheme);

toggleBtn.addEventListener("click", () => {
    const newTheme = html.classList.contains("dark") ? "light" : "dark";
    localStorage.setItem("theme", newTheme);
    applyTheme(newTheme);
});

// DROPDOWN TOGGLE
document.querySelectorAll('[data-dropdown-toggle]').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        const targetId = this.getAttribute('data-dropdown-toggle');
        const target = document.getElementById(targetId);
        
        document.querySelectorAll('[id$="Dropdown"]').forEach(d => {
            if (d !== target) d.classList.add('hidden');
        });
        
        if (target.classList.contains('hidden')) {
            target.classList.remove('hidden');
            target.style.display = 'block';
        } else {
            target.classList.add('hidden');
            target.style.display = 'none';
        }
    });
});

document.addEventListener('click', function (e) {
    if (!e.target.closest('.relative')) {
        document.querySelectorAll('[id$="Dropdown"]').forEach(d => {
            d.classList.add('hidden');
            d.style.display = 'none';
        });
    }
});

function changeFlag(code) {
    document.getElementById("selectedFlag").src = `https://flagcdn.com/w40/${code}.png`;
}
</script>