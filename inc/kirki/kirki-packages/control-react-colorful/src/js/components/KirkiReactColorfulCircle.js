import { useState, useEffect } from "react";
import reactCSS from "reactcss";

const KirkiReactColorfulCircle = (props) => {
	const { color = "" } = props;
	const [value, setValue] = useState(() => color);

	// Update the local state when `color` property value is changed.
	useEffect(() => {
		// We don't need to convert the color since it's using the customizer value.
		setValue(color);
	}, [color]);

	const pickersWithAlpha = [
		"RgbaColorPicker",
		"RgbaStringColorPicker",
		"HslaColorPicker",
		"HslaStringColorPicker",
		"HsvaColorPicker",
		"HsvaStringColorPicker",
	];

	const styles = reactCSS({
		default: {
			triggerButton: {
				backgroundImage:
					'url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAAHnlligAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHJJREFUeNpi+P///4EDBxiAGMgCCCAGFB5AADGCRBgYDh48CCRZIJS9vT2QBAggFBkmBiSAogxFBiCAoHogAKIKAlBUYTELAiAmEtABEECk20G6BOmuIl0CIMBQ/IEMkO0myiSSraaaBhZcbkUOs0HuBwDplz5uFJ3Z4gAAAABJRU5ErkJggg==")',
			},
			colorPreview: {
				backgroundColor: value ? value : "transparent",
			},
		},
	});

	return (
		<div className="kirki-trigger-circle-wrapper">
			<button
				type="button"
				className="kirki-trigger-circle"
				onClick={props.togglePickerHandler}
				style={styles.triggerButton}
			>
				<div className="kirki-color-preview" style={styles.colorPreview}></div>
			</button>
		</div>
	);
};

export default KirkiReactColorfulCircle;
