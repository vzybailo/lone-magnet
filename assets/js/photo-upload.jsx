import React, { useState, useEffect } from "react";
import { createRoot } from "react-dom/client";
import { v4 as uuid } from "uuid";
import PhotoModal from "./components/PhotoModal";

const PhotoUploadApp = () => {
  const [quantity, setQuantity] = useState(1);
  const [uploadedPhotos, setUploadedPhotos] = useState([]);
  const [showModal, setShowModal] = useState(false);

  const container = document.getElementById("custom-photo-modal-root");
  const productId = container?.dataset?.productId || "unknown";
  const STORAGE_KEY = `magnet_photos_product_${productId}`;

  const requiredPhotos = quantity * 2;

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
    plusBtn?.addEventListener("click", () => setTimeout(updateQuantity, 0));
    minusBtn?.addEventListener("click", () => setTimeout(updateQuantity, 0));

    updateQuantity();

    return () => {
      input?.removeEventListener("input", updateQuantity);
      input?.removeEventListener("change", updateQuantity);
      plusBtn?.removeEventListener("click", updateQuantity);
      minusBtn?.removeEventListener("click", updateQuantity);
    };
  }, []);

  useEffect(() => {
    const saved = sessionStorage.getItem(STORAGE_KEY);
    if (saved) {
      setUploadedPhotos(JSON.parse(saved));
    }
  }, [STORAGE_KEY]);

  useEffect(() => {
    const addToCartBtn = document.querySelector("#lone-add-to-cart");
    const alertMsg = document.querySelector(".lone-alert");

    const handleClick = (e) => {
      if (uploadedPhotos.length < requiredPhotos) {
        e.preventDefault();
        alertMsg?.classList.remove("success");
        alertMsg?.classList.add("warn");
        alertMsg.innerHTML = `<div class="py-2">⚠️ Almost there! Please upload <b>${requiredPhotos}</b> photos to complete your order. You’ve uploaded <b>${uploadedPhotos.length}</b> so far.</div>`;
      } else {
        sessionStorage.removeItem(STORAGE_KEY);
        setUploadedPhotos([]);
        
        alertMsg?.classList.remove("warn");
        alertMsg?.classList.add("success");
        alertMsg.innerHTML = `<div class="py-2">✅ You have successfully uploaded <b>${requiredPhotos}</b> photo${requiredPhotos > 1 ? "s" : ""}.</div>`;
      }
    };

    addToCartBtn?.addEventListener("click", handleClick);

    return () => {
      addToCartBtn?.removeEventListener("click", handleClick);
    };
  }, [uploadedPhotos, requiredPhotos]);

  useEffect(() => {
    const alertMsg = document.querySelector(".lone-alert");

    if (uploadedPhotos.length === requiredPhotos && alertMsg) {
      alertMsg.classList.remove("warn");
      alertMsg.classList.add("success");
      alertMsg.innerHTML = `<div class="py-2">✅ You have successfully uploaded <b>${requiredPhotos}</b> photo${requiredPhotos > 1 ? "s" : ""}.</div>`;
    }
  }, [uploadedPhotos, requiredPhotos]);

  const handlePhotoComplete = (uploaded) => {
    const newPhoto = { id: uuid(), url: uploaded.url };
    const newPhotos = [...uploadedPhotos, newPhoto];
    setUploadedPhotos(newPhotos);
    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(newPhotos));
  };

  const handleRemovePhoto = (id) => {
    const updated = uploadedPhotos.filter((photo) => photo.id !== id);
    setUploadedPhotos(updated);
    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(updated));
  };

  useEffect(() => {
    const uploadBtn = document.querySelector("#custom-photo-upload");

    const handleUploadClick = (e) => {
      e.preventDefault();
      setShowModal(true);
    };

    uploadBtn?.addEventListener("click", handleUploadClick);

    return () => {
      uploadBtn?.removeEventListener("click", handleUploadClick);
    };
  }, []);

  return (
    <>
      {showModal && uploadedPhotos.length < requiredPhotos && (
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

const container = document.getElementById("custom-photo-modal-root");

if (container) {
  container.innerHTML = ""; // Очистить старое содержимое, если было
  const productId = container.dataset.productId || "unknown";
  const root = createRoot(container);
  root.render(<PhotoUploadApp productId={productId} />);
}

