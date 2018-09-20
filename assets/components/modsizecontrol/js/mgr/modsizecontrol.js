var modSizeControl = function (config) {
    config = config || {};
    modSizeControl.superclass.constructor.call(this, config);
};
Ext.extend(modSizeControl, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('modsizecontrol', modSizeControl);

modSizeControl = new modSizeControl();