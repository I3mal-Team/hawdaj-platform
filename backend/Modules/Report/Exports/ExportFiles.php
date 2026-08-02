<?php

namespace Modules\Report\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportFiles implements FromCollection, WithHeadingRow, WithHeadings
{
    protected $result, $columns;

    public function __construct($result)
    {
        $this->result = collect($result);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return ($this->result);
    }

    public function headings(): array
    {
        return [array_keys(collect($this->result)[0])];
    }
}
