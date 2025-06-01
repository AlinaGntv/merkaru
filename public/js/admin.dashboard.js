function initModalHandlers() {
    document.addEventListener('click', async function(event) {
        if (!event.target.classList.contains('view-order-details')) return;
        
        event.preventDefault();
        
        const orderId = event.target.dataset.orderId;
        const modalElement = document.getElementById('orderDetailsModal');
        
        if (!modalElement) {
            console.error('Modal element not found');
            return;
        }

        try {
            document.getElementById('orderDetailsContent').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                </div>
            `;
            
            document.getElementById('modalOrderId').textContent = orderId;
            
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            
            const response = await fetch(`/admin/orders/${orderId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'include'
            });
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }
            
            const data = await response.text();
            document.getElementById('orderDetailsContent').innerHTML = data;
        } catch (error) {
            console.error('Error loading order details:', error);
            document.getElementById('orderDetailsContent').innerHTML = `
                <div class="alert alert-danger">
                    Ошибка загрузки: ${error.message}
                </div>
            `;
        }
    });
}

if (typeof bootstrap !== 'undefined') {
    initModalHandlers();
} else {
    console.error('Bootstrap not loaded!');
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
    script.onload = initModalHandlers;
    document.head.appendChild(script);
}