export default function registerTabs(Alpine) {
	Alpine.data('tabs', (initialTab = 'default') => ({
		tab: initialTab,

		selectTab(tab) {
			this.tab = tab;
		},

		isTab(tab) {
			return this.tab === tab;
		},

		tabButtonAttrs(tab) {
			return {
				type: 'button',
				['@click']() {
					this.selectTab(tab);
				},
				[':class']() {
					const baseClasses = 'rounded px-3 py-2 text-sm font-medium transition cursor-pointer';

					return this.isTab(tab)
						? `${baseClasses} bg-neutral-secondary-soft text-heading ring-1 ring-slate-400`
						: `${baseClasses} text-body hover:bg-neutral-secondary-soft`;
				},
			};
		},

		tabPanelAttrs(tab, withCloak = false) {
			const attributes = {
				['x-show']() {
					return this.isTab(tab);
				},
			};

			if (withCloak) {
				attributes['x-cloak'] = '';
			}

			return attributes;
		},
	}));
}
