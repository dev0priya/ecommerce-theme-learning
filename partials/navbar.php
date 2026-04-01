<nav class="h-[70px] flex items-center justify-between px-6 bg-[#1e293b] border-b border-slate-700 transition-colors duration-300">
    
    <!-- LEFT -->
    <div class="flex items-center gap-6 flex-1">
        <button class="text-slate-400 hover:text-white">
            <i class="ri-menu-2-line text-2xl"></i>
        </button>
        
        <!-- SEARCH -->
        <div class="flex items-center gap-3 w-full max-w-[450px]">
            <input type="text" 
                   placeholder="Search" 
                   class="flex-1 h-11 bg-[#28334e] border border-slate-600 rounded-lg px-4 text-sm text-slate-200 focus:outline-none focus:border-blue-500 placeholder:text-slate-500 transition-all">

            <button id="search-btn" class="w-11 h-11 flex items-center justify-center rounded-lg bg-[#28334e] border border-slate-600 transition-all">
                <i class="ri-search-line text-lg"></i>
            </button>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="flex items-center gap-4">
        
        <!-- THEME -->
        <button type="button" id="theme-toggle" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-800/80 border border-slate-700">
            <iconify-icon id="theme-icon" icon="ri:moon-line" class="text-lg"></iconify-icon>
        </button>

        <!-- FLAG -->
        <div class="relative">
            <button data-dropdown-toggle="langDropdown" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-800/80 overflow-hidden border border-slate-700">
                <img id="selectedFlag" src="https://flagcdn.com/w40/us.png" class="w-7 h-5 object-cover">
            </button>

            <!-- 🔥 UPDATED DROPDOWN -->
            <div id="langDropdown" class="hidden absolute right-0 mt-2 w-72 bg-white dark:bg-slate-800 text-black dark:text-white rounded-lg shadow-lg p-3 z-50 max-h-64 overflow-y-auto">

                <button onclick="changeFlag('us')" class="flex items-center gap-3 w-full px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded">
                    <img src="https://flagcdn.com/w40/us.png" class="w-6 h-4">
                    <span>English (US)</span>
                </button>

                <button onclick="changeFlag('in')" class="flex items-center gap-3 w-full px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded">
                    <img src="https://flagcdn.com/w40/in.png" class="w-6 h-4">
                    <span>India</span>
                </button>

                <button onclick="changeFlag('gb')" class="flex items-center gap-3 w-full px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded">
                    <img src="https://flagcdn.com/w40/gb.png" class="w-6 h-4">
                    <span>United Kingdom</span>
                </button>

                <button onclick="changeFlag('fr')" class="flex items-center gap-3 w-full px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded">
                    <img src="https://flagcdn.com/w40/fr.png" class="w-6 h-4">
                    <span>France</span>
                </button>

                <button onclick="changeFlag('de')" class="flex items-center gap-3 w-full px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded">
                    <img src="https://flagcdn.com/w40/de.png" class="w-6 h-4">
                    <span>Germany</span>
                </button>

                <button onclick="changeFlag('jp')" class="flex items-center gap-3 w-full px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded">
                    <img src="https://flagcdn.com/w40/jp.png" class="w-6 h-4">
                    <span>Japan</span>
                </button>

                <button onclick="changeFlag('kr')" class="flex items-center gap-3 w-full px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded">
                    <img src="https://flagcdn.com/w40/kr.png" class="w-6 h-4">
                    <span>Korea</span>
                </button>

                <button onclick="changeFlag('bd')" class="flex items-center gap-3 w-full px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded">
                    <img src="https://flagcdn.com/w40/bd.png" class="w-6 h-4">
                    <span>Bangladesh</span>
                </button>

                <button onclick="changeFlag('ae')" class="flex items-center gap-3 w-full px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded">
                    <img src="https://flagcdn.com/w40/ae.png" class="w-6 h-4">
                    <span>UAE</span>
                </button>

            </div>
        </div>

        <!-- MAIL -->
        <div class="relative">
            <button id="mail-btn" data-dropdown-toggle="messageDropdown" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-800/80 border border-slate-700">
                <i class="ri-mail-line text-lg"></i>
            </button>

            <div id="messageDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white dark:bg-slate-800 text-black dark:text-white rounded-lg shadow-lg p-3 z-50">
                No messages
            </div>
        </div>

        <!-- NOTIFICATION -->
        <div class="relative">
            <button id="bell-btn" data-dropdown-toggle="notificationDropdown" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-800/80 border border-slate-700">
                <i class="ri-notification-3-line text-lg"></i>
            </button>

            <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white dark:bg-slate-800 text-black dark:text-white rounded-lg shadow-lg p-3 z-50">
                No notifications
            </div>
        </div>

        <!-- PROFILE -->
        <div class="relative flex items-center ml-2 border-l border-slate-700 pl-4">
            <button id="profile-btn" data-dropdown-toggle="profileDropdown" class="w-10 h-10 bg-gray-400 rounded-full flex items-center justify-center text-[9px] font-bold uppercase">
                40X40
            </button>

            <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 text-black dark:text-white rounded-lg shadow-lg p-2 z-50">
                <a href="#" class="block px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700">Profile</a>
                <a href="#" class="block px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700">Settings</a>
            </div>
        </div>

    </div>
</nav>

<script>
// THEME
const html = document.documentElement;
const toggleBtn = document.getElementById("theme-toggle");
const themeIcon = document.getElementById("theme-icon");

const icons = [
    document.querySelector("#search-btn i"),
    document.querySelector("#mail-btn i"),
    document.querySelector("#bell-btn i")
];

function applyTheme(theme) {
    if (theme === "dark") {
        html.classList.add("dark");
        html.classList.remove("light");
        themeIcon.setAttribute("icon", "ri:sun-line");
        icons.forEach(i => i.style.color = "#ffffff");
    } else {
        html.classList.remove("dark");
        html.classList.add("light");
        themeIcon.setAttribute("icon", "ri:moon-line");
        icons.forEach(i => i.style.color = "#000000");
    }
}

const saved = localStorage.getItem("theme") || "light";
applyTheme(saved);

toggleBtn.addEventListener("click", () => {
    const newTheme = html.classList.contains("dark") ? "light" : "dark";
    localStorage.setItem("theme", newTheme);
    applyTheme(newTheme);
});

// FLAG
function changeFlag(code) {
    document.getElementById("selectedFlag").src = `https://flagcdn.com/w40/${code}.png`;
}

// DROPDOWN
document.querySelectorAll('[data-dropdown-toggle]').forEach(btn => {
    btn.addEventListener('click', function () {
        const target = document.getElementById(this.getAttribute('data-dropdown-toggle'));
        document.querySelectorAll('[id$="Dropdown"]').forEach(d => {
            if (d !== target) d.classList.add('hidden');
        });
        target.classList.toggle('hidden');
    });
});

document.addEventListener('click', function (e) {
    if (!e.target.closest('[data-dropdown-toggle]')) {
        document.querySelectorAll('[id$="Dropdown"]').forEach(d => d.classList.add('hidden'));
    }
});
</script>