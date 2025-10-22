import React, { useState, useEffect, useMemo } from "react";
import { createRoot } from "react-dom/client";
import { v4 as uuid } from "uuid";
import PhotoModal from "./components/PhotoModal";

const PhotoUploadApp = () => {
  const [quantity, setQuantity] = useState(1); // для custom6 — это число магнитов
  const [uploadedPhotos, setUploadedPhotos] = useState([]);
  const [showModal, setShowModal] = useState(false);
  const [showMsg, setShowMsg] = useState(false);

  const container = document.getElementById("custom-photo-modal-root");
  const productId = container?.dataset?.productId || "unknown";
  const mode = container?.dataset?.mode || "default"; 
  // сколько фото требуется на 1 магнит (для custom6)
  const photosPerMagnet = parseInt(container?.dataset?.photosPerMagnet || "1", 10);

  const STORAGE_KEY = `magnet_photos_product_${productId}_${mode}`;

  // вычисляем requiredPhotos в зависимости от режима
  const requiredPhotos = useMemo(() => {
    if (mode === "bulk") return 1;
    if (mode === "custom6") return Math.max(1, quantity) * Math.max(1, photosPerMagnet);
    // default — старое поведение (9 фото на 1 "item")
    return Math.max(1, quantity) * 9;
  }, [mode, quantity, photosPerMagnet]);

  // Инициализация: слушаем обычный инпут qty и кнопки +/-
  useEffect(() => {
    const input = document.querySelector(".mag-quantity");
    const plusBtn = document.querySelector("#increase-number");
    const minusBtn = document.querySelector("#decrease-number");

    const updateQuantityFromInput = () => {
      if (input) {
        const value = parseInt(input.value, 10);
        setQuantity(isNaN(value) || value < 1 ? 1 : value);
      }
    };

    input?.addEventListener("input", updateQuantityFromInput);
    input?.addEventListener("change", updateQuantityFromInput);
    plusBtn?.addEventListener("click", () => setTimeout(updateQuantityFromInput, 0));
    minusBtn?.addEventListener("click", () => setTimeout(updateQuantityFromInput, 0));

    updateQuantityFromInput();

    return () => {
      input?.removeEventListener("input", updateQuantityFromInput);
      input?.removeEventListener("change", updateQuantityFromInput);
      plusBtn?.removeEventListener("click", updateQuantityFromInput);
      minusBtn?.removeEventListener("click", updateQuantityFromInput);
    };
  }, []);

  // Восстановление сохранённых фото
  useEffect(() => {
    const saved = sessionStorage.getItem(STORAGE_KEY);
    if (saved) {
      setUploadedPhotos(JSON.parse(saved));
    }
  }, [STORAGE_KEY]);

  // Обработчик добавления товара в корзину — оставил вашу логику, но проверяем requiredPhotos
  useEffect(() => {
    const addToCartBtn = document.querySelector("#lone-add-to-cart");
    const alertMsg = document.querySelector(".lone-alert");

    const handleClick = (e) => {
      const hiddenInput = document.querySelector("#magnet_photos_data");
      if (hiddenInput) hiddenInput.value = JSON.stringify(uploadedPhotos);

      setShowMsg(true);

      if (uploadedPhotos.length !== requiredPhotos) {
        e.preventDefault(); // блокируем добавление
      } else {
        // очистка после успешного добавления
        setUploadedPhotos([]);
        sessionStorage.removeItem(STORAGE_KEY);
        setShowMsg(false);
      }

      // показываем сообщение, если нет фото
      if (showMsg && uploadedPhotos.length === 0 && alertMsg) {
        alertMsg.innerHTML = `<div class="py-2 warn">
          ⚠️ You haven’t uploaded any photos yet. Please upload <b>${requiredPhotos}</b> photo${requiredPhotos > 1 ? "s" : ""} to complete your order.
        </div>`;
      }
    };

    addToCartBtn?.addEventListener("click", handleClick);
    return () => addToCartBtn?.removeEventListener("click", handleClick);
  }, [uploadedPhotos, requiredPhotos, showMsg, STORAGE_KEY]);

  // Обновление ленты предупреждений/успеха
  useEffect(() => {
    const alertMsg = document.querySelector(".lone-alert");
    if (!alertMsg || !showMsg) return;

    const addClass = (type) => {
      alertMsg.classList.remove("warn", "success");
      alertMsg.classList.add(type);
    };

    const remaining = requiredPhotos - uploadedPhotos.length;

    if (uploadedPhotos.length === requiredPhotos) {
      addClass("success");
      alertMsg.innerHTML = `<div class="py-2">
        ✅ Great! You’ve uploaded all <b>${requiredPhotos}</b> required photo${requiredPhotos > 1 ? "s" : ""}. You’re good to go!
      </div>`;
    } else if (uploadedPhotos.length < requiredPhotos) {
      addClass("warn");
      alertMsg.innerHTML = `<div class="py-2">
        ⚠️ You’ve uploaded <b>${uploadedPhotos.length}</b> out of <b>${requiredPhotos}</b> photo${requiredPhotos > 1 ? "s" : ""}.
        Please upload <b>${remaining}</b> more photo${remaining > 1 ? "s" : ""} to complete your order.
      </div>`;
    } else {
      const maxItems = Math.floor(uploadedPhotos.length / (photosPerMagnet || 1));
      const extra = uploadedPhotos.length - requiredPhotos;
      addClass("warn");
      alertMsg.innerHTML = `<div class="py-2">
        ⚠️ You’ve uploaded <b>${uploadedPhotos.length}</b> photo${uploadedPhotos.length > 1 ? "s" : ""}, but only <b>${requiredPhotos}</b> are needed for <b>${quantity}</b> item${quantity > 1 ? "s" : ""}.<br/>
        You can either remove the extra <b>${extra}</b> photo${extra > 1 ? "s" : ""}, or update your order to <b>${maxItems}</b> item${maxItems > 1 ? "s" : ""}.
      </div>`;
    }
  }, [uploadedPhotos, requiredPhotos, quantity, photosPerMagnet, showMsg]);

  // Когда пользователь завершил аплоад в модале
  const handlePhotoComplete = (uploaded) => {
    const newPhoto = { id: uuid(), url: uploaded.url };
    const newPhotos = [...uploadedPhotos, newPhoto];
    setUploadedPhotos(newPhotos);
    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(newPhotos));
    setShowMsg(true);
  };

  const handleRemovePhoto = (id) => {
    const updated = uploadedPhotos.filter((photo) => photo.id !== id);
    setUploadedPhotos(updated);
    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(updated));
    setShowMsg(true);
    sessionStorage.setItem("showMsg", "true");
  };

  // Обработчик клика по кнопке "Add photos"
  useEffect(() => {
    const uploadBtn = document.querySelector("#custom-photo-upload");

    const handleUploadClick = (e) => {
      e.preventDefault();
      setShowMsg(true);
      setShowModal(true);
    };

    uploadBtn?.addEventListener("click", handleUploadClick);
    return () => uploadBtn?.removeEventListener("click", handleUploadClick);
  }, []);

  //#photo-count для custom6 
  useEffect(() => {
    if (mode !== "custom6") return;

    const photoCountInput = document.getElementById("photo-count");
    const hiddenQuantityInput = document.getElementById("custom6-quantity-hidden");
    const themeQtyInput = document.querySelector('input.qty');

    const applyQuantity = (value) => {
      const v = Math.min(6, Math.max(1, parseInt(value || 1, 10)));
      setQuantity(v);

      if (hiddenQuantityInput) hiddenQuantityInput.value = v;

      if (themeQtyInput) {
        themeQtyInput.value = v;
        themeQtyInput.dispatchEvent(new Event('change', { bubbles: true }));
      }
    };

    if (photoCountInput) {
      const onInput = () => applyQuantity(photoCountInput.value);
      photoCountInput.addEventListener("input", onInput);
      photoCountInput.addEventListener("change", onInput);

      applyQuantity(photoCountInput.value);
      return () => {
        photoCountInput.removeEventListener("input", onInput);
        photoCountInput.removeEventListener("change", onInput);
      };
    }
  }, [mode]);

  return (
    <>
      {showModal && (
        <PhotoModal
          currentIndex={uploadedPhotos.length + 1}
          total={requiredPhotos}
          onComplete={handlePhotoComplete}
          onClose={() => setShowModal(false)}
        />
      )}

      {uploadedPhotos.length > 0 && (
        <div className="mt-4">
          <h3 className="mb-2 text-sm font-light">Uploaded Photos</h3>
          <div className="grid grid-cols-3 gap-4">
            {uploadedPhotos.map((photo) => (
              <div key={photo.id} className="relative">
                <img
                  src={photo.url}
                  alt="Uploaded"
                  className="w-full h-auto border"
                />
                <button
                  onClick={() => handleRemovePhoto(photo.id)}
                  className="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-700"
                >
                  ✕
                </button>
              </div>
            ))}
          </div>
        </div>
      )}
    </>
  );
};

// монтирование на странице (оставил как у тебя)
const mountContainer = document.getElementById("custom-photo-modal-root");
if (mountContainer) {
  mountContainer.innerHTML = "";
  const root = createRoot(mountContainer);
  root.render(<PhotoUploadApp />);
}
