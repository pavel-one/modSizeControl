<div id="modsizecontrol-body">
    <div id="modsizecontrol-wrapper">
        <div class="modsizecontrol-column">
            <div class="modsizecontrol-item">
                <span class="modsizecontrol-text-sm">{'modsizecontrol_total' | lexicon}</span>
                <span id="modsizecontrol-size" class="modsizecontrol-text modsizecontrol-text-lg">{$size}</span>
            </div>
            <div class="modsizecontrol-item">
                <span class="modsizecontrol-text-sm">{'modsizecontrol_available' | lexicon}</span>
                <span id="modsizecontrol-limit" class="modsizecontrol-text modsizecontrol-text-muted">{$limit}</span>
            </div>
            <div class="modsizecontrol-item">
                <button id="modsizecontrol-send" class="x-btn x-btn-text primary-button">
                    <i class="icon icon-refresh"></i>&nbsp;
                    {'modsizecontrol_refresh' | lexicon}
                </button>
            </div>
        </div>
        <div class="modsizecontrol-column" style="position: relative;">
            <div id="modsizecontrol-circlechart" class="modsizecontrol-circlechart" data-percentage="{$percent}">
            </div>
            <span id="modsizecontrol-percent" class="modsizecontrol-info">{$percent}%</span>
        </div>
    </div>
</div>