// фронтенд-отображение

import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Save({ attributes }) {
  const blockProps = useBlockProps.save({
    className: 'bg-blue-500 text-white p-6 rounded-xl text-center',
  });

  return (
    <div {...blockProps}>
      <RichText.Content tagName="p" value={attributes.message} />
    </div>
  );
}
