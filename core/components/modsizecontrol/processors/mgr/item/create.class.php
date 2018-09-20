<?php

class modSizeControlItemCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'modSizeControlItem';
    public $classKey = 'modSizeControlItem';
    public $languageTopics = ['modsizecontrol'];
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('modsizecontrol_item_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name])) {
            $this->modx->error->addField('name', $this->modx->lexicon('modsizecontrol_item_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'modSizeControlItemCreateProcessor';