modSizeControl.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'modsizecontrol-panel-home',
            renderTo: 'modsizecontrol-panel-home-div'
        }]
    });
    modSizeControl.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(modSizeControl.page.Home, MODx.Component);
Ext.reg('modsizecontrol-page-home', modSizeControl.page.Home);