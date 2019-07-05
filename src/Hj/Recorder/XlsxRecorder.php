<?php

namespace Hj\Recorder;

use Box\Spout\Reader\XLSX\Reader;
use Box\Spout\Writer\XLSX\Writer;

class XlsxRecorder
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var Writer
     */
    private $writer;

    /**
     * @param $reader
     * @param $writer
     */
    function __construct($reader, $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    /**
     * @param string $xlsxFileWhereToRecordData
     * @param string $xlsxTempDataFile
     * @param string $sheetWhereToAddData
     * @param array $rowsBeingAdded
     *
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\SpoutException
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     * @throws \Exception
     *
     */
    public function save($xlsxFileWhereToRecordData, $xlsxTempDataFile, $sheetWhereToAddData, array $rowsBeingAdded, $newHeaderRows = null)
    {
        $existingFilePath = $xlsxFileWhereToRecordData;
        $newFilePath = $xlsxTempDataFile;

        // we need a reader to read the existing file...
        $this->reader->open($existingFilePath);
        $this->reader->setShouldFormatDates(true); // this is to be able to copy dates

        // ... and a writer to create the new file
        $this->writer->openToFile($newFilePath);

        // let's read the entire spreadsheet...
        foreach ($this->reader->getSheetIterator() as $sheetIndex => $sheet) {
            $originalSheetName = $sheet->getName();
            // Add sheets in the new file, as we read new sheets in the existing one
            if ($sheetIndex !== 1) {
                $this->writer->addNewSheetAndMakeItCurrent();
            }

            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                if ($newHeaderRows !== null && $rowIndex == 1 && $sheet->getName() === $sheetWhereToAddData) {
                    $row = $newHeaderRows;
                }
                // ... and copy each row into the new spreadsheet
                $this->writer->addRow($row);
            }
            // on sauvegarde le nom original de la feuille
            $sheet = $this->writer->getCurrentSheet();
            $sheet->setName($originalSheetName);
        }

        foreach ($this->writer->getSheets() as $sheet) {
            if ($sheet->getName() === $sheetWhereToAddData) {
                $this->writer->setCurrentSheet($sheet);
            }
        }
        // At this point, the new spreadsheet contains the same data as the existing one.
        // So let's add the new data:
        foreach ($rowsBeingAdded as $rowBeingAdded) {
            $this->writer->addRow($rowBeingAdded);
        }


        $this->reader->close();
        $this->writer->close();

        unlink($existingFilePath);
        rename($newFilePath, $existingFilePath);
    }
}