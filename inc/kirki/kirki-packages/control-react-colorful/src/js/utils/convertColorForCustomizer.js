import { colord } from "colord";

/**
 * Convert the value for the customizer.
 *
 * @param {string|Object} value The value to be converted.
 * @param {string} pickerComponent The picker component name.
 *
 * @returns {string} The converted value.
 */
const convertColorForCustomizer = (value, pickerComponent, formComponent) => {
	let rgba;
	let hsv;
	let hsva;
	let convertedValue;

	switch (pickerComponent) {
		/**
		 * The HexColorPicker is used by these condition:
		 * 1. When formComponent is defined with HexColorPicker as the value.
		 * 2. When formComponent is not defined but the "alpha" choice is not set or set to false (the old way).
		 */
		case "HexColorPicker":
			convertedValue =
				"string" === typeof value && value.includes("#")
					? value
					: colord(value).toHex();
			break;

		case "RgbColorPicker":
			convertedValue = colord(value).toRgb();
			delete convertedValue.a;
			break;

		case "RgbStringColorPicker":
			convertedValue =
				"string" === typeof value && value.includes("rgb(")
					? value
					: colord(value).toRgbString();
			break;

		case "RgbaColorPicker":
			rgba = colord(value).toRgb();
			convertedValue = rgba;
			break;

		/**
		 * The RgbaStringColorPicker is used by these condition:
		 * 1. When formComponent is defined with RgbaColorPicker as the value.
		 * 2. When formComponent is not defined but the "alpha" choice is set to true.
		 */
		case "RgbaStringColorPicker":
			rgba = colord(value).toRgb();

			if (rgba.a < 1) {
				convertedValue =
					"string" === typeof value && value.includes("rgba")
						? value
						: colord(value).toRgbString();
			} else {
				// When it uses the 2nd condition above, then the expected value is "hex".
				if (!formComponent) {
					convertedValue =
						"string" === typeof value && value.includes("#")
							? value
							: colord(value).toHex();
				} else {
					convertedValue = colord(value).toRgbString();

					// Force to set the alpha channel value.
					if (
						convertedValue.includes("rgb") &&
						!convertedValue.includes("rgba")
					) {
						convertedValue = convertedValue.replace("rgb", "rgba");
						convertedValue = convertedValue.replace(")", ", 1)");
					}
				}
			}

			break;

		case "HslColorPicker":
			convertedValue = colord(value).toHsl();
			delete convertedValue.a;
			break;

		case "HslStringColorPicker":
			convertedValue =
				"string" === typeof value && value.includes("hsl(")
					? value
					: colord(value).toHslString();
			break;

		case "HslaColorPicker":
			convertedValue = colord(value).toHsl();
			break;

		case "HslaStringColorPicker":
			convertedValue = colord(value).toHslString();

			// Force to set the alpha channel value.
			if (convertedValue.includes("hsl") && !convertedValue.includes("hsla")) {
				convertedValue = convertedValue.replace("hsl", "hsla");
				convertedValue = convertedValue.replace(")", ", 1)");
			}

			break;

		case "HsvColorPicker":
			convertedValue = colord(value).toHsv();
			delete convertedValue.a;
			break;

		/**
		 * The colord library doesn't provide .toHsvString() method yet.
		 * This manual value-building will apply to "HsvStringColorPicker" and "HsvaStringColorPicker" stuff below.
		 */
		case "HsvStringColorPicker":
			hsv = colord(value).toHsv();
			convertedValue = "hsv(" + hsv.h + ", " + hsv.s + "%, " + hsv.v + "%)";
			break;

		case "HsvaColorPicker":
			convertedValue = colord(value).toHsv();
			break;

		case "HsvaStringColorPicker":
			hsva = colord(value).toHsv();
			convertedValue =
				"hsva(" +
				hsva.h +
				", " +
				hsva.s +
				"%, " +
				hsva.v +
				"%, " +
				hsva.a +
				")";
			break;

		default:
			convertedValue =
				"string" === typeof value && value.includes("#")
					? value
					: colord(value).toHex();
			break;
	}

	return convertedValue;
};

export default convertColorForCustomizer;
