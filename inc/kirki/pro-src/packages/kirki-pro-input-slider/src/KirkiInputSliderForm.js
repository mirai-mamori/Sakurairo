import { useRef } from "react";

const KirkiInputSliderForm = (props) => {
  const { control, customizerSetting, choices } = props;

  let trigger = "";

  const validateValue = (value) => {
    if (value < choices.min) value = choices.min;
    if (value > choices.max) value = choices.max;

    return value;
  };

  const getValueObject = (value) => {
    value = "string" !== typeof value ? value.toString() : value;

    const valueUnit = value.replace(/\d+/g, "");
    let valueNumber = value.replace(valueUnit, "");

    valueNumber = parseFloat(valueNumber.trim());
    valueNumber = validateValue(valueNumber);

    return {
      number: valueNumber,
      unit: valueUnit,
    };
  };

  const getValueForInput = (value) => {
    const valueObject = getValueObject(value);
    return valueObject.number + valueObject.unit;
  };

  const getValueForSlider = (value) => {
    return getValueObject(value).number;
  };

  control.updateComponentState = (val) => {
    if ("slider" === trigger) {
      valueRef.current.value = getValueForInput(val);
    } else if ("input" === trigger) {
      sliderRef.current.value = getValueForSlider(val);
    } else if ("reset" === trigger) {
      valueRef.current.value = val;
      sliderRef.current.value = val;
    }
  };

  const handleInputChange = (e) => {
    trigger = "input";
    customizerSetting.set(getValueForInput(e.target.value));
  };

  const handleSliderChange = (e) => {
    trigger = "slider";

    let value = parseFloat(e.target.value);
    value = validateValue(value);

    const inputValueObj = getValueObject(valueRef.current.value); // We're going to use the unit.
    const valueForInput = value + inputValueObj.unit;

    customizerSetting.set(valueForInput);
  };

  const handleReset = (e) => {
    if ("" !== props.default && "undefined" !== typeof props.default) {
      sliderRef.current.value = props.default;
      valueRef.current.value = props.default;
    } else {
      if ("" !== props.value) {
        sliderRef.current.value = props.value;
        valueRef.current.value = props.value;
      } else {
        sliderRef.current.value = choices.min;
        valueRef.current.value = "";
      }
    }

    trigger = "reset";
    customizerSetting.set(sliderRef.current.value);
  };

  // Preparing for the template.
  const fieldId = `kirki-control-input-${customizerSetting.id}`;
  const sliderValue = getValueForSlider(props.value);
  const inputValue = getValueForInput(props.value);

  const sliderRef = useRef(null);
  const valueRef = useRef(null);

  return (
    <div className="kirki-control-form" tabIndex="1">
      <label className="kirki-control-label" htmlFor={fieldId}>
        <span className="customize-control-title">{props.label}</span>
        <span
          className="customize-control-description description"
          dangerouslySetInnerHTML={{ __html: props.description }}
        />
      </label>

      <div
        className="customize-control-notifications-container"
        ref={props.setNotificationContainer}
      ></div>

      <button
        type="button"
        className="kirki-control-reset"
        onClick={handleReset}
      >
        <i className="dashicons dashicons-image-rotate"></i>
      </button>

      <div className="kirki-control-cols">
        <div className="kirki-control-left-col">
          <input
            ref={sliderRef}
            type="range"
            id={fieldId}
            defaultValue={sliderValue}
            min={choices.min}
            max={choices.max}
            step={choices.step}
            className="kirki-control-input-slider kirki-pro-control-input-slider"
            onChange={handleSliderChange}
          />
        </div>
        <div className="kirki-control-right-col">
          <input
            ref={valueRef}
            type="text"
            defaultValue={inputValue}
            className="kirki-control-input"
            onChange={handleInputChange}
          />
        </div>
      </div>
    </div>
  );
};

export default KirkiInputSliderForm;
