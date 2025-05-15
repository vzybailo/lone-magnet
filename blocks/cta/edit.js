// редакторская часть

import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit({ attributes, setAttributes }) {
  const blockProps = useBlockProps({
    className: 'bg-blue-500 text-white p-6 rounded-xl text-center',
  });

  return (
    <div {...blockProps}>
      <RichText
        tagName="p"
        value={attributes.message}
        onChange={(val) => setAttributes({ message: val })}
        placeholder="Введите сообщение"
      />
    </div>
  );
}

