Ext.onReady(function () {
    modSizeControl.config.connector_url = OfficeConfig.actionUrl;

    var grid = new modSizeControl.panel.Home();
    grid.render('office-modsizecontrol-wrapper');

    var preloader = document.getElementById('office-preloader');
    if (preloader) {
        preloader.parentNode.removeChild(preloader);
    }
});