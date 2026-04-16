import './bootstrap';
import 'flowbite';
import sort from '@alpinejs/sort';

document.addEventListener('alpine:init', () => {
	window.Alpine.plugin(sort);
});
