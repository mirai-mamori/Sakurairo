import jQuery from "jquery";

export default function setupTabsNavigation() {
	jQuery(".heatbox-tab-nav-item").on("click", function () {
		jQuery(".heatbox-tab-nav-item").removeClass("active");
		jQuery(this).addClass("active");

		const link = this.querySelector("a");
		if (!link) return;

		if (link.href.indexOf("#") === -1) return;

		const hashValue = link.href.substring(link.href.indexOf("#") + 1);

		jQuery(".heatbox-panel-wrapper .heatbox-admin-panel").css(
			"display",
			"none"
		);

		jQuery(".heatbox-panel-wrapper .kirki-" + hashValue + "-panel").css(
			"display",
			"block"
		);
	});

	window.addEventListener("load", function () {
		let hashValue = window.location.hash.substring(1);
		let currentActiveTabMenu: HTMLElement | null = null;

		if (!hashValue) {
			currentActiveTabMenu = document.querySelector(
				".heatbox-tab-nav-item.active"
			);

			if (currentActiveTabMenu && currentActiveTabMenu.dataset.tab) {
				hashValue = currentActiveTabMenu.dataset.tab;
			}

			hashValue = hashValue ? hashValue : "settings";
		}

		jQuery(".heatbox-tab-nav-item").removeClass("active");
		jQuery(".heatbox-tab-nav-item.kirki-" + hashValue + "-panel").addClass(
			"active"
		);

		jQuery(".heatbox-panel-wrapper .heatbox-admin-panel").css(
			"display",
			"none"
		);

		jQuery(".heatbox-panel-wrapper .kirki-" + hashValue + "-panel").css(
			"display",
			"block"
		);
	});
}
