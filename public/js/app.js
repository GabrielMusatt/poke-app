(function () {
    // Show a temporary alert message in the #alerts container
    function showAlert(msg, type) {
        const box = document.getElementById('alerts');
        const div = document.createElement('div');
        div.className = 'alert ' + (type || 'success');
        div.textContent = msg;
        box.innerHTML = '';
        box.appendChild(div);
    }

    // Intercept forms with class="ajax-form" and submit via fetch(FormData)
    async function handleAjaxSubmit(e) {
        // Seach the form (with the .ajax-form css class)
        const form = e.target.closest('form.ajax-form');
        if (!form) return;
        e.preventDefault();

        // Confirm deletes (through an alert)
        if (form.action.includes('/delete/')) {
            if (!confirm('Delete this user?')) return;
        }

        const btn = form.querySelector('button[type=submit]');
        if (btn) btn.disabled = true;

        try {
            const res = await fetch(form.action, {
                method: form.method || 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (res.status === 422) {
                const data = await res.json();
                const messages = [];
                if (data.errors) {
                    for (const k in data.errors) messages.push(data.errors[k][0]);
                } else if (data.message) {
                    messages.push(data.message);
                }
                showAlert(messages.join('\n'), 'error');
                return;
            }

            const data = await res.json();
            if (data && data.html) {
                const tbody = document.getElementById('users-tbody');
                if (tbody) tbody.innerHTML = data.html;
            }
            if (data && data.message) {
                showAlert(data.message, data.ok ? 'success' : 'error');
            }
        } catch (_) {
            showAlert('Network error', 'error');
        } finally {
            if (btn) btn.disabled = false;
        }
    }

    // Load the table body with a specific row opened in edit mode (no URL change)
    async function openInlineEdit(id) {
        const res = await fetch('/inline-edit/' + id, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        if (data && data.html) {
            const tbody = document.getElementById('users-tbody');
            if (tbody) tbody.innerHTML = data.html;
        }
    }

    // Event delegation
    document.addEventListener('submit', handleAjaxSubmit);

    document.addEventListener('click', function (e) {
        const edit = e.target.closest('.edit-btn');
        if (edit) {
            e.preventDefault();
            openInlineEdit(edit.getAttribute('data-id'));
            return;
        }
        const cancel = e.target.closest('.cancel-edit');
        if (cancel) {
            e.preventDefault();
            openInlineEdit(0);
        }
    });
})();
