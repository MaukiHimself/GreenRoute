/*
 * input-guard.js
 * System-wide input restrictions, injected on every HTML page by
 * App\Http\Middleware\InputGuard. Styling-agnostic; works across the app's
 * Tailwind, Bootstrap and inline-CSS forms.
 *
 * Modes (chosen per field from explicit signals first, then a curated
 * name/id keyword map):
 *   - digits  : integers only            (phone, quantity, zip, age, year, ...)
 *   - decimal : money — digits + one dot (amount, price, cost, fee, rate, ...)
 *   - letters : letters + space - '      (personal names only)
 *
 * Fields are left completely untouched unless they match a rule, so emails,
 * addresses, business names, usernames and plate/licence numbers keep working.
 */
(function () {
    'use strict';

    // --- Field classification --------------------------------------------

    // Never touch these input types.
    var SKIP_TYPES = ['hidden', 'password', 'email', 'url', 'date', 'datetime-local',
        'time', 'month', 'week', 'file', 'checkbox', 'radio', 'color', 'range', 'search'];

    // Names/ids that look numeric but are actually alphanumeric — leave alone.
    var ALNUM_EXCLUDE = /(plate|licen[cs]e|registration|business|company|user_?name|account_?name|address|email|_id\b|reference|serial|code|vin|imei)/i;

    var DIGITS_RE  = /(phone|mobile|\btel\b|whatsapp|quantity|\bqty\b|zip|postal|\bage\b|\byear\b|volume|capacity|pincode|\bpin\b|otp|meter)/i;
    var DECIMAL_RE = /(amount|price|cost|\bfee\b|_fee|fee_|\brate\b|_rate|rate_|salary|balance|subtotal|\btotal\b|\btax\b|payment|paid|discount|charge|wage|budget)/i;
    var LETTERS_RE = /^(.*_)?(first_?name|last_?name|full_?name|f_?name|l_?name|surname|contact_?person|contact_?name|middle_?name|other_?names|\bname\b|name)$/i;

    // Sanitizers per mode.
    function cleanDigits(v)  { return v.replace(/[^0-9]/g, ''); }
    function cleanDecimal(v) {
        v = v.replace(/[^0-9.]/g, '');
        var i = v.indexOf('.');
        if (i !== -1) { v = v.slice(0, i + 1) + v.slice(i + 1).replace(/\./g, ''); }
        return v;
    }
    function cleanLetters(v) { return v.replace(/[^A-Za-zÀ-ɏ '\-]/g, ''); }

    function modeFor(el) {
        var type = (el.getAttribute('type') || 'text').toLowerCase();
        if (SKIP_TYPES.indexOf(type) !== -1) return null;

        // 1) Explicit opt-in wins.
        var g = (el.getAttribute('data-guard') || '').toLowerCase();
        if (g === 'digits' || g === 'decimal' || g === 'letters') return g;

        // 2) Native numeric signals.
        var inputmode = (el.getAttribute('inputmode') || '').toLowerCase();
        if (type === 'number') {
            var step = (el.getAttribute('step') || '').toLowerCase();
            return (step && step !== 'any' && step.indexOf('.') === -1 && Number(step) % 1 === 0)
                ? 'digits' : 'decimal';
        }
        if (inputmode === 'numeric') return 'digits';
        if (inputmode === 'decimal') return 'decimal';

        // 3) Keyword map on name / id.
        var key = (el.getAttribute('name') || '') + ' ' + (el.id || '');
        if (!key.trim()) return null;
        if (ALNUM_EXCLUDE.test(key)) return null;
        if (DECIMAL_RE.test(key)) return 'decimal';
        if (DIGITS_RE.test(key))  return 'digits';

        var nameOnly = (el.getAttribute('name') || el.id || '');
        if (LETTERS_RE.test(nameOnly) && !/user_?name/i.test(nameOnly)) return 'letters';

        return null;
    }

    var CLEANERS = { digits: cleanDigits, decimal: cleanDecimal, letters: cleanLetters };

    function enhance(el) {
        if (!el || el.dataset.inputGuard) return;
        var mode = modeFor(el);
        if (!mode) return;
        el.dataset.inputGuard = mode;
        var clean = CLEANERS[mode];

        if (mode !== 'letters') {
            el.setAttribute('inputmode', mode === 'digits' ? 'numeric' : 'decimal');
        }

        el.addEventListener('input', function () {
            var cleaned = clean(el.value);
            if (cleaned !== el.value) {
                var pos = el.selectionStart, drop = el.value.length - cleaned.length;
                el.value = cleaned;
                try { el.setSelectionRange(pos - drop, pos - drop); } catch (e) {}
            }
        });
    }

    function run(root) {
        (root || document).querySelectorAll('input, textarea').forEach(enhance);
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
                    if (n.matches && n.matches('input, textarea')) enhance(n);
                    else if (n.querySelectorAll) run(n);
                }
            }
        }).observe(document.documentElement, { childList: true, subtree: true });
    }
})();
