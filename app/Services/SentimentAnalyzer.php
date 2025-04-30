<?php

namespace App\Services;

class SentimentAnalyzer
{
    // Training data
    private $trainingData = [
        'positive' => [
            "great service",
            "excellent product",
            "very satisfied",
            "amazing experience",
            "helpful staff",
            "love it",
            "fantastic quality",
            "highly recommend",
            "exceeded expectations",
            "very happy",
            "perfect solution",
            "outstanding support",
            "best purchase",
            "wonderful experience",
            "very pleased",
            "impressive performance",
        ],
        'negative' => [
            "poor service",
            "bad quality",
            "disappointed",
            "terrible experience",
            "rude staff",
            "hate it",
            "waste of money",
            "would not recommend",
            "below expectations",
            "very unhappy",
            "doesn't work",
            "awful support",
            "worst purchase",
            "horrible experience",
            "not satisfied",
            "poor performance",
        ],
        'neutral' => [
            "okay service",
            "average product",
            "as expected",
            "normal experience",
            "standard quality",
            "it's fine",
            "reasonable price",
            "might recommend",
            "met expectations",
            "neither happy nor unhappy",
            "works okay",
            "adequate support",
            "regular purchase",
            "typical experience",
            "somewhat satisfied",
            "acceptable performance",
        ],
    ];

    // Preprocess text
    private function preprocessText($text)
    {
        $text = strtolower($text);
        $text = preg_replace('/[^\w\s]/', '', $text);
        $words = preg_split('/\s+/', $text);
        return array_filter($words, function($word) {
            return strlen($word) > 2; // Remove very short words
        });
    }

    // Calculate word frequencies for each class
    private function calculateWordFrequencies()
    {
        $wordFrequencies = [
            'positive' => [],
            'negative' => [],
            'neutral' => [],
        ];

        $totalDocuments = [
            'positive' => count($this->trainingData['positive']),
            'negative' => count($this->trainingData['negative']),
            'neutral' => count($this->trainingData['neutral']),
        ];

        // Count word occurrences in each class
        foreach ($this->trainingData as $sentiment => $documents) {
            foreach ($documents as $doc) {
                $words = $this->preprocessText($doc);
                foreach ($words as $word) {
                    if (!isset($wordFrequencies[$sentiment][$word])) {
                        $wordFrequencies[$sentiment][$word] = 0;
                    }
                    $wordFrequencies[$sentiment][$word]++;
                }
            }
        }

        return ['wordFrequencies' => $wordFrequencies, 'totalDocuments' => $totalDocuments];
    }

    // Calculate prior probabilities
    private function calculatePriors($totalDocuments)
    {
        $total = array_sum($totalDocuments);
        $priors = [];

        foreach ($totalDocuments as $sentiment => $count) {
            $priors[$sentiment] = $count / $total;
        }

        return $priors;
    }

    // Analyze text using Naive Bayes
    public function analyzeText($text)
    {
        // Simulate a delay to make it feel like processing is happening
        usleep(800000); // 800ms delay

        $result = $this->calculateWordFrequencies();
        $wordFrequencies = $result['wordFrequencies'];
        $totalDocuments = $result['totalDocuments'];
        $priors = $this->calculatePriors($totalDocuments);

        $words = $this->preprocessText($text);
        $scores = [];

        // Calculate score for each sentiment class
        foreach (array_keys($priors) as $sentiment) {
            // Start with log of prior probability
            $scores[$sentiment] = log($priors[$sentiment]);

            // Add log probabilities of each word given the class
            foreach ($words as $word) {
                // Laplace smoothing (add-one) to handle unseen words
                $wordCount = isset($wordFrequencies[$sentiment][$word]) ? $wordFrequencies[$sentiment][$word] : 0;
                $totalWords = array_sum($wordFrequencies[$sentiment]);
                
                // Calculate vocabulary size
                $allWords = [];
                foreach ($wordFrequencies as $classWords) {
                    $allWords = array_merge($allWords, array_keys($classWords));
                }
                $vocabularySize = count(array_unique($allWords));

                // Calculate probability with smoothing
                $probability = ($wordCount + 1) / ($totalWords + $vocabularySize);
                $scores[$sentiment] += log($probability);
            }
        }

        // Find the sentiment with the highest score
        $maxScore = PHP_FLOAT_MIN;
        $predictedSentiment = "";

        foreach ($scores as $sentiment => $score) {
            if ($score > $maxScore) {
                $maxScore = $score;
                $predictedSentiment = $sentiment;
            }
        }

        // Calculate a confidence score (normalized probability)
        // Convert log probabilities back to probabilities and normalize
        $expScores = [];
       foreach ($scores as $sentiment => $score) {
    $expScores[$sentiment] = exp($score);
}

// Handle jika $expScores kosong
if (empty($expScores)) {
    return [
        'sentiment' => 'neutral',  // Nilai default
        'probability' => 0
    ];
}

// Pastikan key $predictedSentiment ada di $expScores
if (!isset($expScores[$predictedSentiment])) {
    return [
        'sentiment' => 'unknown',  // Handle error
        'probability' => 0
    ];
}

$totalExpScore = array_sum($expScores);
$probability = $expScores[$predictedSentiment] / $totalExpScore;

return [
    'sentiment' => $predictedSentiment,
    'probability' => $probability
];
    }
}