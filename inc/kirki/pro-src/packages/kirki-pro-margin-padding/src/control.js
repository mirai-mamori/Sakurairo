import "./control.scss";
import KirkiMarginPaddingControl from "./KirkiMarginPaddingControl";

// Register control type with Customizer.
wp.customize.controlConstructor["kirki-margin"] = KirkiMarginPaddingControl;
wp.customize.controlConstructor["kirki-padding"] = KirkiMarginPaddingControl;
