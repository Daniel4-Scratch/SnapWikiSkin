<?php
/**
 * SkinTemplate class for SnapWikiSkin
 *
 * @file
 * @ingroup Skins
 */

class HTMLColorField extends HTMLFormField {

	public function getInputHTML( $value ) {

		$attribs = [
			'id' => $this->mID,
			'name' => $this->mName,
			'value' => $value,
			'dir' => $this->mDir,
			'pattern' => '#[0-9A-Za-z]{6}',
		];

		if ( $this->mClass !== '' ) {
			$attribs['class'] = $this->mClass;
		}

		$allowedParams = [
			'type',
			'pattern',
			'title',
			'disabled',
			'required',
			'autofocus',
			'readonly',
		];

		$attribs += $this->getAttributes( $allowedParams );
		return Html::input( $this->mName, $value, 'color', $attribs );
	}

	public function validate( $value, $alldata ) {
		if (preg_match('%#[a-zA-Z0-9]{6}%', $value) === 0) {
			return $this->msg( 'htmlform-invalid-input' );
		}
		return parent::validate($value, $alldata);
	}
}

class SkinSnapWikiSkin extends SkinTemplate {
	var $skinname = 'SnapWikiSkin', $stylename = 'SnapWikiSkin',
		$template = 'SnapWikiSkinTemplate', $useHeadElement = true;

	/**
	 * Add CSS via ResourceLoader
	 *
	 * @param $out OutputPage
	 */
	public function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );
		$out->addModuleStyles( [
			'mediawiki.skinning.interface', 'skins.snapwikiskin2'
		] );
		// make Chrome mobile testing work
		$out->addMeta('viewport', 'user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height');
	}

	static function onGetPreferences( $user, &$preferences ) {
		HTMLForm::$typeMappings['color'] = HTMLColorField::class;
		$origpref = $user->getOption( 'snapwikiskin-header-color' );
		$preferences['snapwikiskin-header-color'] = [
			'type' => 'color',
			'pattern' => '#[0-9A-Za-z]{6}',
			'label-message' => 'snapwikiskin-pref-color',
			'section' => 'rendering/skin',
			'default' => ($origpref ? $origpref : '#7953c4'),
		];
		return true;
	}
}
