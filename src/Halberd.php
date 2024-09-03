<?php

namespace GrimPirate\Halberd;

use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;

use PragmaRX\Google2FA\Google2FA;

class Halberd
{
    protected Google2FA $g2fa;

	public function __construct()
	{
		$this->g2fa = new Google2FA();
	}

    public function generateSecretKey()
    {
        return $this->g2fa->generateSecretKey();
    }

    public function verifyKeyNewer($secret, $code, $timestamp)
    {
        return false !== $this->g2fa->verifyKeyNewer($secret, $code, floor($timestamp / $this->g2fa->getKeyRegeneration()));
    }

    public function qrcode($issuer, $accountname, $secret)
	{
		$writer = new Writer(new ImageRenderer(
			new RendererStyle(120),
			new SvgImageBackEnd()
		));

		$path = preg_replace(
			'/^.*d="([^"]+).*$/s',	// Leave only path data
			'$1',
			$writer->writeString($this->g2fa->getQRCodeUrl($issuer, $accountname, $secret)));

		// Optimize path data
		$path = preg_split('/([MLZ]+)/', $path, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		for($i = 3; $i < count($path); $i += 2)
		{
			$prevCoord = preg_split('/\h+/', $path[$i - 2], -1, PREG_SPLIT_NO_EMPTY);
			$currCoord = preg_split('/\h+/', $path[$i], -1, PREG_SPLIT_NO_EMPTY);
			if($path[$i - 1] == 'L')
			{
				if($prevCoord[0] == $currCoord[0])
					$path[$i - 1] = 'V';
				elseif($prevCoord[1] == $currCoord[1])
					$path[$i - 1] = 'H';
			}
		}
		for($i = 2; $i < count($path) - 1; $i += 2)
		{
			$currCoord = preg_split('/\h+/', $path[$i + 1], -1, PREG_SPLIT_NO_EMPTY);
			if($path[$i] == 'H')
				$path[$i + 1] = $currCoord[0];
			elseif ($path[$i] == 'V')
				$path[$i + 1] = $currCoord[1];
		}

		return base64_encode(gzcompress(implode('', $path), 9));
	}
}