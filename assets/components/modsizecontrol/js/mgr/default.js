document.addEventListener('DOMContentLoaded', function () {
    var queryUpdate = encodeURI('action=size/update');
    var modSizeControl = {
        link: modSizeControlConfig.web_connector + '?' + queryUpdate,
        elements: {
            button: document.getElementById('modsizecontrol-send'),
            size: document.getElementById('modsizecontrol-size'),
            limit: document.getElementById('modsizecontrol-limit'),
            percent: document.getElementById('modsizecontrol-percent'),
            error: document.getElementsByClassName('modsizecontrol-error'),
            chart: document.getElementById("modsizecontrol-circlechart")
        },
        ajax: function () {
            modSizeControl.elements.button.classList.add('x-item-disabled');
            modSizeControl.elements.chart.classList.add('loading');
            modSizeControl.elements.chart.innerHTML = modSizeControl.makesvg(100);
            modSizeControl.elements.percent.innerHTML = modSizeControlConfig.loading_text;
            var mSCRequest = new XMLHttpRequest();
            mSCRequest.open('GET', modSizeControl.link);

            mSCRequest.onreadystatechange = function () {
                if (mSCRequest.readyState === 4) {
                    if (mSCRequest.status === 200) {
                        var data = JSON.parse(this.responseText);
                        if (!data.success) {
                            MODx.msg.alert(modSizeControlConfig.error_text, data.message, function () {}, MODx);
                            modSizeControl.elements.percent.innerHTML = modSizeControlConfig.error_text;

                            setTimeout(function () {
                                modSizeControl.elements.chart.classList.remove('loading');
                                modSizeControl.elements.button.classList.remove('x-item-disabled');
                                modSizeControl.elements.percent.innerHTML = modSizeControl.percent + '%';
                                modSizeControl.elements.chart.innerHTML = modSizeControl.makesvg(modSizeControl.percent);
                            }, 2000);

                            return;
                        }
                        modSizeControl.elements.size.innerHTML = data.object.size;
                        modSizeControl.elements.limit.innerHTML = data.object.limit;
                        modSizeControl.elements.percent.innerHTML = data.object.percent + '%';
                        modSizeControl.percent = data.object.percent;

                        modSizeControl.elements.chart.classList.remove('loading');
                        modSizeControl.elements.chart.innerHTML = modSizeControl.makesvg(data.object.percent);
                        modSizeControl.elements.button.classList.remove('x-item-disabled');

                        if (data.object.percent > 100) MODx.msg.alert(data.object.errorHeader, data.object.errorText, function () {}, MODx); // ! Нужно выяснить: Почему MODx.msg.alert() после Ctrl + F5 недоступен 

                    } else {
                        MODx.msg.alert(modSizeControlConfig.error_text, 'Произошла ошибка при запросе: ' + mSCRequest.status + ' ' + mSCRequest.statusText, function () {}, MODx);
                        MODx.msg.alert(modSizeControlConfig.error_text, data.message, function () {}, MODx);
                        modSizeControl.elements.percent.innerHTML = modSizeControlConfig.error_text;
                    }
                }
            };

            mSCRequest.send(null);
        },
        makesvg: function (percentage) {
            var classes = 'modsizecontrol-success-stroke';

            if (percentage >= 50 && percentage <= 75) {
                classes = 'modsizecontrol-warning-stroke';
            } else if (percentage > 75) {
                classes = 'modsizecontrol-danger-stroke';
            }

            var svg = '<svg class="modsizecontrol-circle-chart" viewbox="0 0 33.83098862 33.83098862" xmlns="http://www.w3.org/2000/svg">' +
                '<circle class="modsizecontrol-circle-chart-background" cx="16.9" cy="16.9" r="15.9" />' +
                '<circle class="modsizecontrol-circle-chart-circle ' + classes + '"' +
                'stroke-dasharray="' + percentage + ',100"    cx="16.9" cy="16.9" r="15.9" />';

            svg += ' </g></svg>';

            return svg;
        }
    };

    modSizeControl['percent'] = modSizeControl.elements.percent.dataset.percentage;
    modSizeControl.elements.button.onclick = modSizeControl.ajax;
    modSizeControl.elements.chart.innerHTML = modSizeControl.makesvg(modSizeControl.elements.chart.dataset.percentage);

    setTimeout(function () {
        modSizeControl.ajax();
    }, 1000);
});