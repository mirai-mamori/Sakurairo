import { getClosest } from "./utils";
import jQuery from "jquery";

declare var ajaxurl: string;

(function () {
	function init() {
		jQuery(document).on(
			"click",
			".kirki-discount-notice.is-dismissible .notice-dismiss",
			dismiss
		);
	}

	function dismiss(e: JQuery.ClickEvent) {
		const notice = getClosest(this, ".kirki-discount-notice");
		if (!notice) return;
		let nonce = notice.dataset.dismissNonce;
		nonce = nonce ? nonce : "";

		jQuery
			.ajax({
				url: ajaxurl,
				type: "post",
				data: {
					action: "kirki_dismiss_discount_notice",
					nonce: nonce,
					dismiss: 1,
				},
			})
			.always(function (r) {
				if (r.success) console.log(r.data);
			});
	}

	init();
})();
