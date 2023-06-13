<?php

declare(strict_types=1);

namespace Hj;

use Box\Spout\Common\Entity\Cell;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Reader\XLSX\Reader;
use Box\Spout\Writer\XLSX\Writer;

class XlsxRecorder
{
    public function __construct(
        private Reader $reader,
        private Writer $writer
    ) {
    }

    public function save(
        string $xlsxFileWhereToRecordData,
        string $xlsxTempDataFile,
        string $sheetWhereToAddData,
        array $rowsBeingAdded,
        ?string $newHeaderRows
    ) {
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
            $cells = [];

            foreach ($rowBeingAdded as $item) {
                array_push($cells, new Cell($item));
            }

            $this->writer->addRow(new Row($cells, null));
        }

        $this->reader->close();
        $this->writer->close();

        unlink($existingFilePath);
        rename($newFilePath, $existingFilePath);
    }
}
