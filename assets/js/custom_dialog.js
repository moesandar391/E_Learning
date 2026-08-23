/* Custom styled dialog box that replaces the native browser alert()/confirm()
   ("localhost says..." popups) across the whole app. */
(function () {
    'use strict';

    var overlay = null;
    var confirmCallback = null;

    function ensureOverlay() {
        if (overlay) return;
        overlay = document.createElement('div');
        overlay.id = 'customDialogOverlay';
        overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.5);display:none;align-items:center;justify-content:center;z-index:9999;';
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeOverlay();
        });
        overlay.innerHTML =
            '<div class="cd-dialog" style="background:#fff;border-radius:1rem;box-shadow:0 20px 50px rgba(0,0,0,0.25);width:420px;max-width:90vw;padding:24px;font-family:Inter,system-ui,sans-serif;">' +
                '<div style="display:flex;align-items:flex-start;gap:14px;">' +
                    '<div id="cdIcon" style="flex-shrink:0;width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;"></div>' +
                    '<div style="flex:1;min-width:0;">' +
                        '<h3 id="cdTitle" style="margin:0 0 6px;font-size:1.05rem;font-weight:700;color:#1f2937;"></h3>' +
                        '<p id="cdMessage" style="margin:0;font-size:0.875rem;color:#6b7280;line-height:1.5;white-space:pre-line;"></p>' +
                    '</div>' +
                '</div>' +
                '<div style="display:flex;justify-content:flex-end;gap:10px;margin-top:22px;">' +
                    '<button id="cdCancel" type="button" style="display:none;padding:9px 18px;font-size:0.875rem;font-weight:600;color:#6b7280;background:#f3f4f6;border:none;border-radius:0.5rem;cursor:pointer;">Cancel</button>' +
                    '<button id="cdOk" type="button" style="padding:9px 18px;font-size:0.875rem;font-weight:600;color:#fff;background:#FF8A00;border:none;border-radius:0.5rem;cursor:pointer;">OK</button>' +
                '</div>' +
            '</div>';
        document.body.appendChild(overlay);

        document.getElementById('cdOk').addEventListener('click', function () {
            var cb = confirmCallback;
            closeOverlay();
            if (cb) cb(true);
        });
        document.getElementById('cdCancel').addEventListener('click', function () {
            var cb = confirmCallback;
            closeOverlay();
            if (cb) cb(false);
        });
    }

    function closeOverlay() {
        confirmCallback = null;
        if (overlay) overlay.style.display = 'none';
    }

    function iconMarkup(bg, color, pathD) {
        return '<svg style="width:20px;height:20px;color:' + color + ';" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="' + pathD + '"/></svg>';
    }

    var ICONS = {
        info:    { bg: '#ffe9d1', color: '#FF8A00', d: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
        error:   { bg: '#fee2e2', color: '#dc2626', d: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' },
        success: { bg: '#d1fae5', color: '#059669', d: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
        question:{ bg: '#dbeafe', color: '#2563eb', d: 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' }
    };

    function show(title, message, iconType, buttons, onChoose) {
        ensureOverlay();
        var icon = ICONS[iconType] || ICONS.info;
        document.getElementById('cdIcon').style.background = icon.bg;
        document.getElementById('cdIcon').innerHTML = iconMarkup(icon.bg, icon.color, icon.d);
        document.getElementById('cdTitle').textContent = title;
        document.getElementById('cdMessage').textContent = message;

        var okBtn = document.getElementById('cdOk');
        var cancelBtn = document.getElementById('cdCancel');
        confirmCallback = onChoose || null;

        okBtn.style.display = '';
        okBtn.style.background = iconType === 'error' ? '#dc2626' : (iconType === 'success' ? '#059669' : '#FF8A00');
        okBtn.textContent = buttons.okText || 'OK';

        cancelBtn.style.display = buttons.cancel ? '' : 'none';
        cancelBtn.textContent = buttons.cancelText || 'Cancel';

        overlay.style.display = 'flex';
    }

    /* Styled alert (also replaces all native alert() calls). */
    window.showCustomAlert = function (message, title) {
        show(title || 'Notice', String(message), 'info', {}, null);
    };

    /* Styled confirm: showConfirm(message, onConfirm[, options]) */
    window.showConfirm = function (message, onConfirm, opts) {
        opts = opts || {};
        show(opts.title || 'Are you sure?', String(message), 'question', {
            cancel: true,
            okText: opts.okText || 'Confirm',
            cancelText: opts.cancelText || 'Cancel'
        }, function (ok) {
            if (ok && onConfirm) onConfirm();
        });
    };

    /* Confirm before submitting a form:
       confirmForm(event, form, message[, okText]) -> returns false, submits on confirm. */
    window.confirmForm = function (e, form, message, okText) {
        e.preventDefault();
        showConfirm(message, function () {
            form.submit();
        }, { okText: okText || 'Confirm' });
        return false;
    };

    /* Override the native alert() so every existing alert(...) becomes styled. */
    window.alert = function (msg) {
        window.showCustomAlert(msg);
    };
})();