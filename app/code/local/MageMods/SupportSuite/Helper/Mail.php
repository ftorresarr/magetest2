<?php
/**
 * MageMods
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageMods EULA that is bundled with
 * this package in the file LICENSE.txt. It is also available through
 * the world-wide-web at this URL: http://www.magemods.co/LICENSE-1.0.txt
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to sales@magemods.co so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension to
 * newer versions in the future. If you wish to customize the extension for your
 * needs please refer to http://www.magemods.co/ for more information.
 */

class MageMods_SupportSuite_Helper_Mail
    extends Mage_Core_Helper_Abstract
{
    public function decodeHeader($header)
    {
        if (!function_exists('iconv_mime_decode')) {
            return $header;
        }

        return iconv_mime_decode($header, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, 'UTF-8');
    }
}
