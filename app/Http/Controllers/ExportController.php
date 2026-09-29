<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\SponsorInquiry;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function leads(): StreamedResponse
    {
        return $this->csv('equinox-join-the-list', Lead::query(), [
            'id', 'created_at', 'name', 'email', 'organization', 'country', 'profile', 'source',
        ]);
    }

    public function sponsors(): StreamedResponse
    {
        return $this->csv('equinox-sponsor-inquiries', SponsorInquiry::query(), [
            'id', 'created_at', 'name', 'email', 'company', 'job_title', 'phone', 'country', 'website', 'interest', 'message',
        ]);
    }

    private function csv(string $name, Builder $query, array $columns): StreamedResponse
    {
        $filename = $name.'-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($query, $columns) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel shows accents correctly
            fputcsv($out, $columns);
            $query->orderBy('id')->chunk(500, function ($rows) use ($out, $columns) {
                foreach ($rows as $row) {
                    fputcsv($out, array_map(fn ($c) => (string) $row->{$c}, $columns));
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
