<?php

namespace App\Exports;

use Illuminate\Support\Collection;;

use App\Reports\ElectoralPropertyReport;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;

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

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class NorminalRowExportProperty implements FromCollection, ShouldAutoSize, WithEvents, ShouldQueue, WithHeadings
{
    use Exportable;

    protected $electoral;
    protected $year;

    public function __construct(int $year, string $electoral)
    {
        $this->year = $year;
        $this->electoral = $electoral;
    }

    // public function collection()
    // {
    //   $response = \App\PropertyCategory::all();
    //   // dd($response);
    //   return $response;
    // }

    public function collection()
    {
        $response = array();
        $year = $this->year;
        $bills = ElectoralPropertyReport::where('code', $this->electoral)->whereHas('bills', function($q) use ($year) {
          $q->where('year', $year);
        })->with(['bills' => function($query) use ($year) {
          $query->where('year', $year)->where(strtoupper('bill_type'), strtoupper('p'))->orderBy('account_no', 'asc');
        }])->first()->bills;

        foreach ($bills as $key => $bill) {
          if(!$bill->property) continue;
          $newData = [
            $key + 1, $bill->account_no, $bill->property->owner ? $bill->property->owner->name : 'NO NAME', $bill->property->address,
            $bill->property->category ? $bill->property->category->description : 'NA',
            $bill->property->category ? ($bill->property->category->rate_pa ?: floatval(0)) : floatval(0), floatval($bill->property->rateable_value), floatval($bill->arrears),
            floatval($bill->current_amount), floatval($bill->arrears + $bill->current_amount), floatval($bill->total_paid),
            floatval(($bill->arrears + $bill->current_amount) - $bill->total_paid)
          ];
          array_push($response, $newData);
          // dd(collect($response));
        }
        // dd(collect($response));
        return collect($response);
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
