import { useRef, useState } from "react";

const KirkiMarginPaddingForm = (props) => {
  const { control, customizerSetting, defaultArray, valueArray, valueUnit } =
    props;

  const [inputValues, setInputValues] = useState(() => {
    return valueArray;
  });

  const getSingleValueAsObject = (value) => {
    let unit = "";
    let number = "";
    let negative = "";

    if ("" !== value) {
      value = "string" !== typeof value ? value.toString() : value;
      value = value.trim();
      negative = -1 < value.indexOf("-") ? "-" : "";
      value = value.replace(negative, "");

      if ("" !== value) {
        unit = value.replace(/\d+/g, "");
        number = value.replace(unit, "");
        number = negative + number.trim();
        number = parseFloat(number);
      } else {
        number = negative;
      }
    }

    return {
      unit: unit,
      number: number,
    };
  };

  const getValuesForInput = (values) => {
    let singleValue;

    for (const position in values) {
      if (Object.hasOwnProperty.call(values, position)) {
        singleValue = getSingleValueAsObject(values[position]);
        values[position] = singleValue.number;
      }
    }

    return values;
  };

  const getValuesForCustomizer = (values) => {
    let singleValue;

    for (const position in values) {
      if (Object.hasOwnProperty.call(values, position)) {
        singleValue = values[position];

        if ("" !== singleValue) {
          singleValue = getSingleValueAsObject(singleValue);
          singleValue = singleValue.number + valueUnit;
        }

        values[position] = singleValue;
      }
    }

    return values;
  };

  control.updateComponentState = (val) => {
    setInputValues(getValuesForInput(val));
  };

  const handleChange = (e, position) => {
    let values = { ...inputValues };
    values[position] = e.target.value;

    customizerSetting.set(getValuesForCustomizer(values));
  };

  const handleReset = (e) => {
    const values =
      "" !== props.default && "undefined" !== typeof props.default
        ? defaultArray
        : valueArray;

    customizerSetting.set(getValuesForCustomizer(values));
  };

  // Preparing for the template.
  const fieldId = `kirki-control-input-${props.type}-top`;
  const unitRef = useRef(null);

  const makeMapable = () => {
    const items = [];

    for (const position in inputValues) {
      if (Object.hasOwnProperty.call(inputValues, position)) {
        items.push({ position: position, value: inputValues[position] });
      }
    }

    return items;
  };

  return (
    <div className="kirki-control-form" tabIndex="1">
      {(props.label || props.description) && (
        <>
          <label className="kirki-control-label" htmlFor={fieldId}>
            {props.label && (
              <span className="customize-control-title">{props.label}</span>
            )}

            {props.description && (
              <span
                className="customize-control-description description"
                dangerouslySetInnerHTML={{ __html: props.description }}
              />
            )}
          </label>

          <div
            className="customize-control-notifications-container"
            ref={props.setNotificationContainer}
          />
        </>
      )}

      <button
        type="button"
        className="kirki-control-reset"
        onClick={handleReset}
      >
        <i className="dashicons dashicons-image-rotate"></i>
      </button>

      <div className="kirki-control-cols">
        <div className="kirki-control-left-col">
          <div class="kirki-control-fields">
            {makeMapable(inputValues).map((item) => {
              const className = `kirki-control-input kirki-control-input-${item.position}`;
              const id = `kirki-control-input-${props.type}-${item.position}`;

              return (
                <div class="kirki-control-field">
                  <input
                    id={id}
                    type="number"
                    value={item.value || 0 === item.value ? item.value : ""}
                    className={className}
                    onChange={(e) => handleChange(e, item.position)}
                  />
                  <label class="kirki-control-sublabel" htmlFor={id}>
                    {item.position}
                  </label>
                </div>
              );
            })}
          </div>
        </div>
        <div className="kirki-control-right-col">
          <span ref={unitRef} className="kirki-control-unit">
            {valueUnit}
          </span>
        </div>
      </div>
    </div>
  );
};

export default KirkiMarginPaddingForm;
