export default function getCroppedImg(imageSrc, croppedAreaPixels, onProgress = () => {}) {
  return new Promise((resolve, reject) => {
    const image = new Image();
    image.crossOrigin = "anonymous";
    image.src = imageSrc;

    image.onload = () => {
      const canvas = document.createElement("canvas");
      canvas.width = croppedAreaPixels.width;
      canvas.height = croppedAreaPixels.height;

      const ctx = canvas.getContext("2d");

      let progress = 0;
      const fakeProgressInterval = setInterval(() => {
        progress += 10;
        onProgress(Math.min(progress, 90)); 

        if (progress >= 90) {
          clearInterval(fakeProgressInterval);

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

          canvas.toBlob((blob) => {
            if (!blob) {
              reject(new Error("Canvas is empty"));
              return;
            }

            onProgress(100); 
            resolve(blob);
          }, "image/jpeg", 0.95);
        }
      }, 30); 
    };

    image.onerror = () => reject(new Error("Failed to load image"));
  });
}
