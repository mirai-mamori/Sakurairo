import { colord } from "colord";

/**
 * Convert the value for the color picker.
 *
 * @param {string|Object} value The value to be converted.
 * @param {string} pickerComponent The picker component name.
 *
 * @returns {string|Object} The converted value.
 */
const convertColorForPicker = (value, pickerComponent) => {
	let convertedValue;

	switch (pickerComponent) {
		case "HexColorPicker":
			convertedValue = colord(value).toHex();
			break;

		case "RgbColorPicker":
			convertedValue = colord(value).toRgb();
			delete convertedValue.a;
			break;

		case "RgbStringColorPicker":
			convertedValue = colord(value).toRgbString();
			break;

		case "RgbaColorPicker":
			convertedValue = colord(value).toRgb();
			break;

		case "RgbaStringColorPicker":
			convertedValue = colord(value).toRgbString();

			// Force to set the alpha channel value.
			if (convertedValue.includes("rgb") && !convertedValue.includes("rgba")) {
				convertedValue = convertedValue.replace("rgb", "rgba");
				convertedValue = convertedValue.replace(")", ", 1)");
			}

			break;

		case "HslColorPicker":
			convertedValue = colord(value).toHsl();
			delete convertedValue.a;
			break;

		case "HslStringColorPicker":
			convertedValue = colord(value).toHslString();
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

		case "HsvStringColorPicker":
			const hsv = colord(value).toHsv();
			convertedValue = "hsv(" + hsv.h + ", " + hsv.s + "%, " + hsv.v + "%)";

			break;

		case "HsvaColorPicker":
			convertedValue = colord(value).toHsv();
			break;

		case "HsvaStringColorPicker":
			// colord library doesn't provide .toHsvString() method yet.
			const hsva = colord(value).toHsv();
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
			convertedValue = colord(value).toHex();
			break;
	}

	return convertedValue;
};

export default convertColorForPicker;
