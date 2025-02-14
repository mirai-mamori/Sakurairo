import "./control.scss";
import KirkiInputSliderControl from './KirkiInputSliderControl';


// Register control type with Customizer.
wp.customize.controlConstructor['kirki-input-slider'] = KirkiInputSliderControl;
