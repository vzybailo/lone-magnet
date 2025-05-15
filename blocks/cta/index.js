// регистрация блока

import './editor.css';
import './style.css';

import Edit from './edit';
import Save from './save';

wp.blocks.registerBlockType('lone-magnet/cta', {
  title: 'CTA Блок',
  icon: 'megaphone',
  category: 'design',
  attributes: {
    message: {
      type: 'string',
      default: 'Привет из CTA блока!',
    },
  },
  edit: Edit,
  save: Save,
});

