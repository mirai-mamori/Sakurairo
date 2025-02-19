/* global wp */

import "./control.scss";

import KirkiSelectControl from './KirkiSelectControl';

// Register control type with Customizer.
wp.customize.controlConstructor['kirki-react-select'] = KirkiSelectControl;
