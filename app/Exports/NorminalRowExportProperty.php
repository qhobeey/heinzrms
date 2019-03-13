<?php

namespace App\Exports;

use App\Reports\ElectoralPropertyReport;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;
use \Maatwebsite\Excel\Writer;

class NorminalRowExportProperty implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable;

    protected $electoral;
    protected $year;

    public function __construct(int $year, string $electoral)
    {
        $this->year = $year;
        $this->electoral = $electoral;
    }


    public function array(): array
    {
      $response = array();
      $year = $this->year;
      $bills = ElectoralPropertyReport::query()->where('code', $this->electoral)->whereHas('bills', function($q) use ($year) {
        $q->where('year', $year);
      })->with(['bills' => function($query) use ($year) {
        $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'))->orderBy('account_no', 'asc');
      }])->first()->bills;
      foreach ($bills as $key => $bill) {
        if(!$bill->property) continue;
        // dd($bill->property);
        $newData = [
          $key + 1, $bill->account_no, $bill->property->owner ? $bill->property->owner->name : 'NO NAME', $bill->property->address,
          $bill->property->category ? $bill->property->category->description : 'NA',
          $bill->property->category ? ($bill->property->category->rate_pa ?: floatval(0)) : floatval(0), \App\Repositories\ExpoFunction::formatMoney(floatval($bill->property->rateable_value), true), \App\Repositories\ExpoFunction::formatMoney(floatval($bill->arrears), true),
          \App\Repositories\ExpoFunction::formatMoney(floatval($bill->current_amount), true), \App\Repositories\ExpoFunction::formatMoney(floatval($bill->arrears + $bill->current_amount), true), \App\Repositories\ExpoFunction::formatMoney(floatval($bill->total_paid), true),
          \App\Repositories\ExpoFunction::formatMoney(floatval(($bill->arrears + $bill->current_amount) - $bill->total_paid), true)
        ];
        array_push($response, $newData);
        // dd($response);
      }
      // dd($response);
      return $response;
    }

    public function headings(): array
    {
        return [
            '#',
            'ACCOUNT NO.',
            'OWNER NAME	',
            'PROPERTY ADDRESS',
            'PROPERTY CAT.',
            'RATE IMPOSED',
            'RATEABLE VALUE',
            'ARREARS',
            'CURRENT BILL',
            'TOTAL BILL',
            'TOTAL PAYMENT',
            'OUTSTANDING ARREARS',
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class  => function(BeforeExport $event) {
                $event->writer->getProperties()->setCreator('Patrick');
            },
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $cellRange = 'A1:W1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(13);
            },
        ];
    }
}
