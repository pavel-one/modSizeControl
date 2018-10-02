<?php

class SizeUpdateProcessor extends modProcessor
{
    /** @var modSizeControl $modSizeControl */
    public $modSizeControl;
    public $limit;
    public $sources;
    protected $patches;
    protected $size;

    public function initialize()
    {
        $this->modSizeControl = $this->modx->getService('modSizeControl', 'modSizeControl', MODX_CORE_PATH . 'components/modsizecontrol/model/', array());
        if (!$this->modSizeControl) {
            return $this->modx->lexicon('modsizecontrol_err_class_exist');
        }

        $this->limit = ($this->modSizeControl->config['limit'] * 1024) * 1024;
        $this->sources = explode(',', $this->modx->getOption('modsizecontrol_file_system'));

        if (!$this->sources) {
            return $this->modx->lexicon('modsizecontrol_err_filesystem_not_specified');
        }

        if (in_array(1, $this->sources)) {
            $this->sources = array(1);
        }

        $this->patches = array();
        $this->size = 0;


        return parent::initialize();
    }

    public function process()
    {
        $setPatches = $this->setPatches();
        if ($setPatches !== true) {
            return $this->failure($setPatches);
        }
        $setSize = $this->setSize();
        if ($setSize !== true) {
            return $this->failure($setSize);
        }
        $caching = $this->caching();
        if ($caching !== true) {
            return $this->failure($caching);
        }

        $percent = $this->limit / 100;
        $percent = ceil($this->size / $percent);

        $output = array(
            'percent' => $percent,
            'limit' => $this->modSizeControl->format_size($this->limit),
            'size' => $this->modSizeControl->format_size($this->size),
            'errorHeader' => $this->modx->lexicon('modsizecontrol_limit_out_header'),
            'errorText' => $this->modx->lexicon('modsizecontrol_limit_out_text'),
            'loadText' => $this->modx->lexicon('modsizecontrol_load_text')
        );

        return $this->success($this->modx->lexicon('modsizecontrol_success_update'), $output);

    }

    public function setPatches()
    {
        foreach ($this->sources as $item) {
            /** @var modMediaSource $source */
            $source = $this->modx->getObject('modMediaSource', $item);
            if ($source) {
                $source->initialize();
                $path = $source->getBasePath();
                $this->patches[] = $path;
            }
        }
        if (!$this->patches) {
            return $this->modx->lexicon('modsizecontrol_err_filesystem_not_get');
        }
        return true;
    }

    public function setSize()
    {
        foreach ($this->patches as $patch) {
            $this->size += $this->modSizeControl->dir_size($patch);
        }
        if ($this->size < 1) {
            return $this->modx->lexicon('modsizecontrol_err_directory_size');
        }

        return true;
    }

    public function caching()
    {

        /** @var modSystemSetting $option */
        $option = $this->modx->getObject('modSystemSetting', 'modsizecontrol_site_size');
        if (!$option) {
            return $this->modx->lexicon('modsizecontrol_err_cache_setting');
        }
        $option->set('value', $this->size);
        if (!$option->save()) {
            return $this->modx->lexicon('modsizecontrol_err_save_setting');
        }
        $this->modx->cacheManager->refresh(array('system_settings' => array()));

        return true;
    }
}

return 'SizeUpdateProcessor';