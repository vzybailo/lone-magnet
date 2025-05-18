import React, { useState, useCallback } from 'react';
import Cropper from 'react-easy-crop';
import { Button, Modal, Box, Slider } from '@mui/material';

const PhotoUpload = () => {
  const [open, setOpen] = useState(false);
  const [image, setImage] = useState(null);
  const [crop, setCrop] = useState({ x: 0, y: 0 });
  const [zoom, setZoom] = useState(1);
  const [croppedAreaPixels, setCroppedAreaPixels] = useState(null);

  const onCropComplete = useCallback((_, croppedAreaPixels) => {
    setCroppedAreaPixels(croppedAreaPixels);
  }, []);

  const onFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setImage(URL.createObjectURL(file));
      setOpen(true);
    }
  };

  const handleClose = () => {
    setOpen(false);
  };

  return (
    <>
      <input type="file" accept="image/*" id="real-photo-input" style={{ display: 'none' }} onChange={onFileChange} />
      <div id="custom-photo-upload" onClick={() => document.getElementById('real-photo-input').click()}>
        Загрузить фото
      </div>

      <Modal open={open} onClose={handleClose}>
        <Box sx={{ position: 'absolute', top: '50%', left: '50%', transform: 'translate(-50%, -50%)', width: 400, bgcolor: 'white', boxShadow: 24, p: 2 }}>
          {image && (
            <>
              <div style={{ position: 'relative', width: '100%', height: 300 }}>
                <Cropper
                  image={image}
                  crop={crop}
                  zoom={zoom}
                  aspect={1}
                  onCropChange={setCrop}
                  onZoomChange={setZoom}
                  onCropComplete={onCropComplete}
                />
              </div>
              <Slider min={1} max={3} step={0.1} value={zoom} onChange={(e, value) => setZoom(value)} />
              <Button onClick={handleClose}>Готово</Button>
            </>
          )}
        </Box>
      </Modal>
    </>
  );
};

export default PhotoUpload;
