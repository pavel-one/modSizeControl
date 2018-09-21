<div id="modsizecontrol-body">
    <div id="modsizecontrol-wrapper">
        <div class="modsizecontrol-column">
            <div class="modsizecontrol-item">
                <span class="modsizecontrol-small-text">{'ss_all' | lexicon}</span>
                <span id="modsizecontrol-size" class="modsizecontrol-text">{$size}</span>
            </div>
            <div class="modsizecontrol-item">
                <span class="modsizecontrol-small-text">{'ss_available' | lexicon}</span>
                <span id="modsizecontrol-limit" class="modsizecontrol-text modsizecontrol-text-muted">{$limit}</span>
            </div>
            <div class="modsizecontrol-item">
                <button id="modsizecontrol-send" class="x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon">
                    {'ss_refresh' | lexicon}
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