(() => {
  /**
   * Function to hook into `kirkiPostMessageStylesOutput` filter.
   *
   * @param {string} styles The styles to be filtered.
   * @param {string|Object|int} values The control's value.
   * @param {Object} output The control's output argument.
   * @param {string} controlType The control type.
   *
   * @return {string} The filtered styles.
   */
  const stylesOutput = (styles, values, output, controlType) => {
    if ("kirki-margin" !== controlType && "kirki-padding" !== controlType) {
      return styles;
    }

    if (!values.top && !values.right && !values.bottom && !values.left) {
      return styles;
    }

    const property = controlType.replace("kirki-", "");

    styles += output.element + "{";

    for (const position in values) {
      if (Object.hasOwnProperty.call(values, position)) {
        const value = values[position];

        if ("" !== value) {
          styles += property + "-" + position + ": " + value + ";";
        }
      }
    }

    styles += "}";

    return styles;
  };

  // Hook the function to the `kirkiPostMessageStylesOutput` filter.
  wp.hooks.addFilter("kirkiPostMessageStylesOutput", "kirki", stylesOutput);
})();
