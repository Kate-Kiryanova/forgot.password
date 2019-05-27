<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application,
	Bitrix\Main\Localization\Loc;

class FlxMDForgotPassword extends CBitrixComponent
{

	private $arRequest = [];

	private $bCheckFields = false;
	private $isRegisterUser = false;

	private $arResponse = [];

	public function executeComponent()
	{
		Loc::loadMessages(__FILE__);

		$this->arResult["PARAMS_HASH"] = md5(serialize($this->arParams).$this->GetTemplateName());

		$this->arRequest = Application::getInstance()->getContext()->getRequest();

		if (
			$this->arRequest->isAjaxRequest() &&
			$this->arRequest->getPost('FLXMD_AJAX') === 'Y' &&
			$this->arRequest->getPost('PARAMS_HASH') === $this->arResult["PARAMS_HASH"]
		) {
			$this->checkFields();

			if ($this->bCheckFields)
				$this->isRegisterUser();

			if ($this->isRegisterUser)
				$this->sendEmail();

			$this->sendResponseAjax();

		} else {

			$this->IncludeComponentTemplate();

		}
	}

	public function checkFields()
	{
		if (
			$this->arRequest->getPost('PARAMS_HASH') === $this->arResult["PARAMS_HASH"] &&
			empty($this->arRequest->getPost('CHECK_EMPTY')) &&
			!empty($this->arRequest->getPost('forgot-email')) &&
			check_email($this->arRequest->getPost('forgot-email')) &&
			check_bitrix_sessid()
		) {

			$this->bCheckFields = true;

		} else {

			$this->arResponse = ['STATUS' => 'ERROR', 'MESSAGE' => Loc::getMessage("FLXMD_FORGOT_PASSWORD_FIELDS_ERROR")];
			$this->bCheckFields = false;

		}
	}

	public function isRegisterUser()
	{
		$this->arSearchUser = \Bitrix\Main\UserTable::GetList(array(
			'select' => array('ID', 'ACTIVE', 'LOGIN', 'EMAIL'),
			'filter' => array('LOGIN' => htmlspecialchars($this->arRequest->getPost('forgot-email')))
		));

		if ( $this->arUser = $this->arSearchUser->fetch() ) {
			if ($this->arUser["ACTIVE"] == 'Y') {
				$this->isRegisterUser = true;
			} else {
				$this->arResponse = ['STATUS' => 'ERROR', 'MESSAGE' => Loc::getMessage("FLXMD_FORGOT_PASSWORD_IS_REGISTER_WITHOUT_ACTIVE")];
			}
		} else {
			$this->arResponse = ['STATUS' => 'ERROR', 'MESSAGE' => Loc::getMessage("FLXMD_FORGOT_PASSWORD_IS_NOT_REGISTER")];
		}
	}

	public function sendEmail()
	{
		$this->user = new CUser;
		$this->arResult = $this->user->SendPassword(
			htmlspecialchars($this->arRequest->getPost('forgot-email')),
			htmlspecialchars($this->arRequest->getPost('forgot-email'))
		);

		if($this->arResult["TYPE"] == "OK") {
			$this->arResponse = ['STATUS' => 'SUCCESS'];
		} else {
			$this->arResponse = ['STATUS' => 'ERROR', 'MESSAGE' => Loc::getMessage("FLXMD_FORGOT_PASSWORD_ERROR")];
		}
	}

	public function sendResponseAjax() {

		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		echo json_encode($this->arResponse);

		die();

	}

}
