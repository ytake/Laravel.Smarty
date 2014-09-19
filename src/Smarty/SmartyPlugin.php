<?php
namespace Ytake\LaravelSmarty;

use Smarty;
use Illuminate\Html\FormBuilder;

/**
 * Class SmartyPlugin
 * @package Ytake\LaravelSmarty
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 */
class SmartyPlugin
{

    /** @var FormBuilder  */
    protected $form;

    /** @var Smarty  */
    protected $smarty;

    /**
     * @param FormBuilder $form
     * @param Smarty $smarty
     */
    public function __construct(FormBuilder $form, Smarty $smarty)
    {
        $this->form = $form;
        $this->smarty = $smarty;
        $this->registerPlugin();
    }

    /**
     * @return void
     * @throws \SmartyException
     */
    protected function registerPlugin()
    {
        $this->smarty->registerPlugin("block", "form", [$this, "blockForm"]);
    }

    /**
     * @param $params
     * @param $content
     * @param \Smarty_Internal_Template $smarty
     * @param $repeat
     * @return string
     */
    public function blockForm($params, $content, \Smarty_Internal_Template &$smarty, &$repeat)
    {
        if (is_null($content)) {
            return '';
        }
        return $this->form->open($params) . $content . $this->form->close();
    }
}