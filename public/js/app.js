// Axios CSRF token setup
if (typeof axios !== 'undefined') {
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    
    // CSRF token'ı axios'a ekle
    const token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    }
}

// Lucide icons initialization
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Dark mode initialization
    const darkMode = localStorage.getItem('darkMode') === 'true';
    const html = document.documentElement;
    if (darkMode) {
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }
    
    // Dark mode değişikliklerini dinle
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                const isDark = html.classList.contains('dark');
                localStorage.setItem('darkMode', isDark ? 'true' : 'false');
                // Lucide icons'ı yeniden oluştur
                if (typeof lucide !== 'undefined') {
                    setTimeout(() => lucide.createIcons(), 100);
                }
            }
        });
    });
    
    observer.observe(html, {
        attributes: true,
        attributeFilter: ['class']
    });
});
