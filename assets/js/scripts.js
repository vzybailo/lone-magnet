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



document.addEventListener('DOMContentLoaded', () => {
  const debounceDelay = 500;
  let debounceTimer;

  // Находим все поля количества
  const quantityInputs = document.querySelectorAll('.qty');

  quantityInputs.forEach((input) => {
    input.addEventListener('input', () => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        let qty = parseInt(input.value);
        if (isNaN(qty) || qty < 1) {
          qty = 1;
          input.value = qty;
        }

        // Симуляция клика по кнопке "Обновить корзину"
        document.querySelector('[name="update_cart"]')?.click();
      }, debounceDelay);
    });
  });

  // Обработка кнопок + и -
  document.querySelectorAll('.decrease-number').forEach((btn) => {
    btn.addEventListener('click', () => {
      const container = btn.closest('.quantity');
      const input = container?.querySelector('.qty');
      if (!input) return;
      let currentQty = parseInt(input.value) || 1;
      if (currentQty > 1) {
        input.value = currentQty - 1;
        input.dispatchEvent(new Event('input'));
      }
    });
  });

  document.querySelectorAll('.increase-number').forEach((btn) => {
    btn.addEventListener('click', () => {
      const container = btn.closest('.quantity');
      const input = container?.querySelector('.qty');
      if (!input) return;
      let currentQty = parseInt(input.value) || 1;
      input.value = currentQty + 1;
      input.dispatchEvent(new Event('input'));
    });
  });
});


document.addEventListener('DOMContentLoaded', function () {
  const updateFreeShippingNotice = () => {
    const xhr = new XMLHttpRequest();
    const data = new FormData();
    data.append('action', 'get_free_shipping_message');

    xhr.open('POST', window.wc_cart_params.ajax_url, true);
    xhr.onload = function () {
      if (xhr.status === 200) {
        const target = document.getElementById('free-shipping-message');
        if (target) {
          target.innerHTML = xhr.responseText;
        }
      }
    };
    xhr.send(data);
  };

  document.querySelectorAll('input.qty').forEach(function (input) {
    input.addEventListener('change', function () {
      setTimeout(updateFreeShippingNotice, 1000);
    });
  });
});
