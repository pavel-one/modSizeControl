<div id="ss_body">
    <div id="ss_wrapper">
        <div class="ss_column">
            <div class="ss_item">
                <span class="ss_small-text ss_text-bold">{'ss_all' | lexicon}</span>
                <span id="ss_size" class="ss_text ss_text-bold">{$size}</span>
            </div>
            <div class="ss_item">
                <span class="ss_small-text ss_text-bold">{'ss_available' | lexicon}</span>
                <span id="ss_limit" class="ss_text ss_text-muted">{$limit}</span>
            </div>
            <div class="ss_item">
                <button id="ss_send" class="x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon">
                    {'ss_refresh' | lexicon}
                </button>
            </div>
        </div>
        <div class="ss_column" style="position: relative;">
            <div id="circlechart" class="circlechart" data-percentage="{$percent}">
            </div>
            <span id="ss_percent" class="ss_info">{$percent}%</span>
        </div>
    </div>
</div>