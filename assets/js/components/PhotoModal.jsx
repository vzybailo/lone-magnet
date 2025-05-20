import React, { useState, useCallback } from "react";
import Cropper from "react-easy-crop";
import getCroppedImg from "./utils/getCroppedImg";

export default function PhotoModal({ onClose, onComplete, currentIndex, total }) {
  const [imageSrc, setImageSrc] = useState(null);
  const [crop, setCrop] = useState({ x: 0, y: 0 });
  const [zoom, setZoom] = useState(1);
  const [croppedAreaPixels, setCroppedAreaPixels] = useState(null);

  const onCropComplete = useCallback((_, croppedAreaPixels) => {
    setCroppedAreaPixels(croppedAreaPixels);
  }, []);

  const handleFileChange = async (e) => {
    const file = e.target.files?.[0];
    if (file) {
      const reader = new FileReader();
      reader.addEventListener("load", () => setImageSrc(reader.result));
      reader.readAsDataURL(file);
    }
  };

  const handleSave = async () => {
    const croppedImage = await getCroppedImg(imageSrc, croppedAreaPixels);
    onComplete(croppedImage);
    setImageSrc(null);
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
      <div className="bg-white p-4 rounded max-w-lg w-full relative">
        <button onClick={onClose} className="absolute top-2 right-2 text-black">✕</button>

        <p className="mb-2">Фото {currentIndex} из {total}</p>

        {!imageSrc && (
          <input type="file" accept="image/*" onChange={handleFileChange} />
        )}

        {imageSrc && (
          <>
            <div className="relative w-full h-96 bg-gray-200">
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
            <div className="flex justify-between mt-4">
              <button onClick={onClose} className="px-4 py-2 bg-gray-300 rounded">Отмена</button>
              <button onClick={handleSave} className="px-4 py-2 bg-blue-500 text-white rounded">Сохранить</button>
            </div>
          </>
        )}
      </div>
    </div>
  );
}
