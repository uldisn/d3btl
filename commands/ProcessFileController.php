<?php

namespace d3yii2\d3btl\commands;

use d3yii2\d3btl\Module;
use yii\console\ExitCode;
use yii\console\Controller;
use d3yii2\d3btl\models\BtlFileData;
use d3yii2\d3btl\models\BtlPart;

/**
* Class ProcessFileController* @property Module $module
*/
class ProcessFileController extends Controller
{

    public const REG_GENERAL = '#GENERAL](?s)(.*)\[RAWPART#';

    /**
     * @param string $filename
     * @param string $notes
     * @return int
     */
    public function actionAdd($filename, $notes = null): int
    {
        $fileText = file_get_contents($filename);

        $generalInfo = $this->getGeneral($fileText);

        // saving the BtlFileData from general info

        // saving the BtlPart

        return ExitCode::OK;
    }

    private function getGeneral($fileText)
    {
        $matches = [];
        
        $result = preg_match(self::REG_GENERAL, $fileText, $matches);

        if (!$result) {
            $this->stdout('file reading error');
        }

        if (isset($matches[1])) {
            return $this->parseFile($matches[1]);
        }

    }

    private function parseFile($text)
    {
        $parsedText = [];

        $textChunks = explode(PHP_EOL, $text);

        foreach ($textChunks as $chunk) {
            $valuePair = explode(': ', $chunk);

            if (count($valuePair) === 2) {
                $parsedText[$valuePair[0]] = $valuePair[1];
            }
        }


        return $parsedText;

    }

}

