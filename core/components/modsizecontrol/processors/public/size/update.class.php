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


        if (!$_SESSION['modSizeUpdate']) {
            $_SESSION['modSizeUpdate'] = time();
        } else {
            $check = $_SESSION['modSizeUpdate'] + 5;
            if (time() < $check) {
                return 'Слишком частый запрос обновления';
            } else {
                $_SESSION['modSizeUpdate'] = time();
            }
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

        if ($this->size = $this->modx->cacheManager->get('modSizeControl')) {
            $percent = $this->limit / 100;
            $percent = ceil($this->size / $percent);
            return $this->success($this->modx->lexicon('modsizecontrol_success_update'), array(
                'percent' => $percent,
                'limit' => $this->modSizeControl->format_size($this->limit),
                'size' => $this->modSizeControl->format_size($this->size),
                'errorHeader' => $this->modx->lexicon('modsizecontrol_limit_out_header'),
                'errorText' => $this->modx->lexicon('modsizecontrol_limit_out_text'),
                'loadText' => $this->modx->lexicon('modsizecontrol_load_text')
            ));
        }

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

        $this->modx->cacheManager->set('modSizeControl', $this->size, 43200); //12 hour

        return true;
    }
}

return 'SizeUpdateProcessor';