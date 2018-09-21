<?php

class SizeUpdateProcessor extends modProcessor
{
    /** @var modSizeControl $modSizeControl */
    public $modSizeControl;
    /** @var pdoTools $pdo */
    public $pdo;
    public $limit;
    public $sources;
    protected $patches;
    protected $size;

    public function initialize()
    {
        $this->modSizeControl = $this->modx->getService('modSizeControl', 'modSizeControl', MODX_CORE_PATH . 'components/modsizecontrol/model/', array());
        if (!$this->modSizeControl) {
            return 'Не загружен класс';
        }
        $this->pdo = $this->modx->getService('pdoTools');
        if (!$this->pdo) {
            return 'Не загружен pdoTools';
        }

        $this->limit = $this->modSizeControl->config['limit'];
        $this->sources = explode(',', $this->modx->getOption('modsizecontrol_file_system'));

        if (!$this->sources) {
            return 'Не заданы источники файлов';
        }
        $this->patches = array();
        $this->size = array();


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
            'errorHeader' => $this->modx->lexicon('ss_limit_out_header'),
            'errorText' => $this->modx->lexicon('ss_limit_out_text'),
        );

        return $this->success('Обновление завершено успешно', $output);

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
            return 'Не удалось получить источники';
        }
        return true;
    }

    public function setSize()
    {
        foreach ($this->patches as $patch) {
            $this->size += $this->modSizeControl->dir_size($patch);
        }
        if (!$this->size < 1) {
            return 'Проблема с получением размеров директорий';
        }
        return true;
    }

    public function caching()
    {
        /** @var modSystemSetting $option */
        $option = $this->modx->getObject('modSystemSetting', 'modsizecontrol_site_size');
        if (!$option) {
            return 'Не найдена кэш-настройка';
        }
        $option->set('value', $this->size);
        if (!$option->save()) {
            return 'Не удалось сохранить настройку';
        }
        $this->modx->cacheManager->refresh(array('system_settings' => array()));

        return true;
    }
}

return 'SizeUpdateProcessor';