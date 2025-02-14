import "./control.scss";

(function ($) {
  const setupTabs = () => {
    const childControls = document.querySelectorAll(
      "[data-kirki-parent-tab-id]"
    );
    if (!childControls.length) return;

    let tabIds = [];

    [].slice.call(childControls).forEach(function (childControl) {
      const parentTabId = childControl.dataset.kirkiParentTabId;

      if (!tabIds.includes(parentTabId)) {
        tabIds.push(parentTabId);
      }
    });

    const switchTabs = (tabId, tabItemName) => {
      $('[data-kirki-tab-id="' + tabId + '"] .kirki-tab-menu-item').removeClass(
        "is-active"
      );

      const tabMenuItem = document.querySelector(
        '[data-kirki-tab-id="' +
          tabId +
          '"] [data-kirki-tab-menu-id="' +
          tabItemName +
          '"]'
      );

      if (tabMenuItem) tabMenuItem.classList.add("is-active");

      const tabItems = document.querySelectorAll(
        '[data-kirki-parent-tab-id="' + tabId + '"]'
      );

      [].slice.call(tabItems).forEach(function (tabItem) {
        if (tabItem.dataset.kirkiParentTabItem === tabItemName) {
          tabItem.classList.remove("kirki-tab-item-hidden");
        } else {
          tabItem.classList.add("kirki-tab-item-hidden");
        }
      });
    };

    const setupTabClicks = () => {
      $(document).on("click", ".kirki-tab-menu-item a", function (e) {
        e.preventDefault();

        const tabId = this.parentNode.parentNode.parentNode.dataset.kirkiTabId;
        const tabItemName = this.parentNode.dataset.kirkiTabMenuId;

        switchTabs(tabId, tabItemName);
      });
    };

    const setupBindings = () => {
      tabIds.forEach(function (tabId) {
        wp.customize.section(tabId, function (section) {
          section.expanded.bind(function (isExpanded) {
            if (isExpanded) {
              const activeTabMenu = document.querySelector(
                '[data-kirki-tab-id="' +
                  tabId +
                  '"] .kirki-tab-menu-item.is-active'
              );

              if (activeTabMenu) {
                switchTabs(tabId, activeTabMenu.dataset.kirkiTabMenuId);
              }
            }
          });
        });
      });
    };

    setupTabClicks();
    setupBindings();
  };

  wp.customize.bind("ready", function () {
    setupTabs();
  });
})(jQuery);
