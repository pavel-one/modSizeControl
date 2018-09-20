document.addEventListener('DOMContentLoaded', function(){
    var elements = {
        button: document.getElementById('ss_send'),
        size: document.getElementById('ss_size'),
        limit: document.getElementById('ss_limit'),
        percent: document.getElementById('ss_percent'),
        error: document.getElementsByClassName('ss_error'),
        chart: document.getElementById("circlechart")
    }

    var sitesize = {
        link: '../assets/components/modsizecontrol/action.php?action=get',
        ajax: function() {
            elements.button.classList.add('x-item-disabled');
            elements.chart.classList.add('loading');
            elements.chart.innerHTML = sitesize.makesvg(100);
            elements.percent.innerHTML = 'Загрузка';
            var ss_request = new XMLHttpRequest();
            ss_request.open('GET', sitesize.link);

            ss_request.onreadystatechange = function() {
                if(ss_request.readyState === 4) {
                    if(ss_request.status === 200) {
                        var data = JSON.parse(this.responseText);
                        elements.size.innerHTML = data.size;
                        elements.limit.innerHTML = data.limit;
                        elements.percent.innerHTML = data.percent + '%';

                        if (data.percent > 100) {
                            MODx.msg.alert(data.errorHeader,data.errorText,function() {},MODx);
                        }

                        elements.chart.classList.remove('loading');
                        elements.chart.innerHTML = sitesize.makesvg(data.percent);
                        elements.button.classList.remove('x-item-disabled');
                    } else {
                        ss_wrapper.innerHTML = 'Произошла ошибка при запросе: ' +  ss_request.status + ' ' + ss_request.statusText;
                    }
                }
            }

            ss_request.send(null);
        },
        makesvg: function(percentage) {
            var abs_percentage = Math.abs(percentage).toString();
            var classes = "";

            if(percentage >= 50 && percentage <= 75){
                classes = "warning-stroke";
            } else if(percentage > 75){
                classes = "danger-stroke";
            } else{
                classes = "success-stroke";
            }

            var svg = '<svg class="circle-chart" viewbox="0 0 33.83098862 33.83098862" xmlns="http://www.w3.org/2000/svg">'
                + '<circle class="circle-chart__background" cx="16.9" cy="16.9" r="15.9" />'
                + '<circle class="circle-chart__circle '+classes+'"'
                + 'stroke-dasharray="'+ abs_percentage+',100"    cx="16.9" cy="16.9" r="15.9" />';

            svg += ' </g></svg>';

            return svg;
        }
    }

    if(elements.button) {
        elements.button.onclick = sitesize.ajax;
    }

    if(elements.chart) {
        elements.chart.innerHTML = sitesize.makesvg(elements.chart.dataset.percentage);
    }
});