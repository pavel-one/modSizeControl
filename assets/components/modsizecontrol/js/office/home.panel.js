modSizeControl.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'modsizecontrol-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: false,
            hideMode: 'offsets',
            items: [{
                title: _('modsizecontrol_items'),
                layout: 'anchor',
                items: [{
                    html: _('modsizecontrol_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'modsizecontrol-grid-items',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    modSizeControl.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(modSizeControl.panel.Home, MODx.Panel);
Ext.reg('modsizecontrol-panel-home', modSizeControl.panel.Home);
