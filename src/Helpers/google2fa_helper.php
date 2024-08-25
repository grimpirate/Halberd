<?php

use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;

use PragmaRX\Google2FA\Google2FA;

if(!function_exists('qrcode'))
{
	function qrcode($issuer, $accountname, $secret)
	{
		$writer = new Writer(new ImageRenderer(
			new RendererStyle(120),
			new SvgImageBackEnd()
		));

		return preg_replace(
			'/^.*d="([^"]+).*$/',	// Leave only path data
			'$1',
			$writer->writeString((new Google2FA())->getQRCodeUrl($issuer, $accountname, $secret)));
	}
}

if(!function_exists('getCurrentOtp'))
{
	function getCurrentOtp($secret)
	{
		return (new Google2FA())->getCurrentOtp($secret);
	}
}

if(!function_exists('generateSecretKey'))
{
	function generateSecretKey()
	{
		return (new Google2FA())->generateSecretKey();
	}
}

if(!function_exists('verifyKeyNewer'))
{
	function verifyKeyNewer($secret, $code, $timestamp)
	{
		$g2fa = new Google2FA();
		return false !== $g2fa->verifyKeyNewer($secret, $code, floor($timestamp / $g2fa->getKeyRegeneration()));
	}
}