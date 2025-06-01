document.addEventListener('DOMContentLoaded', function() {
    const steps = ['#step-model', '#step-settings', '#step-size', '#step-address', '#step-submit'];
    let currentStep = 0;

    const showStep = (id) => {
        steps.forEach(step => document.querySelector(step)?.classList.add('d-none'));
        document.querySelector(id)?.classList.remove('d-none');
        
        currentStep = steps.indexOf(id);
        
        if (id === '#step-submit') {
            updateOrderSummary();
            updatePaymentMethod();
            updateHiddenFormFields();
        }
    };

    document.querySelectorAll('.color-circle').forEach(circle => {
        circle.addEventListener('click', function() {
            document.querySelectorAll('.color-circle').forEach(c => {
                c.setAttribute('aria-checked', 'false');
                c.style.border = 'none';
            });
            
            this.setAttribute('aria-checked', 'true');
            this.style.border = '2px solid #3498db';
            
            document.getElementById('color').value = this.dataset.color;
        });
    });

    document.querySelector('.color-circle').click();

    function updateOrderSummary() {
        const summary = document.getElementById('order-details');
        if (!summary) return;
        
        const model = document.querySelector('input[name="model"]:checked')?.value || 'Не выбрано';
        const color = document.getElementById('color')?.value || '#ffffff';
        const material = document.getElementById('material')?.value || 'Не выбрано';
        const address = document.getElementById('address')?.value || 'Не указан';
        const comment = document.getElementById('order-comment')?.value || 'Нет комментария';

        const sizes = [
            { label: "Рост", id: "height" },
                { label: "Грудь", id: "chest" },
                { label: "Талия", id: "waist" },
                { label: "Бёдра", id: "hips" },
                { label: "Ширина плеч", id: "shoulder_width" },
                { label: "Рукав", id: "sleeve_length" },
                { label: "Шея", id: "neck_circumference" },
                { label: "Запястье", id: "wrist_circumference" },
                { label: "Бедро", id: "thigh_circumference" },
                { label: "Колено", id: "knee_circumference" },
                { label: "Длина штанины", id: "inseam_length" }
        ];

        const sizesHTML = sizes.map(({ label, id }) => {
            const value = document.getElementById(id)?.value;
            return value ? `<li>${label}: ${value} см</li>` : '';
        }).join('');

        summary.innerHTML = `
            <p><strong>Модель:</strong> ${getModelName(model)}</p>
            <p><strong>Цвет:</strong> <span class="color-badge" style="background-color: ${color}"></span></p>
            <p><strong>Материал:</strong> ${getMaterialName(material)}</p>
            <p><strong>Адрес доставки:</strong> ${address}</p>
            <p><strong>Размеры:</strong></p>
            <ul>${sizesHTML}</ul>
        `;

        summary.innerHTML += `<p><strong>Комментарий:</strong> ${comment || '—'}</p>`;

        document.getElementById('order-total').textContent = calculateTotal();
    }


    function updateHiddenFormFields() {
        document.getElementById('order-model-type').value = document.querySelector('input[name="model"]:checked')?.value;
        document.getElementById('order-color').value = document.getElementById('color')?.value;
        document.getElementById('order-material').value = document.getElementById('material')?.value;
        document.getElementById('order-comment-field').value = document.getElementById('order-comment')?.value;
    }

    function getModelName(key) {
        const models = {
            'tshirt': 'Футболка',
            'hoodie': 'Толстовка',
            'dress': 'Платье',
            'suit': 'Костюм'
        };
        return models[key] || key;
    }

    function getMaterialName(key) {
        const materials = {
            'cotton': 'Хлопок',
            'polyester': 'Полиэстер',
            'wool': 'Шерсть'
        };
        return materials[key] || key;
    }

    function calculateTotal() {
        const modelType = document.querySelector('input[name="model"]:checked')?.value; 
        const material = document.getElementById('material')?.value;

        const prices = {
            'tshirt': 3500,
            'hoodie': 7000,
            'dress': 5000,
            'suit': 10000
        };

        const materialModifiers = {
            'cotton': 1.0,
            'polyester': 0.9,
            'wool': 1.2
        };

        if (!modelType || !material) {
            return 0; 
        }

        const basePrice = prices[modelType];
        const modifier = materialModifiers[material];

        const totalPrice = Math.round(basePrice * modifier);

        return totalPrice;
    }



    function updatePaymentMethod() {
        const method = document.querySelector('input[name="payment_method"]:checked')?.value;
        if (method) {
            document.getElementById('final-payment-method').value = method;
        }
    }

    document.querySelectorAll('input[name="payment_method"]').forEach(method => {
        method.addEventListener('change', function() {
            document.getElementById('card-input').classList.toggle('d-none', this.value !== 'card');
            updatePaymentMethod();
        });
    });

    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function() {
            const nextId = this.dataset.next;
            if (nextId) showStep(nextId);
        });
    });

    document.getElementById('order-form')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'Обработка...';
        
        try {
            const formData = new FormData();
            
            formData.append('model_type', document.querySelector('input[name="model"]:checked')?.value);
            formData.append('color', document.getElementById('color').value); 
            formData.append('material', document.getElementById('material').value);
            formData.append('address', document.getElementById('address').value?.trim());
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('comment', document.getElementById('order-comment')?.value || '');
            
            const sizeFields = ['height', 'chest', 'waist', 'hips', 'shoulder_width', 
                            'sleeve_length', 'neck_circumference', 'wrist_circumference',
                            'thigh_circumference', 'knee_circumference', 'inseam_length'];
            
            sizeFields.forEach(field => {
                const value = document.getElementById(field)?.value;
                if (value) formData.append(field, value);
            });
            
            const items = [{
                product_id: 1, 
                quantity: 1,   
                price: calculateTotal() 
            }];
            formData.append('items', JSON.stringify(items));
            
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showToast(data.message || 'Заказ успешно отправлен', 'success');
                setTimeout(() => {
                    window.location.href = '/dashboard?order_success=true';
                }, 2000);
            } else {
                throw new Error(data.message || 'Ошибка при оформлении заказа');
            }

        } catch (error) {
            console.error('Ошибка:', error);
            showToast(error.message || 'Произошла ошибка при отправке заказа', 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'Подтвердить и оплатить';
        }
    });

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    showStep('#step-model');
});

document.querySelectorAll('.order-card').forEach(card => {
    card.addEventListener('click', () => {
        const orderId = card.getAttribute('data-id');
        const details = document.getElementById('orderDetails' + orderId);
        if (!details) return;

        // Переключаем показ
        if (details.classList.contains('show')) {
            details.classList.remove('show');
            details.style.display = 'none';
        } else {
            details.classList.add('show');
            details.style.display = 'block';
        }
    });
});
