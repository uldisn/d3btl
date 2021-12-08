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

    public const REGEX = '#GENERAL](?s)(.*)\[RAWPART](?s)(.*)\[PART](.*)\[PART](.*)#';

    public const GENERAL = 'general';
    public const RAWPART = 'rawpart';
    public const PART = 'part';

    /**
     * @param string $filename
     * @param string $notes
     * @return int
     */
    public function actionAdd($filename, $notes = null): int
    {
        $fileText = file_get_contents($filename);

        $allParts = $this->getParts($fileText);

        $generalInfo = array_shift($allParts);
        $btlData = $this->saveGeneralInfo($generalInfo[1]);

        foreach ($allParts as $part) {
             $this->savePart($part[1], $part[0], $btlData->id);
        }

        $btlData->file_data = $fileText;
        $btlData->parsed_data = json_encode($this->parseText($fileText));

        $btlData->save();

        return ExitCode::OK;
    }

    private function getParts($fileText)
    {
        $matches = [];
        
        $result = preg_match(self::REGEX, $fileText, $matches);

        if (!$result) {
            $this->stdout('file reading error');
        }

        // too specific for example file
        // also propably should make it into an object
        return [[ self::GENERAL, $this->parseText($matches[1])],
                [ self::RAWPART, $this->parseText($matches[2])],
                [ self::PART, $this->parseText($matches[3])],
                [ self::PART, $this->parseText($matches[4])],
        ];

    }

    private function parseText($text)
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

    private function saveGeneralInfo($generalInfo)
    {
       $btlData = new BtlFileData;
       $btlData->status = BtlFileData::STATUS_PROCESSED;
       $btlData->add_time = date('Y-m-d H:i:s');
       $btlData->project_name = $generalInfo['PROJECTNAME'];

       $dateTime = str_replace('\\', ' ', $generalInfo['EXPORTDATE'] . $generalInfo['EXPORTTIME']);
       $dateTime = str_replace('"', ' ', $dateTime);

       $btlData->export_datetime = $dateTime;

       $btlData->save();

       return $btlData;
    }

    private function savePart($partInfo, $type, $fileId)
    {
        $btlPart = new BtlPart;

        $btlPart->file_data_id = $fileId;
        $btlPart->type = $type;
        $btlPart->single_member_number = (int)$partInfo['SINGLEMEMBERNUMBER'];
        $btlPart->assembly_number = (int)$partInfo['ASSEMBLYNUMBER'] ;
        $btlPart->order_number = $partInfo['ORDERNUMBER'];
        $btlPart->designation = $partInfo['DESIGNATION'];
        // etc.

        if (!$btlPart->save()) {
            $this->stdout(print_r($btlPart->getErrors()));
        };

    }
}

