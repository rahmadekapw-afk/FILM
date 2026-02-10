<?php

namespace App\Imports;

use App\Models\Movie;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MovieImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip empty rows or rows missing title
        if (!isset($row['title']) || empty($row['title'])) {
            return null;
        }

        return new Movie([
            'title' => $row['title'],
            'genres' => $row['genres'] ?? 'N/A',
        ]);
    }
}
