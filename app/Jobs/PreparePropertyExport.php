<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Excel;
use App\Exports\NorminalRowExportProperty;

class PreparePropertyExport implements ShouldQueue
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
      \App\TemporalFiles::truncate();
      (new NorminalRowExportProperty($this->year, $this->electoral))->queue($this->name);
      \App\TemporalFiles::create(['file_name' => $this->name]);
    }
}
