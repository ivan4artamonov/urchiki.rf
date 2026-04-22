import './bootstrap';
import 'flowbite';
import sort from '@alpinejs/sort';
import { initFlowbite } from 'flowbite';
import registerTabs from './admin/tabs';

document.addEventListener('alpine:init', () => {
	window.Alpine.plugin(sort);
	registerTabs(window.Alpine);
});

document.addEventListener('DOMContentLoaded', () => {
	initFlowbite();
});

document.addEventListener('livewire:navigated', () => {
	initFlowbite();
});
