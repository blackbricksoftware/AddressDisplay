<?php defined( '_JEXEC' ) or die( 'Restricted access' );

class ModAddressDisplayHelper {

	public $JPATH_COMPONENT;
	public $JPATH_COMPONENT_ADMINISTRATOR;

	public $view;
	public $params;

	function __construct(&$params) {

		$this->params =& $params;

		$this->JPATH_COMPONENT = JPATH_SITE.'/components/com_contact';
		$this->JPATH_COMPONENT_ADMINISTRATOR = JPATH_ADMINISTRATOR.'/components/com_contact';
	}

	public function display() {

		$contact_id = $this->params->get('contact_id');
		if ($contact_id<=0) return false;

		$app = JFactory::getApplication('site');

		JLoader::import('contact',$this->JPATH_COMPONENT.'/models');
		$model = JModelLegacy::getInstance('Contact','ContactModel',array('ignore_request'=>true));
		$model->setState('params',$app->getParams());

		JLoader::import('joomla.application.component.view');
		$this->view = new JViewLegacy(array(
			'name' => 'contact',
			'base_path' => $this->JPATH_COMPONENT,
		));
		$this->view->setModel($model,true);
		$this->view->addTemplatePath(JPATH_THEMES.'/'.$app->getTemplate().'/html/com_contact/contact');

		$this->view->params = JComponentHelper::getParams('com_contact');
		$this->view->contact = $this->view->getModel()->getItem($contact_id);

		$this->view->params->set('address_check',(int)(
			($this->view->contact->address && $this->view->params->get('show_street_address')) ||
			($this->view->contact->suburb && $this->view->params->get('show_suburb')) ||
			($this->view->contact->state && $this->view->params->get('show_state')) ||
			($this->view->contact->postcode && $this->view->params->get('show_postcode')) ||
			($this->view->contact->country && $this->view->params->get('show_country'))
		));
		
		ob_start();
		require JModuleHelper::getLayoutPath('mod_addressdisplay',$this->params->get('layout', 'default'));
		echo JHtml::_('content.prepare',ob_get_clean());
	}
}
