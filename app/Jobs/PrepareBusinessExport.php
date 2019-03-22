<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Excel;
use App\Exports\NorminalRowExportBusiness;

use Illuminate\Support\Facades\Storage;
use File;
use Response;

class PrepareBusinessExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $year;
    protected $electoral;
    protected $name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($year, $electoral, $name)
    {
      $this->year = $year;
      $this->electoral = $electoral;
      $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      ini_set('memory_limit','256M');
      \App\TemporalFiles::truncate();
      // \App\TemporalFiles::create(['file' => 'gt', 'filename' => $this->name]);
      // (new NorminalRowExportBusiness($this->year, $this->electoral))->queue($this->name);
      $gt = Excel::raw(new NorminalRowExportBusiness($this->year, $this->electoral), \Maatwebsite\Excel\Excel::XLSX);
      \App\TemporalFiles::create(['file' => $gt, 'filename' => $this->name]);
      // $page = File::put(public_path('images/kbills/'.$this->name), $gt);

      // \App\TemporalFiles::create(['file_name' => $gt]);
      // return Response::download(public_path('images/kbills/'.$this->name));
    }
}
