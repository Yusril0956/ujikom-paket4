<?php

namespace App\Services\Exports;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ZipArchive;

class XlsxReportExporter
{
    public function export(
        string $filePrefix,
        string $sheetName,
        string $title,
        string $subtitle,
        array $columns,
        iterable $rows,
        array $options = []
    ): string {
        $tempDir = storage_path('app/exports');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

        $safePrefix = Str::slug($filePrefix) ?: 'export';
        $timestamp = now()->format('Ymd-His');
        $filePath = $tempDir . DIRECTORY_SEPARATOR . $safePrefix . '-' . $timestamp . '.xlsx';
        $archivePath = tempnam(sys_get_temp_dir(), 'xlsx_');

        if ($archivePath === false) {
            throw new \RuntimeException('Unable to create temporary xlsx archive.');
        }

        $logoPath = null;
        $includeLogo = Arr::get($options, 'logo', true);
        if ($includeLogo) {
            $logoPath = $this->createLogoImage();
        }

        $sheetName = Str::limit($sheetName, 31, '');
        $columns = array_values($columns);
        $rows = collect($rows)->map(fn ($row) => array_values($row))->all();

        $summaryLines = Arr::get($options, 'summary', []);

        $zip = new ZipArchive();
        if ($zip->open($archivePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Unable to create xlsx archive.');
        }

        try {
            $zip->addFromString('[Content_Types].xml', $this->contentTypesXml($includeLogo));
            $zip->addFromString('_rels/.rels', $this->rootRelsXml());
            $zip->addFromString('xl/workbook.xml', $this->workbookXml($sheetName));
            $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelsXml($includeLogo));
            $zip->addFromString('xl/styles.xml', $this->stylesXml());
            $zip->addFromString('xl/worksheets/sheet1.xml', $this->sheetXml($title, $subtitle, $summaryLines, $columns, $rows, $includeLogo));

            if ($includeLogo && $logoPath && file_exists($logoPath)) {
                $zip->addFile($logoPath, 'xl/media/image1.png');
                $zip->addFromString('xl/drawings/drawing1.xml', $this->drawingXml());
                $zip->addFromString('xl/worksheets/_rels/sheet1.xml.rels', $this->sheetRelsXml());
                $zip->addFromString('xl/drawings/_rels/drawing1.xml.rels', $this->drawingRelsXml());
            }
        } finally {
            $zip->close();
        }

        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        if (!copy($archivePath, $filePath)) {
            @unlink($archivePath);
            throw new \RuntimeException('Unable to move xlsx archive into export directory.');
        }

        @unlink($archivePath);

        if ($logoPath && file_exists($logoPath)) {
            @unlink($logoPath);
        }

        return $filePath;
    }

    public function buildSummaryLines(array $pairs): array
    {
        return array_values(array_filter(array_map(function ($pair) {
            if (is_string($pair)) {
                return $pair;
            }

            $label = $pair['label'] ?? null;
            $value = $pair['value'] ?? null;

            if ($label === null && $value === null) {
                return null;
            }

            return trim(($label ? $label . ': ' : '') . ($value ?? ''));
        }, $pairs)));
    }

    private function contentTypesXml(bool $includeLogo): string
    {
        $imageOverride = $includeLogo ? '<Override PartName="/xl/drawings/drawing1.xml" ContentType="application/vnd.openxmlformats-officedocument.drawing+xml"/>' : '';
        $imageDefault = $includeLogo ? '<Default Extension="png" ContentType="image/png"/>' : '';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . $imageDefault
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . $imageOverride
            . '</Types>';
    }

    private function rootRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
    }

    private function workbookXml(string $sheetName): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="' . $this->xml($sheetName) . '" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';
    }

    private function workbookRelsXml(bool $includeLogo): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '</Relationships>';
    }

    private function sheetRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/drawing" Target="../drawings/drawing1.xml"/>'
            . '</Relationships>';
    }

    private function drawingRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="../media/image1.png"/>'
            . '</Relationships>';
    }

    private function drawingXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<xdr:wsDr xmlns:xdr="http://schemas.openxmlformats.org/drawingml/2006/spreadsheetDrawing" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<xdr:oneCellAnchor>'
            . '<xdr:from><xdr:col>0</xdr:col><xdr:colOff>0</xdr:colOff><xdr:row>0</xdr:row><xdr:rowOff>0</xdr:rowOff></xdr:from>'
            . '<xdr:ext cx="914400" cy="914400"/>'
            . '<xdr:pic>'
            . '<xdr:nvPicPr>'
            . '<xdr:cNvPr id="2" name="Scriptoria Logo"/>'
            . '<xdr:cNvPicPr><a:picLocks noChangeAspect="1"/></xdr:cNvPicPr>'
            . '</xdr:nvPicPr>'
            . '<xdr:blipFill><a:blip r:embed="rId1"/><a:stretch><a:fillRect/></a:stretch></xdr:blipFill>'
            . '<xdr:spPr><a:prstGeom prst="rect"><a:avLst/></a:prstGeom></xdr:spPr>'
            . '</xdr:pic>'
            . '<xdr:clientData/>'
            . '</xdr:oneCellAnchor>'
            . '</xdr:wsDr>';
    }

    private function stylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="4">'
            . '<font><sz val="11"/><name val="Aptos"/></font>'
            . '<font><b/><sz val="16"/><color rgb="FFFFFFFF"/><name val="Aptos"/></font>'
            . '<font><sz val="10"/><color rgb="FFFFFFFF"/><name val="Aptos"/></font>'
            . '<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Aptos"/></font>'
            . '</fonts>'
            . '<fills count="5">'
            . '<fill><patternFill patternType="none"/></fill>'
            . '<fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF20160F"/><bgColor indexed="64"/></patternFill></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFF3EEE6"/><bgColor indexed="64"/></patternFill></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFFFFFFF"/><bgColor indexed="64"/></patternFill></fill>'
            . '</fills>'
            . '<borders count="2">'
            . '<border><left/><right/><top/><bottom/><diagonal/></border>'
            . '<border>'
            . '<left style="thin"><color rgb="FFD6CFC4"/></left>'
            . '<right style="thin"><color rgb="FFD6CFC4"/></right>'
            . '<top style="thin"><color rgb="FFD6CFC4"/></top>'
            . '<bottom style="thin"><color rgb="FFD6CFC4"/></bottom>'
            . '<diagonal/>'
            . '</border>'
            . '</borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="8">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyFont="0" applyFill="0" applyBorder="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>'
            . '<xf numFmtId="0" fontId="2" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>'
            . '<xf numFmtId="0" fontId="3" fillId="3" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>'
            . '<xf numFmtId="0" fontId="0" fillId="4" borderId="1" xfId="0" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>'
            . '<xf numFmtId="0" fontId="3" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            . '<xf numFmtId="0" fontId="0" fillId="4" borderId="1" xfId="0" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>'
            . '<xf numFmtId="0" fontId="0" fillId="3" borderId="1" xfId="0" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf>'
            . '</cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>';
    }

    private function sheetXml(string $title, string $subtitle, array $summaryLines, array $columns, array $rows, bool $includeLogo): string
    {
        $lastColumnLetter = $this->columnLetter(count($columns));
        $dataStartRow = 7;
        $dataEndRow = $dataStartRow + count($rows) - 1;
        $dimensionEnd = $this->columnLetter(max(count($columns), 2)) . max($dataEndRow, 7);

        $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $sheet .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">';
        $sheet .= '<dimension ref="A1:' . $dimensionEnd . '"/>';
        $sheet .= '<sheetViews><sheetView workbookViewId="0"><pane ySplit="6" topLeftCell="A7" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>';
        $sheet .= '<sheetFormatPr defaultRowHeight="20"/>';
        $sheet .= '<cols>';
        foreach ($columns as $index => $column) {
            $width = $column['width'] ?? 16;
            if ($index === 0) {
                $width = max(14, (float) $width);
            }
            $sheet .= '<col min="' . ($index + 1) . '" max="' . ($index + 1) . '" width="' . (float) $width . '" customWidth="1"/>';
        }
        $sheet .= '</cols>';
        $sheet .= '<sheetData>';
        $sheet .= $this->rowXml(1, [
            $this->inlineCell('B1', $title, 1),
        ], 30);
        $sheet .= $this->rowXml(2, [
            $this->inlineCell('B2', $subtitle, 2),
        ], 22);
        $sheet .= $this->rowXml(4, [
            $this->inlineCell('A4', 'Generated', 3),
            $this->inlineCell('B4', now()->format('d M Y H:i') . ' WIB', 4),
        ]);
        $sheet .= $this->rowXml(5, [
            $this->inlineCell('A5', 'Filter', 3),
            $this->inlineCell('B5', $summaryLines ? implode(' | ', $summaryLines) : 'Semua data', 4),
        ]);

        $headerCells = [];
        foreach ($columns as $index => $column) {
            $cellRef = $this->columnLetter($index + 1) . '6';
            $headerCells[] = $this->inlineCell($cellRef, $column['header'], 5);
        }
        $sheet .= $this->rowXml(6, $headerCells, 24);

        foreach ($rows as $rowIndex => $row) {
            $cells = [];
            foreach ($columns as $colIndex => $column) {
                $cellRef = $this->columnLetter($colIndex + 1) . ($dataStartRow + $rowIndex);
                $value = $row[$colIndex] ?? null;
                $style = $rowIndex % 2 === 0 ? 6 : 7;
                $cells[] = $this->cellXml($cellRef, $value, $style);
            }
            $sheet .= $this->rowXml($dataStartRow + $rowIndex, $cells);
        }

        $sheet .= '</sheetData>';
        $sheet .= '<mergeCells count="4">';
        $sheet .= '<mergeCell ref="B1:' . $lastColumnLetter . '1"/>';
        $sheet .= '<mergeCell ref="B2:' . $lastColumnLetter . '2"/>';
        $sheet .= '<mergeCell ref="B4:' . $lastColumnLetter . '4"/>';
        $sheet .= '<mergeCell ref="B5:' . $lastColumnLetter . '5"/>';
        $sheet .= '</mergeCells>';
        $sheet .= '<autoFilter ref="A6:' . $lastColumnLetter . '6"/>';
        if ($includeLogo) {
            $sheet .= '<drawing r:id="rId1"/>';
        }
        $sheet .= '<pageMargins left="0.3" right="0.3" top="0.4" bottom="0.4" header="0.2" footer="0.2"/>';
        $sheet .= '</worksheet>';

        return $sheet;
    }

    private function rowXml(int $rowNumber, array $cells, int $height = 20): string
    {
        $xml = '<row r="' . $rowNumber . '" ht="' . $height . '" customHeight="1">';
        $xml .= implode('', $cells);
        $xml .= '</row>';
        return $xml;
    }

    private function inlineCell(string $cellRef, string $value, int $style = 0): string
    {
        return '<c r="' . $cellRef . '" t="inlineStr" s="' . $style . '"><is><t xml:space="preserve">' . $this->xml($value) . '</t></is></c>';
    }

    private function cellXml(string $cellRef, mixed $value, int $style = 0): string
    {
        if ($value === null || $value === '') {
            return '<c r="' . $cellRef . '" s="' . $style . '"/>';
        }

        if ($value instanceof CarbonInterface || $value instanceof DateTimeInterface) {
            return $this->inlineCell($cellRef, $value->format('d M Y H:i'), $style);
        }

        if (is_bool($value)) {
            return $this->inlineCell($cellRef, $value ? 'Ya' : 'Tidak', $style);
        }

        if (is_int($value) || is_float($value) || (is_numeric($value) && !preg_match('/^0[0-9]+$/', (string) $value))) {
            return '<c r="' . $cellRef . '" s="' . $style . '"><v>' . $value . '</v></c>';
        }

        return $this->inlineCell($cellRef, (string) $value, $style);
    }

    private function xml(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    private function columnLetter(int $index): string
    {
        $letter = '';
        while ($index > 0) {
            $index--;
            $letter = chr(65 + ($index % 26)) . $letter;
            $index = intdiv($index, 26);
        }

        return $letter;
    }

    private function createLogoImage(): string
    {
        $tempDir = storage_path('app/exports');
        $path = $tempDir . DIRECTORY_SEPARATOR . 'scriptoria-logo.png';

        $size = 256;
        $img = imagecreatetruecolor($size, $size);
        imagesavealpha($img, true);
        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $transparent);

        $bg = imagecolorallocate($img, 31, 22, 15);
        $accent = imagecolorallocate($img, 233, 220, 197);
        $white = imagecolorallocate($img, 255, 255, 255);
        $muted = imagecolorallocate($img, 214, 207, 196);

        imagefilledellipse($img, 128, 128, 216, 216, $bg);
        imageellipse($img, 128, 128, 216, 216, $accent);

        imagerectangle($img, 86, 92, 116, 172, $white);
        imagerectangle($img, 140, 92, 170, 172, $white);
        imageline($img, 116, 98, 140, 92, $white);
        imageline($img, 116, 166, 140, 172, $white);
        imageline($img, 128, 96, 128, 170, $white);
        imageline($img, 96, 182, 160, 182, $muted);

        $font = 5;
        $text = 'S';
        $textWidth = imagefontwidth($font) * strlen($text);
        $textHeight = imagefontheight($font);
        imagestring($img, $font, (int) ((256 - $textWidth) / 2), (int) (122 - $textHeight / 2), $text, $accent);

        imagepng($img, $path);
        imagedestroy($img);

        return $path;
    }
}
