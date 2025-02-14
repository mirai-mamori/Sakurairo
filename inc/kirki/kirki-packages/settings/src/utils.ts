export const emptyElement = (el: HTMLElement) => {
	while (el.firstChild) {
		el.removeChild(el.firstChild);
	}
};

export const getClosest = (
	el: HTMLElement,
	selector: string,
	depth?: number
): HTMLElement | undefined => {
	if (el.matches(selector)) {
		return el;
	}

	if (el.tagName === "BODY" || el.tagName === "HTML") {
		return undefined;
	}

	let closest = undefined;
	depth = depth ? depth : 20;

	for (let i = 0; i < depth; i++) {
		const parentNode = el.parentNode as HTMLElement;

		if (
			!parentNode ||
			parentNode.tagName === "BODY" ||
			parentNode.tagName === "HTML"
		) {
			break;
		}

		if (parentNode.matches(selector)) {
			return parentNode;
		}

		el = parentNode;
	}

	return closest;
};

export const startLoading = (button: HTMLButtonElement | HTMLElement) => {
	button.classList.add("is-loading");
};

export const stopLoading = (button: HTMLButtonElement | HTMLElement) => {
	button.classList.remove("is-loading");
};
