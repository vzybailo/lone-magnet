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
    burgerMenu.style.top = `${headerHeight - window.scrollY}px`

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

  // Функция для обновления корзины с задержкой
  function triggerUpdate() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
      document.querySelector('[name="update_cart"]')?.click();
    }, debounceDelay);
  }

  // Делегируем клики (чтобы работало даже после AJAX WooCommerce)
  document.addEventListener('click', (e) => {
    // Кнопка "+"
    if (e.target.matches('.increase-number')) {
      const container = e.target.closest('.quantity');
      const input = container?.querySelector('.qty');
      if (!input) return;

      const max = parseInt(input.getAttribute('max')) || Infinity;
      let currentQty = parseInt(input.value) || 1;

      if (currentQty < max) {
        input.value = currentQty + 1;
        input.dispatchEvent(new Event('input', { bubbles: true }));
      } else {
        input.value = max; // фиксируем на максимуме
      }
    }

    // Кнопка "–"
    if (e.target.matches('.decrease-number')) {
      const container = e.target.closest('.quantity');
      const input = container?.querySelector('.qty');
      if (!input) return;

      let currentQty = parseInt(input.value) || 1;
      if (currentQty > 1) {
        input.value = currentQty - 1;
        input.dispatchEvent(new Event('input', { bubbles: true }));
      } else {
        input.value = 1;
      }
    }
  });

  // Контроль ручного ввода (не больше max и не меньше 1)
  document.addEventListener('input', (e) => {
    if (!e.target.matches('.qty')) return;

    const input = e.target;
    const max = parseInt(input.getAttribute('max')) || Infinity;
    let value = parseInt(input.value);

    if (isNaN(value) || value < 1) value = 1;
    if (value > max) value = max;

    input.value = value;
    triggerUpdate();
  });

  // Повторная инициализация после AJAX обновления корзины
  if (typeof jQuery !== 'undefined') {
    jQuery(document.body).on('updated_cart_totals', () => {
      console.log('Cart updated — custom quantity script still active');
    });
  }
});

// пересчитывается общая стоимость в корзине при измении количества товаров
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.increase-number, .decrease-number').forEach(btn => {
    btn.addEventListener('click', function () {
      const updateBtn = document.querySelector('button[name="update_cart"]')
      if (updateBtn) {
        updateBtn.disabled = false
        updateBtn.click()
      }
    })
  })

  document.querySelectorAll('input.qty').forEach(input => {
    input.addEventListener('change', function () {
      const updateBtn = document.querySelector('button[name="update_cart"]')
      if (updateBtn) {
        updateBtn.disabled = false
        updateBtn.click()
      }
    })
  })
})

function adjustMainMargin() {
  const header = document.querySelector('.header__main');
  const main = document.querySelector('.home-page');
  if (header && main) {
    main.style.marginTop = `-${header.offsetHeight}px`;
  }
}

window.addEventListener('DOMContentLoaded', adjustMainMargin);
window.addEventListener('resize', adjustMainMargin);

//swiper slider
document.addEventListener("DOMContentLoaded", function () {
  new Swiper(".mySwiper", {
      loop: true,
      speed: 1000, 
      autoplay: {
          delay: 5000,
          disableOnInteraction: false,
      },
      slidesPerView: 1,
      spaceBetween: 26,
      pagination: {
        el: ".swiper-pagination",
        clickable: true, 
      },
      breakpoints: {
          768: {
              slidesPerView: 2,
          },
          1024: {
              slidesPerView: 3,
          },
      },
  });
});

// change price for the bulk order
document.addEventListener('DOMContentLoaded', () => {
  const priceElement = document.querySelector('.lone-regular-price .woocommerce-Price-amount');
  const salePriceElement = document.querySelector('.lone-sale-price .woocommerce-Price-amount');
  const radios = document.querySelectorAll('.bulk-quantity-buttons input[type="radio"]');

  if (!priceElement || radios.length === 0) return;

  const basePrice = parsePrice(priceElement.textContent || '');
  const baseSalePrice = salePriceElement ? parsePrice(salePriceElement.textContent || '') : null;

  function parsePrice(text) {
    const number = text.replace(/[^0-9.,]/g, '').replace(',', '.');
    return parseFloat(number) || 0;
  }

  function formatPrice(price) {
    return '$' + price.toFixed(2);
  }

  function updatePrice(qty) {
    const newPrice = basePrice * qty;
    priceElement.textContent = formatPrice(newPrice);
  }

  function updateSalePrice(qty) {
    if (salePriceElement && baseSalePrice !== null) {
      const newSalePrice = baseSalePrice * qty;
      salePriceElement.textContent = formatPrice(newSalePrice);
    }
  }

  radios.forEach(radio => {
    radio.addEventListener('change', (e) => {
      const qty = parseInt(e.target.value, 10) || 1;
      updatePrice(qty);
      updateSalePrice(qty);
    });

    // Обновляем цену сразу при загрузке, если radio выбран
    if (radio.checked) {
      const qty = parseInt(radio.value, 10) || 1;
      updatePrice(qty);
      updateSalePrice(qty);
    }
  });
});

// block scroll
document.addEventListener('DOMContentLoaded', () => {
  const parent = document.querySelector('#custom-photo-modal-root')
  const addPhotoBtn = document.querySelector('#custom-photo-upload')

  const lock = () => {
    document.documentElement.style.overflow = 'hidden'
    document.body.style.overflow = 'hidden'
  }
  const unlock = () => {
    document.documentElement.style.overflow = ''
    document.body.style.overflow = ''
  }

  addPhotoBtn?.addEventListener('click', () => {
    // ждём пока модалка реально попадёт в DOM
    const wait = () => {
      const modal = parent?.querySelector(':scope > .inset-0') // прямой потомок
      if (modal) {
        lock()
        // когда модалку удалят — вернём скролл
        const mo = new MutationObserver(() => {
          if (!parent.querySelector(':scope > .inset-0')) {
            unlock()
            mo.disconnect()
          }
        })
        mo.observe(parent, { childList: true })
      } else {
        requestAnimationFrame(wait)
      }
    }
    wait()
  })
})

// show more review btn
document.querySelectorAll('.show-more').forEach(btn => {
  const text = btn.previousElementSibling;
  if (text.scrollHeight > text.clientHeight) btn.classList.remove('hidden');
  btn.addEventListener('click', () => {
    text.classList.toggle('line-clamp-4');
    btn.textContent = text.classList.contains('line-clamp-4') ? 'Show more' : 'Show less';
  });
});
