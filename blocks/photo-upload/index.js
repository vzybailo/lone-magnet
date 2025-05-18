import PhotoUpload from './PhotoUpload';
import { createRoot } from 'react-dom/client';
import React from 'react';

document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('custom-photo-upload');
  if (container) {
    const root = createRoot(container);
    root.render(<PhotoUpload />);
  }
});
