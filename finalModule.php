<?php

if(!defined('_PS_VERSION_'))
{
    exit;
}

class FinalModule extends Module {

    public function __construct()
    {
        $this->name = 'mymodule';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Sweet Johns';
        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => _PS_VERSION_
        ];

        parent::__construct();
        $this->bootstrap = true;
        $this->displayName = $this->l('finalModule');
        $this->description = $this->l('Bottom of the seventh');

    }

    public function install()
    {
        if(!parent::install() ||
        !Configuration::updateValue('POIDS', 11) ||
        !Configuration::updateValue('TAILLE', 180) ||
        !$this->createTable() ||
        !$this->installTab('AdminClient','Mes clients','AdminCatalog'))
        {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        if(!parent::uninstall() ||
        !Configuration::deleteByName('POIDS') ||
        !Configuration::deleteByName('TAILLE') ||
        !$this->deleteTable() ||
        !$this->deleteTab())
        {
            return false;
        }

        return true;
    }

    public function getContent()
    {

        return $this->PostProcess().$this->renderForm();
    }

    public function renderForm()
    {

        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings')
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Modifier le poids'),
                    'name' => 'POIDS',
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Modifier la taille'),
                    'name' => 'TAILLE',
                    'required' => true
                ]
              
                ],
                'submit' => [
                    'title'=> $this->l('save'),
                    'name' => 'save',
                    'class' => 'btn btn-primary'
                ]
                ];

        $helper = new HelperForm();   
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;  
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->fields_value['POIDS'] = Configuration::get('POIDS');
        $helper->fields_value['TAILLE'] = Configuration::get('TAILLE');

        return $helper->generateForm($fieldsForm);

    }



}