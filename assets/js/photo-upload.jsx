import React, { useState, useEffect } from "react";
import { createRoot } from "react-dom/client";
import PhotoModal from "./components/PhotoModal";

const PhotoUploadApp = () => {
  const [quantity, setQuantity] = useState(1);
  const [uploadedPhotos, setUploadedPhotos] = useState([]);
  const [showModal, setShowModal] = useState(false);

  const requiredPhotos = quantity * 9;

  // Обновляем quantity при изменении инпута
  useEffect(() => {
    const input = document.querySelector(".mag-quantity");
    const plusBtn = document.querySelector("#increase-number");
    const minusBtn = document.querySelector("#decrease-number");

    const updateQuantity = () => {
      if (input) {
        const value = parseInt(input.value, 10);
        setQuantity(isNaN(value) || value < 1 ? 1 : value);
      }
    };

    input?.addEventListener("input", updateQuantity);
    input?.addEventListener("change", updateQuantity);

    plusBtn?.addEventListener("click", () => {
      setTimeout(updateQuantity, 0); // Обновим после клика
    });

    minusBtn?.addEventListener("click", () => {
      setTimeout(updateQuantity, 0);
    });

    updateQuantity();

    return () => {
      input?.removeEventListener("input", updateQuantity);
      input?.removeEventListener("change", updateQuantity);
      plusBtn?.removeEventListener("click", updateQuantity);
      minusBtn?.removeEventListener("click", updateQuantity);
    };
  }, []);

  // Обработка кнопки "Добавить в корзину"
  useEffect(() => {
    const addToCartBtn = document.querySelector("#lone-add-to-cart");
    const alertMsg = document.querySelector('.lone-alert')

    const handleClick = (e) => {
      if (uploadedPhotos.length < requiredPhotos) {
        e.preventDefault();
        alertMsg.classList.add('warn')
        alertMsg.innerHTML = `<div class="py-2"> ⚠️ Almost there! Please upload <b>${requiredPhotos}</b> photos to complete your order. You’ve uploaded <b>${uploadedPhotos.length}</b> so far.</div>`
      } else {
        alertMsg.classList.remove('warn')
        alertMsg.classList.add('success')
        alertMsg.innerHTML = `<div class="py-2">✅ You have successfully uploaded <b>${requiredPhotos}</b>. </div>`
      }
    };

    if (addToCartBtn) {
      addToCartBtn.addEventListener("click", handleClick);
    }

    return () => {
      if (addToCartBtn) {
        addToCartBtn.removeEventListener("click", handleClick);
      }
    };
  }, [uploadedPhotos, requiredPhotos]);

  useEffect(() => {
    const alertMsg = document.querySelector('.lone-alert');

    if (
      alertMsg &&
      alertMsg.classList.contains('warn') &&
      uploadedPhotos.length < requiredPhotos
    ) {
      alertMsg.innerHTML = `<div class="py-2"> ⚠️ Almost there! Please upload <b>${requiredPhotos}</b> photos to complete your order. You’ve uploaded <b>${uploadedPhotos.length}</b> so far.</div>`;
    }

    if (
      alertMsg &&
      uploadedPhotos.length >= requiredPhotos &&
      alertMsg.classList.contains('warn')
    ) {
      alertMsg.classList.remove('warn');
      alertMsg.classList.add('success');
      alertMsg.innerHTML = `<div class="py-2">✅ You have successfully uploaded <b>${requiredPhotos}</b> photo${requiredPhotos > 1 ? 's' : ''}.</div>`;
    }
  }, [uploadedPhotos, requiredPhotos]);

  // 🆕 Функция загрузки фото на WordPress и добавления его в uploadedPhotos
  // Этот параметр должен быть именно Blob или File
  const handlePhotoComplete = async (blob) => {
    if (!blob) return;

    // ✅ Обязательно преобразуем Blob в File
    const file = new File([blob], "photo.jpg", {
      type: blob.type || "image/jpeg", // если есть type
    });

    const formData = new FormData();
    formData.append("action", "upload_user_photo");
    formData.append("photo", file);

    try {
      const response = await fetch("/wp-admin/admin-ajax.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        const newPhotos = [...uploadedPhotos, result.data.url];
        setUploadedPhotos(newPhotos);
        sessionStorage.setItem("magnet_photos", JSON.stringify(newPhotos));

        if (newPhotos.length >= requiredPhotos) {
          setShowModal(false);
        }
      } else {
        console.error("Upload error:", result.data.message);
      }
    } catch (error) {
      console.error("Upload failed:", error);
    }
  };


  useEffect(() => {
    const saved = sessionStorage.getItem("magnet_photos");
    if (saved) {
      setUploadedPhotos(JSON.parse(saved));
    }
  }, []);

  // Обработчик кнопки "Загрузить фото"
  useEffect(() => {
    const uploadBtn = document.querySelector("#custom-photo-upload");

    const handleUploadClick = (e) => {
      e.preventDefault();
      setShowModal(true);
    };

    if (uploadBtn) {
      uploadBtn.addEventListener("click", handleUploadClick);
    }

    return () => {
      if (uploadBtn) {
        uploadBtn.removeEventListener("click", handleUploadClick);
      }
    };
  }, []);

  return (
    <>
      {/* 🆕 Модалка появляется при showModal и если не загружено достаточно фото */}
      {showModal && uploadedPhotos.length < requiredPhotos && (
        <PhotoModal
          currentIndex={uploadedPhotos.length + 1}
          total={requiredPhotos}
          onComplete={handlePhotoComplete} // 🆕 новая функция обработки
          onClose={() => setShowModal(false)}
        />
      )}
    </>
  );
};

// Инициализация
const container = document.getElementById("custom-photo-modal-root");
if (container) {
  const root = createRoot(container);
  root.render(<PhotoUploadApp />);
}
