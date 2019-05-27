<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

<form class="form entry__form js-validate" id="forgot-pass-form" action="<?=POST_FORM_ACTION_URI;?>" method="post" autocomplete="off">

	<?=bitrix_sessid_post();?>

	<input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>" />
	<input type="hidden" name="FLXMD_AJAX" value="Y" />
	<input type="hidden" name="CHECK_EMPTY" value="" />

	<p class="entry__text">
		<?= Loc::getMessage('FORGOT_PASSWORD_INFO_TITLE'); ?>
	</p>

	<div class="form__message js-error-container"></div>

	<div class="form__row">
		<div class="form__item" id="forgot-pass-email-item">
			<label class="form__label" for="forgot-pass-email">
				<?= Loc::getMessage('FORGOT_PASSWORD_EMAIL'); ?>*
			</label>
			<input
				class="input"
				id="forgot-pass-email"
				type="email"
				name="forgot-email"
				required
				placeholder="<?= Loc::getMessage('FORGOT_PASSWORD_EMAIL_PLACEHOLDER'); ?>"
				data-required-message="<?= Loc::getMessage('FORGOT_PASSWORD_REQUIRED_FIELD'); ?>"
				data-error-message="<?= Loc::getMessage('FORGOT_PASSWORD_EMAIL_ERROR'); ?>"
				data-error-target="#forgot-pass-email-item"
			/>
		</div>
	</div>

	<div class="form__footer">
		<button class="btn btn--big entry__btn" type="submit">
			<?= Loc::getMessage('FORGOT_PASSWORD_SEND'); ?>
		</button>
	</div>

</form>
