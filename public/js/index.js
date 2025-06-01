console.log('Script loaded');
console.log('Login buttons:', document.querySelectorAll('.login-btn').length);
console.log('Register buttons:', document.querySelectorAll('.register-btn').length);

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded');
    
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
            document.body.classList.add('body-no-scroll');
            console.log('Opened modal:', modalId);
        } else {
            console.error('Modal not found:', modalId);
        }
    }

    function closeModals() {
        document.querySelectorAll('.merka-modal').forEach(modal => {
            modal.style.display = 'none';
        });
        document.body.classList.remove('body-no-scroll');
    }

    function setupModelSelection() {
        const modelOptions = document.querySelectorAll('.model-option');
        const selectedModelInput = document.getElementById('selected-model');
        
        if (!modelOptions.length || !selectedModelInput) {
            console.log('Model selection elements not found');
            return;
        }
        
        modelOptions.forEach(option => {
            option.addEventListener('click', function() {
                modelOptions.forEach(opt => opt.classList.remove('selected'));
                
                this.classList.add('selected');
                
                const model = this.getAttribute('data-model');
                selectedModelInput.value = model;
                
                const radio = this.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
                
                console.log('Selected model:', model);
            });
            
            const radio = option.querySelector('input[type="radio"]');
            if (radio && radio.checked) {
                option.classList.add('selected');
            }
        });
        
        if (!document.querySelector('.model-option.selected') && modelOptions.length > 0) {
            modelOptions[0].classList.add('selected');
            const defaultModel = modelOptions[0].getAttribute('data-model');
            selectedModelInput.value = defaultModel;
            console.log('Default model selected:', defaultModel);
        }
    }

    function init() {
        document.querySelectorAll('.login-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                openModal('loginModal');
            });
        });

        document.querySelectorAll('.register-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                openModal('registerModal');
            });
        });

        document.querySelectorAll('.order-btn[data-auth="guest"]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const productId = this.getAttribute('data-product-id');
                const productIdField = document.getElementById('product_id');
                if (productIdField) {
                    productIdField.value = productId;
                }

                document.getElementById('loginModal').dataset.productId = productId;
                openModal('loginModal');

                console.log('Order button clicked for guest, product:', productId);
            });
        });


        document.querySelectorAll('.merka-close').forEach(button => {
            button.addEventListener('click', closeModals);
        });

        document.querySelectorAll('.merka-modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModals();
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModals();
            }
        });

        document.querySelectorAll('.show-register').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                closeModals();
                openModal('registerModal');
            });
        });
        
        setupModelSelection();
    }

    init();
});