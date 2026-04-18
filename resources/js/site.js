import './bootstrap';
import 'virtual:svg-icons-register';
import 'flowbite';
import { initFlowbite } from 'flowbite';

document.addEventListener('DOMContentLoaded', () => {
	initFlowbite();
});

document.addEventListener('livewire:navigated', () => {
	initFlowbite();
});
