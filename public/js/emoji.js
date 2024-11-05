import 'emoji-picker-element';

document.addEventListener('DOMContentLoaded', function () {
    const button = document.querySelector('#emoji-picker-button');
    const picker = new EmojiPicker.default();
    
    picker.addEventListener('emoji-click', event => {
        const textarea = document.querySelector('.form-control');
        textarea.value += event.detail.unicode;
    });

    button.addEventListener('click', () => {
        picker.togglePicker(button);
    });
});
