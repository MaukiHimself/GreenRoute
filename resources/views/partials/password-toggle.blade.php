{{-- Auto-adds a show/hide (eye) toggle to every password input on the page.
     Styling-agnostic: works across the app's Tailwind, Bootstrap and inline-CSS
     forms. Include once before </body>. --}}
<script>
(function () {
    var EYE = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>';
    var EYE_OFF = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';

    function enhance(input) {
        if (!input || input.dataset.pwToggle === '1') return;
        input.dataset.pwToggle = '1';

        // Wrapper keeps the input's own layout/width; positions the button.
        var wrap = document.createElement('span');
        wrap.style.cssText = 'position:relative;display:block;';
        input.parentNode.insertBefore(wrap, input);
        wrap.appendChild(input);

        // Reserve space on the right so text doesn't run under the icon.
        input.style.paddingRight = '2.6rem';

        var btn = document.createElement('button');
        btn.type = 'button';
        btn.tabIndex = -1;
        btn.setAttribute('aria-label', 'Show password');
        btn.innerHTML = EYE;
        btn.style.cssText = 'position:absolute;top:50%;right:.6rem;transform:translateY(-50%);' +
            'background:none;border:none;padding:0;margin:0;cursor:pointer;color:#6b7280;' +
            'display:flex;align-items:center;line-height:0;z-index:5;';
        wrap.appendChild(btn);

        btn.addEventListener('click', function () {
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.innerHTML = show ? EYE_OFF : EYE;
            btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    }

    function run(root) {
        (root || document).querySelectorAll('input[type="password"]').forEach(enhance);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { run(document); });
    } else {
        run(document);
    }

    // Catch inputs added dynamically (modals, AJAX forms, etc.).
    if (window.MutationObserver) {
        new MutationObserver(function (muts) {
            for (var i = 0; i < muts.length; i++) {
                for (var j = 0; j < muts[i].addedNodes.length; j++) {
                    var n = muts[i].addedNodes[j];
                    if (n.nodeType !== 1) continue;
                    if (n.matches && n.matches('input[type="password"]')) enhance(n);
                    else if (n.querySelectorAll) run(n);
                }
            }
        }).observe(document.documentElement, { childList: true, subtree: true });
    }
})();
</script>
