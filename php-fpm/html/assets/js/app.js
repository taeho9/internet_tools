/**
 * assets/js/app.js - Taeho's Internet Tools Interactions
 */

// Theme Management (Light / Dark)
(function initTheme() {
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
    }
})();

function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
}

// Copy to Clipboard Utility
function copyToClipboard(elementId, btnElement) {
    const target = document.getElementById(elementId);
    if (!target) return;

    let textToCopy = '';
    if (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA') {
        textToCopy = target.value;
    } else {
        textToCopy = target.innerText;
    }

    if (!textToCopy) return;

    navigator.clipboard.writeText(textToCopy).then(() => {
        if (btnElement) {
            const originalHtml = btnElement.innerHTML;
            btnElement.innerHTML = `
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg> 복사완료!
            `;
            btnElement.style.borderColor = 'var(--success)';
            btnElement.style.color = 'var(--success)';

            setTimeout(() => {
                btnElement.innerHTML = originalHtml;
                btnElement.style.borderColor = '';
                btnElement.style.color = '';
            }, 2000);
        }
    }).catch(err => {
        console.error('Failed to copy: ', err);
        alert('클립보드 복사에 실패했습니다.');
    });
}

// ICS Calendar File Download Utility
function downloadIcs(elementId, filename) {
    const target = document.getElementById(elementId);
    if (!target) return;
    const content = target.value || target.innerText;
    if (!content) return;

    const blob = new Blob([content], { type: 'text/calendar;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', filename || 'calendar.ics');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}
