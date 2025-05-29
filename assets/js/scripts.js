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
document.addEventListener('DOMContentLoaded', () => {
  const burgerBtn = document.getElementById('burger-btn')
  const burgerMenu = document.getElementById('burger-menu')
  const lines = burgerBtn.querySelectorAll('.burger-line')
  const header = document.querySelector('.header')
  const adminBar = document.getElementById('wpadminbar')
  const adminBarHeight = adminBar ? adminBar.offsetHeight : 0
  const headerHeight = header.offsetHeight + adminBarHeight

  console.log(headerHeight)

  burgerBtn.addEventListener('click', () => {
    const isOpen = burgerMenu.classList.contains('translate-x-0')
    burgerMenu.style.top = `${headerHeight}px`

    if (isOpen) {
      burgerMenu.classList.remove('translate-x-0')
      burgerMenu.classList.add('-translate-x-full')
      document.body.classList.remove('overflow-hidden')

      lines[0].classList.remove('rotate-45', 'translate-y-1.5')
      lines[1].classList.remove('opacity-0')
      lines[2].classList.remove('-rotate-45', '-translate-y-1.5')
    } else {
      burgerMenu.classList.remove('-translate-x-full')
      burgerMenu.classList.add('translate-x-0')
      document.body.classList.add('overflow-hidden')

      lines[0].classList.add('rotate-45', 'translate-y-1.5')
      lines[1].classList.add('opacity-0')
      lines[2].classList.add('-rotate-45', '-translate-y-1.5')
    }
  })
})

