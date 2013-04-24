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

class MageMods_SupportSuite_Block_Adminhtml_Ticket_Edit_Tab_Message_View
    extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        $this->setTemplate('supportsuite/ticket/message.phtml');

        parent::__construct();
    }

    public function parseQuotes($text)
    {
        $output = array();

        $prevLevel = $curLevel = 0;

        $text = str_replace(array("\r\n", "\r"), "\n", $text);

        foreach (explode("\n", $text) as $line) {
            while (preg_match('#^[ ]*&gt;[ ]?#', $line, $matches)) {
                $curLevel++;
                $line = (string) substr($line, strlen($matches[0]));
            }

            while ($prevLevel < $curLevel) {
                $line = '<blockquote style="border-left:2px solid red;padding-left:5px;font-style:italic">' . $line;
                $prevLevel++;
            }

            while ($curLevel < $prevLevel) {
                $line = '</blockquote>' . $line;
                $prevLevel--;
            }

            $output[] = $line;

            $curLevel = 0;
        }

        return join("\n", $output);
    }
}
