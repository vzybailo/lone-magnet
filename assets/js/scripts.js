document.addEventListener('DOMContentLoaded', function () {
    // мгновенный пересчет цены в зависимости от количества

    const regPrice = document.querySelector('.lone-regular-price .woocommerce-Price-amount')
    const salePrice = document.querySelector('.lone-sale-price .woocommerce-Price-amount')
    const savePrice = document.querySelector('.lone-save-summ')
    const quantityInput = document.querySelector('.qty')

    if (!regPrice || !quantityInput || (salePrice && !savePrice)) return

    const currencySymbol = regPrice.querySelector('.woocommerce-Price-currencySymbol')?.textContent || '$'
    const baseRegPrice = parseFloat(regPrice.textContent.replace(/[^0-9.]/g, ''))
    const baseSalePrice = salePrice ? parseFloat(salePrice.textContent.replace(/[^0-9.]/g, '')) : null

    function updatePrice() {
        const quantity = parseInt(quantityInput.value) || 1
        const newRegTotal = (baseRegPrice * quantity).toFixed(2)
    
        regPrice.innerHTML = `<bdi><span class="woocommerce-Price-currencySymbol">${currencySymbol}</span>${newRegTotal}</bdi>`
    
        if (baseSalePrice !== null && salePrice && savePrice) {
            const newSalePrice = (baseSalePrice * quantity).toFixed(2)
            const newSaveSumm = (newRegTotal - newSalePrice).toFixed(0)
    
            salePrice.innerHTML = `<bdi><span class="woocommerce-Price-currencySymbol">${currencySymbol}</span>${newSalePrice}</bdi>`
            savePrice.innerHTML = `<bdi><span class="woocommerce-Price-currencySymbol">You save ${currencySymbol}</span>${newSaveSumm}</bdi>`
        }
    }    

    quantityInput.addEventListener('input', updatePrice)
    quantityInput.addEventListener('change', updatePrice)

    updatePrice() 

    // кнопки + и - в инпуте

    const minus = document.querySelector('#decrease-number')
    const plus = document.querySelector('#increase-number')
    minus.classList.add('not-allowed')

    quantityInput.addEventListener('input', () => {
        if (parseInt(quantityInput.value) <= 0 || quantityInput.value === '0') {
            quantityInput.value = 1
        }
    })    

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

// burger menu

    // const burgerBtn = document.querySelector('#burger-btn')
    // const burgerMenu = document.querySelector('#burger-menu')

    // burgerBtn.addEventListener('click', () => {
    //     burgerMenu.classList.toggle('hidden')
    // })

