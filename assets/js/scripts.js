document.addEventListener('DOMContentLoaded', function () {
    // мгновенный пересчет цены в зависимости от количества

    const priceElement = document.querySelector('.woocommerce-Price-amount')
    const quantityInput = document.querySelector('.qty')

    if (!priceElement || !quantityInput) return

    const currencySymbol = priceElement.querySelector('.woocommerce-Price-currencySymbol')?.textContent || '$'
    const basePrice = parseFloat(priceElement.textContent.replace(/[^0-9.]/g, ''))

    function updatePrice() {
        const quantity = parseInt(quantityInput.value) || 1
        const newTotal = (basePrice * quantity).toFixed(2)

        priceElement.innerHTML = `<bdi><span class="woocommerce-Price-currencySymbol">${currencySymbol}</span>${newTotal}</bdi>`
    }

    quantityInput.addEventListener('input', updatePrice)
    quantityInput.addEventListener('change', updatePrice)

    updatePrice() 

    // кнопки + и - в инпуте

    const minus = document.querySelector('#decrease-number')
    const plus = document.querySelector('#increase-number')
    minus.classList.add('not-allowed')

    if (!quantityInput || !minus || !plus) return

    function updateMinusState() {
        const currentValue = parseInt(quantityInput.value) || 1;
        if (currentValue <= 1) {
            minus.classList.add('not-allowed');
        } else {
            minus.classList.remove('not-allowed');
            minus.style.pointerEvents = 'auto';
        }
    }

    minus.addEventListener('click', () => {
        let currentValue = parseInt(quantityInput.value) || 1
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1
            quantityInput.dispatchEvent(new Event('change'))
        } 
        updateMinusState()
    })

    plus.addEventListener('click', () => {
        let currentValue = parseInt(quantityInput.value) || 1
        quantityInput.value = currentValue + 1
        quantityInput.dispatchEvent(new Event('change'))
        minus.classList.remove('not-allowed')
        updateMinusState()
    })

    updateMinusState()
})

