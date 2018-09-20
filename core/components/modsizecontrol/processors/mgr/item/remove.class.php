<?php

class modSizeControlItemRemoveProcessor extends modObjectProcessor
{
    public $objectType = 'modSizeControlItem';
    public $classKey = 'modSizeControlItem';
    public $languageTopics = ['modsizecontrol'];
    //public $permission = 'remove';


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('modsizecontrol_item_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var modSizeControlItem $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('modsizecontrol_item_err_nf'));
            }

            $object->remove();
        }

        return $this->success();
    }

}

return 'modSizeControlItemRemoveProcessor';