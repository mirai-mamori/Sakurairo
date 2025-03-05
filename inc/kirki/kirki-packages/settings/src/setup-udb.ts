import { emptyElement, getClosest, startLoading, stopLoading } from "./utils";
import jQuery from "jquery";

declare var wp: any;
declare var kirkiSettings: any;
declare var ajaxurl: any;

export default function setupUdb() {
	const adminPage: HTMLElement | null = document.querySelector(
		".kirki-settings-page"
	);
	if (!adminPage) return;

	const progressBox: HTMLElement | null = adminPage.querySelector(
		".installation-progress-metabox"
	);
	if (!progressBox) return;

	const progressList: HTMLElement | null = progressBox.querySelector(
		".installation-progress-list"
	);
	if (!progressList) return;

	let doingAjax = false;
	const udbData = kirkiSettings.recommendedPlugins.udb;

	document.addEventListener("click", handleDocumentClick);

	function handleDocumentClick(e: Event) {
		const button = getClosest(e.target as HTMLElement, ".kirki-install-udb");
		if (!button) return;

		e.preventDefault();
		prepareUdb(button);
	}

	function prepareUdb(button: HTMLElement) {
		if (!adminPage) return;
		if (doingAjax) return;
		startProcessing(button);
		addProgress("Preparing...", "loading");

		const nonce = adminPage.dataset.setupUdbNonce
			? adminPage.dataset.setupUdbNonce
			: "";

		jQuery
			.ajax({
				url: ajaxurl,
				method: "POST",
				data: {
					action: "kirki_prepare_install_udb",
					nonce: nonce,
				},
			})
			.done(function (response) {
				if (!response.success) {
					modifyPreviousProgress(response.data, "failed");
					stopProcessing(button, "");
					return;
				}

				if (response.data.finished) {
					modifyPreviousProgress(
						"Ultimate Dashboard has already been installed.",
						"done"
					);
					addProgress(response.data.message, "done");
					addProgress("All done! Redirecting...", "loading");
					stopProcessing(button, udbData.redirectUrl);
					return;
				}

				modifyPreviousProgress(response.data.message, "done");
				doingAjax = false;
				installUdb(button);
			})
			.fail(function (jqXHR) {
				let errorMessage: string =
					"Something went wrong. Please try again later.";

				if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
					errorMessage = jqXHR.responseJSON.data;
				}

				modifyPreviousProgress(errorMessage, "failed");
				stopProcessing(button, "");
			});
	}

	function installUdb(button: HTMLElement) {
		if (doingAjax) return;
		doingAjax = true;
		addProgress("Installing Ultimate Dashboard", "loading");

		wp.updates.installPlugin({
			slug: udbData.slug,
			success: function () {
				modifyPreviousProgress(
					"Ultimate Dashboard has been installed successfully",
					"done"
				);
				doingAjax = false;
				activateUdb(button);
			},
			error: function (jqXHR: any) {
				let abort = true;

				if (jqXHR.errorCode && jqXHR.errorMessage) {
					if (jqXHR.errorCode === "folder_exists") {
						modifyPreviousProgress(
							"Ultimate Dashboard has already been installed.",
							"done"
						);

						doingAjax = false;
						abort = false;

						// Since the plugin has already installed since before, let's activate it.
						activateUdb(button);
					} else {
						modifyPreviousProgress(jqXHR.errorMessage, "failed");
					}
				} else {
					if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
						modifyPreviousProgress(jqXHR.responseJSON.data, "failed");
					} else {
						modifyPreviousProgress(
							"Something went wrong. Please try again later.",
							"failed"
						);
					}
				}

				if (abort) stopProcessing(button, "");
			},
		});
	}

	function activateUdb(button: HTMLElement) {
		if (doingAjax) return;
		doingAjax = true;
		addProgress("Activating Ultimate Dashboard", "loading");

		jQuery.ajax({
			async: true,
			type: "GET",
			url: udbData.activationUrl,
			success: function () {
				modifyPreviousProgress(
					"Ultimate Dashboard has been activated successfully.",
					"done"
				);

				addProgress("All done! Redirecting...", "loading");
				stopProcessing(button, udbData.redirectUrl);
			},
			error: function (jqXHR: any) {
				if (jqXHR.errorCode && jqXHR.errorMessage) {
					modifyPreviousProgress(jqXHR.errorMessage, "failed");
				} else {
					if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
						modifyPreviousProgress(jqXHR.responseJSON.data, "failed");
					} else {
						modifyPreviousProgress(
							"Something went wrong. Please try again later.",
							"failed"
						);
					}
				}

				stopProcessing(button, "");
			},
		});
	}

	function addProgress(text: string, status: string) {
		if (!progressList) return;
		const li = document.createElement("li");
		li.className = "installation-progress";

		if (status === "done") {
			li.classList.add("is-done");
		} else if (status === "failed") {
			li.classList.add("is-failed");
		} else {
			li.classList.add("is-loading");
		}

		const iconDiv = document.createElement("div");
		iconDiv.className = "progress-icon";
		li.appendChild(iconDiv);

		const textDiv = document.createElement("div");
		textDiv.className = "progress-text";
		textDiv.innerHTML = text;
		li.appendChild(textDiv);

		progressList.appendChild(li);
	}

	function modifyPreviousProgress(text: string, status: string) {
		if (!progressList) return;
		const li = progressList.querySelector(".installation-progress:last-child");
		if (!li) return;

		if (status === "done") {
			li.classList.remove("is-loading");
			li.classList.add("is-done");
		} else if (status === "failed") {
			li.classList.remove("is-loading");
			li.classList.add("is-failed");
		} else {
			li.classList.remove("is-done");
			li.classList.remove("is-failed");
			li.classList.add("is-loading");
		}

		if (text) {
			const textDiv = li.querySelector(".progress-text");
			if (!textDiv) return;
			textDiv.innerHTML = text;
		}
	}

	function disableOrEnableOtherButtons(
		actionType: string,
		currentButton: HTMLElement | HTMLButtonElement
	) {
		const buttons = document.querySelectorAll(".kirki-install-udb");
		if (!buttons.length) return;

		buttons.forEach((button) => {
			if (actionType === "disable") {
				if (currentButton && button === currentButton) return;
			}

			if (button.tagName.toLowerCase() === "button") {
				if (actionType === "disable") {
					// button.setAttribute("disabled", "disabled");
					button.classList.add("is-loading");
				} else {
					// button.removeAttribute("disabled");
					button.classList.remove("is-loading");
				}
			} else {
				if (actionType === "disable") {
					// button.classList.add("is-disabled");
					button.classList.add("is-loading");
				} else {
					// button.classList.remove("is-disabled");
					button.classList.remove("is-loading");
				}
			}
		});
	}

	function startProcessing(button: HTMLElement) {
		if (progressBox && progressList) {
			emptyElement(progressList);
			progressBox.classList.remove("is-hidden");
		}

		doingAjax = true;
		disableOrEnableOtherButtons("disable", button);
		startLoading(button);
	}

	function stopProcessing(button: HTMLElement, redirectUrl: string) {
		if (redirectUrl) {
			window.setTimeout(() => {
				window.location.replace(redirectUrl);
			}, 1000);
		}

		stopLoading(button);
		doingAjax = false;
		disableOrEnableOtherButtons("enable", button);
	}
}
