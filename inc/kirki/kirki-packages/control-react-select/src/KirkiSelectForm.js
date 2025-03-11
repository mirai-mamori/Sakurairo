/* globals _, wp, React */

import Select from "react-select";
import { components } from "react-select";

const KirkiSelectMenu = (props) => {
  const { selectProps } = props;
  const optionSelectedLength = props.getValue().length || 0;

  return (
    <components.Menu {...props}>
      {optionSelectedLength < selectProps.maxSelectionNumber ? (
        props.children
      ) : (
        <div style={{ padding: 15 }}>
          {selectProps.messages.maxLimitReached}
        </div>
      )}
    </components.Menu>
  );
};

const KirkiSelectForm = (props) => {
  /**
   * Pass-on the value to the customizer object to save.
   *
   * @param {Object} val - The selected option.
   */
  const handleChangeComplete = (val, type) => {
    let newValue;

    if ("clear" === type) {
      newValue = "";
    } else {
      if (Array.isArray(val)) {
        newValue = val.map((item) => item.value);
      } else {
        newValue = val.value;
      }
    }

    wp.customize(props.customizerSetting.id).set(newValue);
  };

  /**
   * Change the color-scheme using WordPress colors.
   *
   * @param {Object} theme
   */
  const theme = (theme) => ({
    ...theme,
    colors: {
      ...theme.colors,
      primary: "#0073aa",
      primary75: "#33b3db",
      primary50: "#99d9ed",
      primary24: "#e5f5fa",
    },
  });

  const customStyles = {
    control: (base, state) => ({
      ...base,
      minHeight: "30px",
    }),
    valueContainer: (base) => ({
      ...base,
      padding: "0 6px",
    }),
    input: (base) => ({
      ...base,
      margin: "0px",
    }),
  };

  /**
   * Allow rendering HTML in select labels.
   *
   * @param {Object} props - Object { label: foo, value: bar }.
   */
  const getLabel = (props) => {
    return <div dangerouslySetInnerHTML={{ __html: props.label }}></div>;
  };

  const inputId = props.inputId
    ? props.inputId
    : "kirki-react-select-input--" + props.customizerSetting.id;
  const label = props.label ? (
    <label
      className="customize-control-title"
      dangerouslySetInnerHTML={{ __html: props.label }}
      htmlFor={inputId}
    />
  ) : (
    ""
  );
  const description = props.description ? (
    <span
      className="description customize-control-description"
      dangerouslySetInnerHTML={{ __html: props.description }}
    />
  ) : (
    ""
  );

  return (
    <div>
      {label}
      {description}
      <div
        className="customize-control-notifications-container"
        ref={props.setNotificationContainer}
      ></div>
      <Select
        {...props}
        inputId={inputId}
        className="kirki-react-select-container"
        classNamePrefix="kirki-react-select"
        inputClassName="kirki-react-select-input"
        openMenuOnFocus={props.openMenuOnFocus} // @see https://github.com/JedWatson/react-select/issues/888#issuecomment-209376601
        formatOptionLabel={getLabel}
        options={props.control.getFormattedOptions()}
        onChange={handleChangeComplete}
        value={props.control.getOptionProps(props.value)}
        isOptionDisabled={props.isOptionDisabled}
        components={{ IndicatorSeparator: () => null, Menu: KirkiSelectMenu }}
        theme={theme}
        styles={customStyles}
      />
    </div>
  );
};

export default KirkiSelectForm;
