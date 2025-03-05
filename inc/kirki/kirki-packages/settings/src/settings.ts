import "./settings.scss";
import setupUdb from "./setup-udb";
import setupTabsNavigation from "./tabs";

declare var ajaxurl: string;

(function () {
	setupTabsNavigation();
	setupUdb();

	const metabox = document.querySelector(".kirki-clear-font-cache-metabox");
	if (!metabox) return;

	var notice = metabox.querySelector(".submission-status");
	if (!notice) return;

	const button = metabox.querySelector(".kirki-clear-font-cache");
	if (!button) return;

	button.addEventListener("click", clearFontCache);

	let doingAjax = false;
	let timeoutId: number = 0;

	function clearFontCache(e: Event) {
		if (doingAjax) return;
		doingAjax = true;

		const button = this as HTMLButtonElement;
		button.classList.add("is-loading");

		if (timeoutId) {
			window.clearTimeout(timeoutId);
		}

		timeoutId = 0;

		var data = {
			action: "kirki_clear_font_cache",
			nonce: button.dataset.nonce,
		};

		jQuery
			.ajax({
				url: ajaxurl,
				type: "POST",
				data: data,
			})
			.done(function (r) {
				showNotice(r.success ? "success" : "error", r.data);
			})
			.fail(function (r) {
				showNotice("error", "Something went wrong.");
			})
			.always(function (r) {
				doingAjax = false;
				button.classList.remove("is-loading");

				timeoutId = window.setTimeout(function () {
					hideNotice();
				}, 4000);
			});
	}

	function showNotice(status: string, textContent: string) {
		if (!notice) return;
		notice.textContent = textContent;
		notice.classList.add(status === "success" ? "is-success" : "is-error");
		notice.classList.remove("is-hidden");
	}

	function hideNotice() {
		if (!notice) return;
		notice.textContent = "";
		notice.classList.remove("is-success");
		notice.classList.remove("is-error");
		notice.classList.add("is-hidden");
	}
})();
