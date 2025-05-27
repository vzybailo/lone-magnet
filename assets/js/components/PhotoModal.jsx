import React, { useState, useCallback, useEffect, useRef } from "react";
import Cropper from "react-easy-crop";
import getCroppedImg from "./utils/getCroppedImg";

export default function PhotoModal({ onClose, onComplete, currentIndex, total }) {
  const [imageSrc, setImageSrc] = useState(null);
  const [crop, setCrop] = useState({ x: 0, y: 0 });
  const [zoom, setZoom] = useState(1);
  const [croppedAreaPixels, setCroppedAreaPixels] = useState(null);
  const [isSaving, setIsSaving] = useState(false);
  const fileInputRef = useRef(null);

  const onCropComplete = useCallback((_, croppedAreaPixels) => {
    setCroppedAreaPixels(croppedAreaPixels);
  }, []);

  const handleFileChange = async (e) => {
    const file = e.target.files?.[0];
    if (file && file.type.startsWith("image/")) {
      const reader = new FileReader();
      reader.onload = () => {
        setImageSrc(reader.result);
        setCrop({ x: 0, y: 0 });
        setZoom(1);
      };
      reader.readAsDataURL(file);
    } else {
      alert("Можно загружать только изображения.");
    }
  };

  const handleSave = async () => {
    setIsSaving(true);
    try {
      const croppedImage = await getCroppedImg(imageSrc, croppedAreaPixels);
      const file = new File([croppedImage], `photo-${currentIndex}.jpg`, {
        type: "image/jpeg",
      });

      const formData = new FormData();
      formData.append("file", file);
      formData.append("title", `Photo ${currentIndex}`);
      formData.append("alt_text", `Uploaded photo ${currentIndex}`);

      const response = await fetch("/wp-json/wp/v2/media", {
        method: "POST",
        headers: {
          "X-WP-Nonce": window.wpApiSettings?.nonce || "",
        },
        body: formData,
      });

      if (!response.ok) throw new Error("Ошибка загрузки");

      const data = await response.json();

      onComplete({
        id: data.id,
        url: data.source_url,
        title: data.title.rendered,
      });

      setImageSrc(null);
    } catch (err) {
      console.error(err);
      alert("Ошибка при загрузке изображения. Попробуйте снова.");
    } finally {
      setIsSaving(false);
    }
  };

  const handleDrop = (e) => {
    e.preventDefault();
    const file = e.dataTransfer.files?.[0];
    if (file) handleFileChange({ target: { files: [file] } });
  };

  useEffect(() => {
    const handleKey = (e) => {
      if (e.key === "Escape") onClose();
    };
    window.addEventListener("keydown", handleKey);
    return () => window.removeEventListener("keydown", handleKey);
  }, [onClose]);

  return (
    <div className="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
      <div
        className="bg-white p-4 max-w-lg w-full relative"
        onDrop={handleDrop}
        onDragOver={(e) => e.preventDefault()}
      >
        <button onClick={onClose} className="absolute top-2 right-2">✕</button>

        <h3 className="font-bold text-center text-xl">Upload your photo</h3>
        <p className="text-center font-light mb-2">JPG, JPEG или PNG</p>
        <p className="text-center mb-4">Photo {currentIndex} from {total}</p>

        {!imageSrc ? (
          <div
            className="border-dashed border-2 border-gray-300 p-4 text-center text-sm cursor-pointer"
            onClick={() => fileInputRef.current?.click()}
          >
            Drag and drop an image or click to select one.
            <input
              type="file"
              accept="image/*"
              onChange={handleFileChange}
              ref={fileInputRef}
              className="hidden"
            />
          </div>
        ) : (
          <>
            <div className="relative w-full h-64 bg-gray-100">
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
            <div className="flex justify-between items-center mt-4">
              <input
                type="range"
                min={1}
                max={3}
                step={0.1}
                value={zoom}
                onChange={(e) => setZoom(e.target.value)}
                className="w-full"
              />
            </div>
            <div className="flex justify-between mt-4">
              <button
                onClick={() => setImageSrc(null)}
                className="bg-gray-200 px-4 py-2"
              >
                Cancel
              </button>
              <button
                onClick={handleSave}
                className="bg-blue-600 text-white px-4 py-2"
                disabled={isSaving}
              >
                {isSaving ? "Saving..." : "Save"}
              </button>
            </div>
          </>
        )}
      </div>
    </div>
  );
}
