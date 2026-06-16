// Admin Dashboard Layout Logic

(function() {
    const theme = localStorage.getItem('admin-theme') || 
        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
    
    const dir = localStorage.getItem('admin-dir') || 'rtl';
    document.documentElement.setAttribute('dir', dir);
    
    const lang = localStorage.getItem('admin-lang') || (dir === 'rtl' ? 'ar' : 'en');
    document.documentElement.setAttribute('lang', lang);
})();

function toggleSidebar() {
    const sidebar = document.getElementById('admin-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    
    if (!sidebar || !backdrop) return;
    
    if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
    } else {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
    }
}

function toggleTheme() {
    const html = document.documentElement;
    if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.setItem('admin-theme', 'light');
    } else {
        html.classList.add('dark');
        localStorage.setItem('admin-theme', 'dark');
    }
}

function toggleDirection() {
    const html = document.documentElement;
    const currentDir = html.getAttribute('dir') || 'rtl';
    const newDir = currentDir === 'rtl' ? 'ltr' : 'rtl';
    const newLang = newDir === 'rtl' ? 'ar' : 'en';
    
    localStorage.setItem('admin-dir', newDir);
    localStorage.setItem('admin-lang', newLang);
    
    // Set a cookie that expires in 1 year so the backend can read the locale selection
    document.cookie = "admin-lang=" + newLang + ";path=/;max-age=" + (60 * 60 * 24 * 365);
    
    window.location.reload();
}


window.toggleSidebar = toggleSidebar;
window.toggleTheme = toggleTheme;
window.toggleDirection = toggleDirection;


function adjustArrowRotation() {
    const arrow = document.querySelector('.dir-ltr\\:rotate-0');
    if (arrow) {
        const dir = document.documentElement.getAttribute('dir') || 'rtl';
        if (dir === 'ltr') {
            arrow.classList.remove('rotate-180');
        } else {
            arrow.classList.add('rotate-180');
        }
    }
}

document.addEventListener('DOMContentLoaded', adjustArrowRotation);

