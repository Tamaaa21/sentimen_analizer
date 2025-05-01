<?php

namespace App\Http\Controllers;

use App\Models\Sentiment;
use App\Services\SentimentAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SentimentController extends Controller
{
    protected $sentimentAnalyzer;

    public function __construct(SentimentAnalyzer $sentimentAnalyzer)
    {
        $this->middleware('auth');
        $this->sentimentAnalyzer = $sentimentAnalyzer;
    }

    public function index()
    {
        $sentiments = Sentiment::where('user_id', Auth::id())->latest()->get();

        $sentimentCounts = [
            'positive' => $sentiments->where('sentiment', 'positive')->count(),
            'neutral' => $sentiments->where('sentiment', 'neutral')->count(),
            'negative' => $sentiments->where('sentiment', 'negative')->count(),
        ];

        // For time series data
        $groupedSentiments = $sentiments->groupBy(function($item) {
            return $item->created_at->format('Y-m-d');
        })->map(function($day) {
            return [
                'positive' => $day->where('sentiment', 'positive')->count(),
                'neutral' => $day->where('sentiment', 'neutral')->count(),
                'negative' => $day->where('sentiment', 'negative')->count()
            ];
        });

        return view('sentiments.index', compact('sentiments', 'sentimentCounts', 'groupedSentiments'));
    }

    public function analyze(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'nullable|string|min:3|required_without:file',
            'file' => 'nullable|file|mimes:csv,json|max:2048|required_without:text',
        ], [
            'text.required_without' => 'Masukkan teks atau unggah file',
            'file.required_without' => 'Masukkan teks atau unggah file',
            'file.max' => 'Ukuran file maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $results = [];

            if ($request->hasFile('file')) {
                $results = $this->processUploadedFile($request->file('file'));
                return redirect()->route('sentiments.index')
                    ->with('results', $results)
                    ->with('source', 'file');
            }

            if ($request->filled('text')) {
                $results[] = $this->processManualText($request->input('text'));
                return redirect()->route('sentiments.index')
                    ->with('result', $results[0])
                    ->with('source', 'manual');
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses: ' . $e->getMessage());
        }
    }

    protected function processUploadedFile($file)
    {
        $extension = $file->getClientOriginalExtension();
        $results = [];

        if ($extension === 'csv') {
            $results = $this->processCsvFile($file);
        } elseif ($extension === 'json') {
            $results = $this->processJsonFile($file);
        }

        // Batch insert for better performance
        if (!empty($results)) {
            $records = array_map(function ($result) {
                return [
                    'user_id' => Auth::id(),
                    'text' => $result['text'],
                    'sentiment' => $result['sentiment'],
                    'probability' => $result['probability'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $results);

            Sentiment::insert($records);
        }

        return $results;
    }

    protected function processCsvFile($file)
    {
        $results = [];
        $handle = fopen($file->getPathname(), 'r');

        if (!$handle) {
            throw new \Exception('Gagal membuka file CSV');
        }

        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            throw new \Exception('File CSV tidak memiliki header');
        }

        // Normalisasi header (case-insensitive)
        $header = array_map(fn($h) => strtolower(trim($h)), $header);

        $textIndex = array_search('text', $header);
        if ($textIndex === false) {
            fclose($handle);
            throw new \Exception('Kolom "text" diperlukan dalam file CSV');
        }

        $sentimentIndex = array_search('sentiment', $header);
        $probabilityIndex = array_search('probability', $header);

        while (($data = fgetcsv($handle)) !== false) {
            if (!isset($data[$textIndex])) {
                continue;
            }

            $text = trim($data[$textIndex]);
            if ($text === '') {
                continue;
            }

            $sentiment = ($sentimentIndex !== false && isset($data[$sentimentIndex]))
                ? trim($data[$sentimentIndex])
                : null;

            $probability = ($probabilityIndex !== false && isset($data[$probabilityIndex]))
                ? floatval($data[$probabilityIndex])
                : 0.8;

            $results[] = $this->sentimentAnalyzer->analyze($text, $sentiment, $probability);
        }

        fclose($handle);
        return $results;
    }


    protected function processJsonFile($file)
    {
        $jsonData = json_decode(file_get_contents($file->getPathname()), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Format JSON tidak valid');
        }

        if (!is_array($jsonData)) {
            throw new \Exception('Data JSON harus berupa array');
        }

        $results = [];

        foreach ($jsonData as $item) {
            if (!isset($item['text']) || !is_string($item['text'])) continue;

            $text = trim($item['text']);
            if (empty($text)) continue;

            $sentiment = $item['sentiment'] ?? null;
            $probability = isset($item['probability']) ? floatval($item['probability']) : 0.8;

            $results[] = $this->sentimentAnalyzer->analyze($text, $sentiment, $probability);
        }

        return $results;
    }

    protected function processManualText($text)
    {
        $result = $this->sentimentAnalyzer->analyze($text);

        Sentiment::create([
            'user_id' => Auth::id(),
            'text' => $text,
            'sentiment' => $result['sentiment'],
            'probability' => $result['probability']
        ]);

        return $result;
    }

    public function dashboard()
    {
        $sentiments = Sentiment::where('user_id', Auth::id())->latest()->get();

        // Calculate sentiment counts
        $sentimentCounts = [
            'positive' => $sentiments->where('sentiment', 'positive')->count(),
            'neutral' => $sentiments->where('sentiment', 'neutral')->count(),
            'negative' => $sentiments->where('sentiment', 'negative')->count(),
        ];

        return view('sentiments.dashboard', compact('sentiments', 'sentimentCounts'));
    }

    public function history()
    {
        $sentiments = Sentiment::where('user_id', Auth::id())->latest()->get();
        return view('sentiments.history', compact('sentiments'));
    }

    public function edit($id)
    {
        $sentiment = Sentiment::where('user_id', Auth::id())->findOrFail($id);
        return view('sentiments.edit', compact('sentiment'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|min:3',
            'sentiment' => 'required|in:positive,negative,neutral',
            'probability' => 'required|numeric|min:0|max:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $sentiment = Sentiment::where('user_id', Auth::id())->findOrFail($id);
        $sentiment->text = $request->input('text');
        $sentiment->sentiment = $request->input('sentiment');
        $sentiment->probability = $request->input('probability');
        $sentiment->save();

        return redirect()->route('sentiments.history')->with('success', 'Sentiment updated successfully');
    }

    public function destroy($id)
    {
        $sentiment = Sentiment::where('user_id', Auth::id())->findOrFail($id);
        $sentiment->delete();

        return redirect()->route('sentiments.history')->with('success', 'Sentiment deleted successfully');
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,json',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        $success = 0;
        $failed = 0;

        if ($extension === 'csv') {
            // Process CSV file
            $handle = fopen($file->getPathname(), 'r');
            $header = fgetcsv($handle);

            // Validate headers
            $requiredColumns = ['text', 'sentiment'];
            $missingColumns = array_diff($requiredColumns, $header);

            if (!empty($missingColumns)) {
                return redirect()->back()->with('error', 'Invalid CSV file. Missing required columns: ' . implode(', ', $missingColumns));
            }

            $textIndex = array_search('text', $header);
            $sentimentIndex = array_search('sentiment', $header);
            $probabilityIndex = array_search('probability', $header);

            while (($data = fgetcsv($handle)) !== false) {
                try {
                    if (count($data) < max($textIndex, $sentimentIndex) + 1) {
                        $failed++;
                        continue;
                    }

                    $text = trim($data[$textIndex]);
                    $sentiment = strtolower(trim($data[$sentimentIndex]));

                    // Normalize sentiment value
                    if (in_array($sentiment, ['positive', 'positif', 'puas'])) {
                        $sentiment = 'positive';
                    } elseif (in_array($sentiment, ['negative', 'negatif', 'tidak puas'])) {
                        $sentiment = 'negative';
                    } else {
                        $sentiment = 'neutral';
                    }

                    // Get probability if available, otherwise use default
                    $probability = 0.8;
                    if ($probabilityIndex !== false && isset($data[$probabilityIndex])) {
                        $probValue = floatval($data[$probabilityIndex]);
                        if ($probValue >= 0 && $probValue <= 1) {
                            $probability = $probValue;
                        }
                    }

                    // Add to database with user_id
                    Sentiment::create([
                        'user_id' => Auth::id(),
                        'text' => $text,
                        'sentiment' => $sentiment,
                        'probability' => $probability,
                    ]);

                    $success++;
                } catch (\Exception $e) {
                    $failed++;
                }
            }

            fclose($handle);
        } elseif ($extension === 'json') {
            // Process JSON file
            $jsonData = json_decode(file_get_contents($file->getPathname()), true);

            if (!is_array($jsonData)) {
                return redirect()->back()->with('error', 'Invalid JSON file. Data must be an array.');
            }

            foreach ($jsonData as $item) {
                try {
                    if (!isset($item['text']) || !is_string($item['text'])) {
                        $failed++;
                        continue;
                    }

                    $sentiment = strtolower($item['sentiment'] ?? 'neutral');

                    // Normalize sentiment value
                    if (in_array($sentiment, ['positive', 'positif', 'puas'])) {
                        $sentiment = 'positive';
                    } elseif (in_array($sentiment, ['negative', 'negatif', 'tidak puas'])) {
                        $sentiment = 'negative';
                    } else {
                        $sentiment = 'neutral';
                    }

                    // Get probability if available, otherwise use default
                    $probability = 0.8;
                    if (isset($item['probability'])) {
                        $probValue = floatval($item['probability']);
                        if ($probValue >= 0 && $probValue <= 1) {
                            $probability = $probValue;
                        }
                    }

                    // Add to database with user_id
                    Sentiment::create([
                        'user_id' => Auth::id(),
                        'text' => $item['text'],
                        'sentiment' => $sentiment,
                        'probability' => $probability,
                    ]);

                    $success++;
                } catch (\Exception $e) {
                    $failed++;
                }
            }
        }

        return redirect()->route('sentiments.history')->with('import_stats', [
            'total' => $success + $failed,
            'success' => $success,
            'failed' => $failed,
        ]);
    }

    public function export()
    {
        $sentiments = Sentiment::where('user_id', Auth::id())->get();
        $filename = 'sentiment-data-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($sentiments) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['id', 'text', 'sentiment', 'probability', 'created_at']);

            // Add data
            foreach ($sentiments as $sentiment) {
                fputcsv($file, [
                    $sentiment->id,
                    $sentiment->text,
                    $sentiment->sentiment,
                    $sentiment->probability,
                    $sentiment->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print()
    {
        $sentiments = Sentiment::where('user_id', Auth::id())->latest()->get();

        // Calculate sentiment counts
        $sentimentCounts = [
            'positive' => $sentiments->where('sentiment', 'positive')->count(),
            'neutral' => $sentiments->where('sentiment', 'neutral')->count(),
            'negative' => $sentiments->where('sentiment', 'negative')->count(),
        ];

        return view('sentiments.print', compact('sentiments', 'sentimentCounts'));
    }
}
