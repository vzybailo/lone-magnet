import React, { useState, useCallback, useEffect } from 'react';
import { createRoot } from 'react-dom/client';
import Cropper from 'react-easy-crop';

function getCroppedImg(imageSrc, croppedAreaPixels) {
  return new Promise((resolve, reject) => {
    const image = new Image();
    image.src = imageSrc;
    image.onload = () => {
      const canvas = document.createElement('canvas');
      canvas.width = croppedAreaPixels.width;
      canvas.height = croppedAreaPixels.height;
      const ctx = canvas.getContext('2d');

      ctx.drawImage(
        image,
        croppedAreaPixels.x,
        croppedAreaPixels.y,
        croppedAreaPixels.width,
        croppedAreaPixels.height,
        0,
        0,
        croppedAreaPixels.width,
        croppedAreaPixels.height
      );

      canvas.toBlob(blob => {
        if (!blob) {
          reject(new Error('Canvas is empty'));
          return;
        }
        resolve(blob);
      }, 'image/png');
    };
    image.onerror = () => {
      reject(new Error('Image load error'));
    };
  });
}

function CustomUploadModal() {
  const [isOpen, setIsOpen] = useState(false);
  const [imageSrc, setImageSrc] = useState(null);
  const [crop, setCrop] = useState({ x: 0, y: 0 });
  const [zoom, setZoom] = useState(1);
  const [croppedAreaPixels, setCroppedAreaPixels] = useState(null);
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState('');
  const [quantity, setQuantity] = useState(1);
  const [uploadedPhotos, setUploadedPhotos] = useState([]);

  const requiredPhotos = quantity * 9;

  const openModal = () => {
    setIsOpen(true);
    setImageSrc(null);
    setMessage('');
  };
  const closeModal = () => setIsOpen(false);

  const onCropComplete = useCallback((_, croppedPixels) => {
    setCroppedAreaPixels(croppedPixels);
  }, []);

  const onFileChange = (e) => {
    if (e.target.files?.length) {
      const reader = new FileReader();
      reader.onload = () => {
        setImageSrc(reader.result);
        e.target.value = null; // сброс input
      };
      reader.readAsDataURL(e.target.files[0]);
    }
  };

  const uploadCroppedImage = async () => {
    if (!imageSrc || !croppedAreaPixels) return;

    setLoading(true);
    setMessage('');

    try {
      const croppedBlob = await getCroppedImg(imageSrc, croppedAreaPixels);

      const formData = new FormData();
      formData.append('file', croppedBlob, 'cropped-image.png');
      formData.append('order_id', CustomUploadSettings.order_id);

      const res = await fetch(CustomUploadSettings.rest_url, {
        method: 'POST',
        credentials: 'include',
        headers: { 'X-WP-Nonce': CustomUploadSettings.nonce },
        body: formData,
      });

      const data = await res.json();

      if (!res.ok) {
        throw new Error(data.message || 'Unknown error');
      }

      setUploadedPhotos(prev => [...prev, data.url]);
      setMessage('✅ Uploaded successfully!');
      setImageSrc(null);
    } catch (err) {
      setMessage(`❌ Error: ${err.message}`);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    const btn = document.getElementById('custom-photo-upload');
    btn?.addEventListener('click', openModal);
    return () => btn?.removeEventListener('click', openModal);
  }, []);

  useEffect(() => {
    const input = document.querySelector(".mag-quantity");
    const plusBtn = document.querySelector("#increase-number");
    const minusBtn = document.querySelector("#decrease-number");

    const updateQuantity = () => {
      const val = parseInt(input?.value, 10);
      setQuantity(isNaN(val) || val < 1 ? 1 : val);
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
    const btn = document.querySelector("#lone-add-to-cart");
    const alertMsg = document.querySelector(".lone-alert");

    const handleClick = (e) => {
      if (uploadedPhotos.length < requiredPhotos) {
        e.preventDefault();
        alertMsg.classList.add("warn");
        alertMsg.classList.remove("success");
        alertMsg.innerHTML = `⚠️ Please upload <b>${requiredPhotos}</b> photos. You’ve uploaded <b>${uploadedPhotos.length}</b>.`;
      } else {
        alertMsg.classList.remove("warn");
        alertMsg.classList.add("success");
        alertMsg.innerHTML = `✅ Uploaded <b>${uploadedPhotos.length}</b> of <b>${requiredPhotos}</b> required photos.`;
      }
    };

    btn?.addEventListener("click", handleClick);
    return () => btn?.removeEventListener("click", handleClick);
  }, [uploadedPhotos, requiredPhotos]);

  if (!isOpen) return null;

  const modalStyle = {
    position: 'fixed',
    top: 0, left: 0, right: 0, bottom: 0,
    backgroundColor: 'rgba(0,0,0,0.6)',
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    zIndex: 9999
  };

  const containerStyle = {
    background: '#fff',
    padding: 20,
    borderRadius: 8,
    width: 400,
    maxWidth: '90%'
  };

  return (
    <div style={modalStyle}>
      <div style={containerStyle}>
        <h3>Upload and Crop Photo</h3>
        <span>{uploadedPhotos}</span>

        {!imageSrc && (
          <input type="file" accept="image/*" onChange={onFileChange} />
        )}

        {imageSrc && (
          <>
            <div style={{ position: 'relative', width: '100%', height: 300, background: '#333' }}>
              <Cropper
                image={imageSrc}
                crop={crop}
                zoom={zoom}
                aspect={1}
                onCropChange={setCrop}
                onZoomChange={setZoom}
                onCropComplete={onCropComplete}
              />
            </div>
            <div style={{ marginTop: 10 }}>
              <button onClick={uploadCroppedImage} disabled={loading}>
                {loading ? 'Uploading...' : 'Upload Cropped Image'}
              </button>
              <button onClick={() => setImageSrc(null)} disabled={loading} style={{ marginLeft: 10 }}>
                Choose Another
              </button>
            </div>
          </>
        )}

        <button onClick={closeModal} style={{ marginTop: 15 }}>Close</button>
        {message && <p style={{ marginTop: 10 }}>{message}</p>}
      </div>
    </div>
  );
}

document.addEventListener('DOMContentLoaded', () => {
  const modalRoot = document.getElementById('custom-photo-modal-root');
  if (modalRoot) {
    const root = createRoot(modalRoot);
    root.render(<CustomUploadModal />);
  }
});
