import './bootstrap';
import 'flowbite';
import sort from '@alpinejs/sort';
import { initFlowbite } from 'flowbite';

document.addEventListener('alpine:init', () => {
	window.Alpine.plugin(sort);
});

document.addEventListener('DOMContentLoaded', () => {
	initFlowbite();
});

document.addEventListener('livewire:navigated', () => {
	initFlowbite();
});
