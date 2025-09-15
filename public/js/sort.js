(function () {
    function applyNameSort(dir) {
        console.log(dir);
        fetch('/users-tbody?sort=' + encodeURIComponent(dir), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.json())
            .then(data => {
                if (data && data.html) {
                    const tbody = document.getElementById('users-tbody');
                    if (tbody) {
                        tbody.innerHTML = data.html;
                        tbody.setAttribute('data-sort', dir);
                    }
                }
                if (data && data.message && typeof showAlert === 'function') {
                    showAlert(data.message, data.ok ? 'success' : 'error');
                }
            })
            .catch(() => {
                if (typeof showAlert === 'function') showAlert('Could not sort table.', 'error');
            });
    }

    document.addEventListener('change', function (e) {
        const select = e.target.closest('#sort-by-name');
        if (!select) return;
        applyNameSort(select.value || 'asc');
    });

})();
