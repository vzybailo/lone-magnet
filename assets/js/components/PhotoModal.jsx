import React, { useState, useCallback, useEffect, useRef } from "react";
import Cropper from "react-easy-crop";
import getCroppedImg from "./utils/getCroppedImg";

export default function PhotoModal({ onClose, onComplete }) {
  const [images, setImages] = useState([]);
  const [currentIndex, setCurrentIndex] = useState(0);
  const [crop, setCrop] = useState({ x: 0, y: 0 });
  const [zoom, setZoom] = useState(1);
  const [croppedAreaPixels, setCroppedAreaPixels] = useState(null);
  const [isSaving, setIsSaving] = useState(false);
  const [cropProgress, setCropProgress] = useState(0);
  const fileInputRef = useRef(null);

  const onCropComplete = useCallback((_, areaPixels) => {
    setCroppedAreaPixels(areaPixels);
  }, []);

  const handleFileChange = async (e) => {
    const files = Array.from(e.target.files || []);
    const imagePromises = files.map(file => {
      return new Promise(resolve => {
        const reader = new FileReader();
        reader.onload = () => resolve({ src: reader.result, file });
        reader.readAsDataURL(file);
      });
    });
    const loadedImages = await Promise.all(imagePromises);
    setImages(loadedImages);
    setCurrentIndex(0);
    setCrop({ x: 0, y: 0 });
    setZoom(1);
  };

  const handleSave = async () => {
    setIsSaving(true);
    setCropProgress(0);

    try {
      const { src } = images[currentIndex];
      const croppedImage = await getCroppedImg(src, croppedAreaPixels, setCropProgress);

      const file = new File([croppedImage], `photo-${currentIndex + 1}.jpg`, {
        type: "image/jpeg",
      });

      const formData = new FormData();
      formData.append("file", file);
      formData.append("title", `Photo ${currentIndex + 1}`);
      formData.append("alt_text", `Uploaded photo ${currentIndex + 1}`);

      const response = await fetch("/wp-json/wp/v2/media", {
        method: "POST",
        headers: {
          "X-WP-Nonce": window.wpApiSettings?.nonce || "",
        },
        body: formData,
      });

      if (!response.ok) throw new Error("Upload error");

      const data = await response.json();
      onComplete({
        id: data.id,
        url: data.source_url,
        title: data.title.rendered,
      });

      if (currentIndex < images.length - 1) {
        setCurrentIndex(currentIndex + 1);
        setCrop({ x: 0, y: 0 });
        setZoom(1);
        setCropProgress(0);
      } else {
        onClose();
      }
    } catch (err) {
      console.error(err);
      alert("Ошибка при загрузке. Попробуйте снова.");
    } finally {
      setIsSaving(false);
    }
  };

  const handleDrop = (e) => {
    e.preventDefault();
    const files = e.dataTransfer.files;
    handleFileChange({ target: { files } });
  };

  useEffect(() => {
    const handleKey = (e) => {
      if (e.key === "Escape") onClose();
    };
    window.addEventListener("keydown", handleKey);
    return () => window.removeEventListener("keydown", handleKey);
  }, [onClose]);

  const currentImage = images[currentIndex]?.src;

  return (
    <div className="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
      <div
        className="bg-white p-4 max-w-lg w-full relative"
        onDrop={handleDrop}
        onDragOver={(e) => e.preventDefault()}
      >
        <button onClick={onClose} className="absolute top-2 right-2">✕</button>

        <h3 className="font-bold text-center text-xl">Upload your photos</h3>
        <p className="text-center font-light mb-2">JPG, JPEG or PNG</p>

        {images.length === 0 ? (
          <div
            className="border-dashed border-2 border-gray-300 px-4 py-12 text-center text-sm cursor-pointer"
            onClick={() => fileInputRef.current?.click()}
          >
            Drag and drop images or click to select.
            <input
              type="file"
              accept="image/*"
              multiple
              onChange={handleFileChange}
              ref={fileInputRef}
              className="hidden"
            />
          </div>
        ) : (
          <>
            <p className="text-center mb-4">
              Photo {currentIndex + 1} of {images.length}
            </p>
            <div className="relative w-full h-64 bg-gray-100">
              <Cropper
                image={currentImage}
                crop={crop}
                zoom={zoom}
                aspect={1}
                onCropChange={setCrop}
                onZoomChange={setZoom}
                onCropComplete={onCropComplete}
              />
            </div>
            <div class="flex items-center mt-2">
              <span class="text-sm mr-2">zoom</span>
              <input
                type="range"
                min={1}
                max={3}
                step={0.1}
                value={zoom}
                onChange={(e) => setZoom(e.target.value)}
                className="w-full h-1 appearance-none bg-gray-300 rounded-sm outline-none transition-all duration-200
                          [&::-webkit-slider-thumb]:appearance-none
                          [&::-webkit-slider-thumb]:w-3
                          [&::-webkit-slider-thumb]:h-3
                          [&::-webkit-slider-thumb]:bg-grey
                          [&::-webkit-slider-thumb]:rounded-full
                          [&::-webkit-slider-thumb]:cursor-pointer
                          [&::-moz-range-thumb]:appearance-none
                          [&::-moz-range-thumb]:w-3
                          [&::-moz-range-thumb]:h-3
                          [&::-moz-range-thumb]:bg-grey
                          [&::-moz-range-thumb]:rounded-full
                          [&::-moz-range-thumb]:cursor-pointer"
              />

            </div>

            <div className="flex justify-between mt-4">
              <button
                onClick={() => setCurrentIndex(Math.max(0, currentIndex - 1))}
                disabled={currentIndex === 0 || isSaving}
                className="bg-gray-300 px-4 py-2"
              >
                Previous
              </button>
              <button
                onClick={handleSave}
                className="bg-blue text-white px-4 py-2 flex items-center justify-center gap-2"
                disabled={isSaving}
              >
                {isSaving ? (
                  <>
                    <svg
                      className="animate-spin h-5 w-5 text-white"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                    >
                      <circle
                        className="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        strokeWidth="4"
                      ></circle>
                      <path
                        className="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                      ></path>
                    </svg>
                    Saving...
                  </>
                ) : currentIndex === images.length - 1 ? "Finish" : "Save & Next"}
              </button>

            </div>
          </>
        )}
      </div>
    </div>
  );
}
