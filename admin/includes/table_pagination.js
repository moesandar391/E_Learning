function initTablePagination(config) {
    var container = document.getElementById(config.containerId || 'tablePagination');
    var input = document.getElementById(config.searchInput);
    var rows = Array.prototype.slice.call(document.querySelectorAll(config.rowSelector));
    var perPage = config.perPage || 10;
    var currentPage = 1;

    function getQuery() {
        return input ? (input.value || '').trim() : '';
    }

    function rowText(row) {
        if (config.searchText) return config.searchText(row);
        return row.textContent || '';
    }

    function matches(row) {
        var q = getQuery();
        if (!q) return true;
        var words = q.toLowerCase().split(/\s+/);
        var text = rowText(row).toLowerCase();
        var i;
        for (i = 0; i < words.length; i++) {
            if (text.indexOf(words[i]) === -1) return false;
        }
        return true;
    }

    function filtered() {
        return rows.filter(matches);
    }

    function totalPages(list) {
        return Math.max(1, Math.ceil(list.length / perPage));
    }

    function render(list) {
        var tp = totalPages(list);
        if (currentPage > tp) currentPage = tp;
        var start = (currentPage - 1) * perPage;
        var end = Math.min(start + perPage, list.length);
        var i;

        rows.forEach(function(r) { r.style.display = 'none'; });
        for (i = start; i < end; i++) {
            var row = list[i];
            row.style.display = '';
            if (!config.noNumbering) {
                var first = row.querySelector('td');
                if (first) first.textContent = i + 1;
            }
        }
        renderControls(list, tp);
    }

    function goToPage(p) {
        var list = filtered();
        var tp = totalPages(list);
        if (p < 1) p = 1;
        if (p > tp) p = tp;
        currentPage = p;
        render(list);
    }

    function button(label, target, disabled) {
        var cls = 'px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition';
        if (disabled) cls += ' pointer-events-none opacity-40';
        return '<button type="button" data-page="' + target + '" class="' + cls + '"' + (disabled ? ' disabled' : '') + '>' + label + '</button>';
    }

    function renderControls(list, tp) {
        if (!container) return;
        if (list.length === 0 || tp <= 1) {
            container.innerHTML = '';
            return;
        }
        var html = '<div class="flex flex-wrap items-center justify-between gap-2">'
            + '<p class="text-sm text-gray-500">Page ' + currentPage + ' of ' + tp + ' (' + list.length + ' total)</p>'
            + '<div class="flex items-center gap-1">'
            + button('First', 1, currentPage === 1)
            + button('&lt;', currentPage - 1, currentPage === 1)
            + '<span class="flex items-center gap-1 px-1"><label class="text-sm text-gray-500">Page</label>'
            + '<input type="number" min="1" max="' + tp + '" value="' + currentPage + '" class="w-16 px-2 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange" data-page-input>'
            + '</span>'
            + button('&gt;', currentPage + 1, currentPage === tp)
            + button('Last', tp, currentPage === tp)
            + '</div></div>';
        container.innerHTML = html;

        var buttons = container.querySelectorAll('button[data-page]');
        for (var i = 0; i < buttons.length; i++) {
            (function(btn) {
                btn.addEventListener('click', function() {
                    goToPage(parseInt(btn.getAttribute('data-page'), 10));
                });
            })(buttons[i]);
        }
        var numInput = container.querySelector('input[data-page-input]');
        if (numInput) {
            numInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    var v = parseInt(this.value, 10);
                    if (!isNaN(v)) goToPage(v);
                }
            });
        }
    }

    if (input) {
        var timer = null;
        input.addEventListener('keyup', function() {
            if (timer) clearTimeout(timer);
            timer = setTimeout(function() {
                currentPage = 1;
                render(filtered());
            }, 250);
        });
    }

    render(filtered());
}