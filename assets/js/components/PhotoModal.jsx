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
    if (file) {
      if (!file.type.startsWith("image/")) {
        alert("Можно загружать только изображения.");
        return;
      }

      const reader = new FileReader();
      reader.addEventListener("load", () => {
        setImageSrc(reader.result);
        setCrop({ x: 0, y: 0 });
        setZoom(1);
        setCroppedAreaPixels(null);
      });
      reader.readAsDataURL(file);
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

      if (!response.ok) {
        throw new Error("Ошибка при загрузке изображения в WordPress");
      }

      const data = await response.json();

      onComplete({
        id: data.id,
        url: data.source_url,
        title: data.title.rendered,
      });

      setImageSrc(null);
    } catch (err) {
      console.error("Upload error:", err);
      alert("Ошибка загрузки изображения. Попробуйте снова.");
    } finally {
      setIsSaving(false);
    }
  };

  const handleDrop = (e) => {
    e.preventDefault();
    const file = e.dataTransfer.files?.[0];
    if (file) {
      const event = { target: { files: [file] } };
      handleFileChange(event);
    }
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
        <button onClick={onClose} className="absolute top-2 right-2 text-black">✕</button>

        <h3 className="font-bold text-center text-xl">Upload your files</h3>
        <p className="text-center font-light">Files can be JPG, JPEG or PNG</p>

        <p className="mb-2 text-center">Photo {currentIndex} from {total}</p>

        {!imageSrc && (
          <div className="border-dashed border-2 border-gray-300 p-4 text-center">
            <p className="mb-2">Drag & Drop file here</p>
            <div className="mb-4">or</div> 
            <label className="bg-teal-500 text-white hover:bg-teal-600 w-full p-2 cursor-pointer" htmlFor="upload-photo">Browse for file </label>
            <input
              type="file"
              accept="image/*"
              onChange={handleFileChange}
              ref={fileInputRef}
              className="mx-auto hidden"
              id="upload-photo"
            />
          </div>
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
              <button
                onClick={onClose}
                disabled={isSaving}
                className="px-4 py-2 bg-gray-300"
              >
                Cancel
              </button>
              <button
                onClick={handleSave}
                disabled={isSaving}
                className={`px-4 py-2 text-white ${isSaving ? "bg-blue-300" : "bg-blue-500"}`}
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
