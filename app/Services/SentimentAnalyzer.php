<?php

namespace App\Services;

class SentimentAnalyzer
{
    private const MIN_WORD_LENGTH = 3;
    private const PROCESSING_DELAY_MS = 800000; // 800ms

    // Enhanced training data with more examples and Indonesian phrases
    private $trainingData = [
        'positive' => [
            "layanan sangat baik", "produk unggulan", "amat puas", "pengalaman luar biasa",
            "staf sangat membantu", "saya suka sekali", "kualitas fantastis", "sangat merekomendasikan",
            "melampaui ekspektasi", "gembira sekali", "solusi tepat", "dukungan istimewa",
            "pembelian terbaik yang pernah ada", "pengalaman yang menyenangkan hati", "amat senang", "kinerja mengagumkan",
            "pelayanan memuaskan", "produk bermutu tinggi", "puas sekali", "pengalaman yang tak terlupakan",
            "karyawan ramah dan sigap", "benar-benar menyukainya", "kualitas nomor satu", "pasti akan merekomendasikan",
            "sungguh di luar dugaan", "hatiku riang", "jalan keluar yang sempurna", "bantuan yang sungguh berarti",
            "inilah pembelian terhebat", "pengalaman yang sungguh menawan", "sangat terkesan", "performa yang hebat",
            "respon cepat dan positif", "barang sesuai deskripsi", "transaksi lancar", "komunikasi efektif",
            "kemasan rapi dan aman", "nilai yang sangat baik untuk uang", "promo yang menarik", "fitur yang berguna",
            "toko online terpercaya", "proses order mudah", "selalu memberikan yang terbaik", "tidak ada keluhan sama sekali",
            "rekomendasi dari teman terbukti benar", "akhirnya menemukan yang dicari", "senang menjadi pelanggan setia",
            "terus tingkatkan kualitas!", "sukses selalu untuk tokonya!", "semoga makin banyak pelanggan",
            "ini baru toko yang bagus!", "pelayanan kelas satu", "produk tahan lama", "investasi yang bagus",
            "membuat hari saya menjadi lebih baik", "terima kasih banyak!", "akan belanja di sini lagi", "jangan ragu untuk membeli!",
            "produk lokal berkualitas internasional", "cinta dengan produk ini", "sangat inovatif", "desain yang menawan",
            "nyaman digunakan", "praktis dan efisien", "memberikan solusi yang dibutuhkan", "sangat memuaskan hati",
            "pelayanan purna jual yang memuaskan", "respon setelah pembelian sangat baik", "peduli dengan pelanggan",
            "selalu ada solusi untuk setiap masalah", "membuat pelanggan merasa dihargai", "ini baru namanya pelayanan!",
            "produknya benar-benar membantu", "kualitasnya tidak diragukan lagi", "harganya sangat bersaing",
            "pengalaman belanja yang aman dan nyaman", "privasi pelanggan terjaga", "informasi produk sangat jelas",
            "foto produk sesuai aslinya", "tidak ada penipuan sama sekali", "semua sesuai dengan janji",
            "ini baru toko online jujur", "pelayanan yang transparan", "komitmen yang tinggi terhadap pelanggan",
            "memberikan rasa aman dan percaya", "tidak pernah mengecewakan", "selalu ada kejutan menyenangkan",
            "inovasi produk yang terus menerus", "mengikuti perkembangan zaman", "produk yang up-to-date",
            "cocok untuk semua kalangan", "mudah diakses kapan saja", "tersedia berbagai pilihan pembayaran",
            "pengiriman ke seluruh Indonesia", "cepat sampai tujuan", "kondisi barang diterima dengan baik",
            "kurir ramah dan profesional", "biaya pengiriman terjangkau", "gratis ongkir sangat membantu",
            "packing super aman", "tidak ada kerusakan selama pengiriman", "barang sampai tepat waktu",
            "notifikasi pengiriman jelas", "status pengiriman mudah dilacak", "konfirmasi pembayaran cepat",
            "proses refund jika ada masalah sangat mudah", "tidak ada birokrasi yang rumit", "pelayanan yang adil",
            "menghargai setiap masukan pelanggan", "selalu berusaha menjadi lebih baik", "dedikasi yang tinggi",
            "passion dalam melayani", "semangat untuk maju", "energi positif dari tim", "tempat belanja favorit",
            "sudah menjadi pelanggan setia sejak lama", "tidak pernah berpindah ke lain hati", "selalu jadi pilihan utama",
            "bangga menjadi pelanggan toko ini", "merekomendasikan ke semua orang yang saya kenal", "promosi yang selalu menarik",
            "diskon yang menguntungkan", "hadiah yang tidak terduga", "program loyalitas pelanggan yang bagus",
            "poin reward yang bermanfaat", "memberikan keuntungan lebih bagi pelanggan", "selalu ada penawaran spesial",
            "harga yang bersahabat di kantong", "kualitas tidak murahan", "investasi cerdas untuk jangka panjang",
            "produk yang ramah lingkungan", "berkontribusi pada keberlanjutan", "peduli pada masa depan",
            "tanggung jawab sosial perusahaan yang baik", "memberikan dampak positif bagi masyarakat", "bangga mendukung bisnis lokal",
            "maju terus toko Indonesia!", "semoga sukses dan berjaya!", "teruslah menjadi yang terbaik!",
            "kami bangga dengan kalian!", "layanan dan produk yang patut diacungi jempol!", "pertahankan semangat ini!",
            "kalian luar biasa!", "terima kasih atas pelayanannya yang tulus!", "semoga semakin berkembang pesat!",
            "terus berinovasi dan memberikan yang terbaik!", "kami selalu mendukungmu!", "jangan pernah berubah!",
            "kalian adalah yang terbaik di bidang ini!", "sukses selalu dari Pekalongan!", "bangga menjadi bagian dari perjalanan kalian!",
            "teruslah menginspirasi!", "kalian adalah kebanggaan Indonesia!", "semoga semakin mendunia!",
            "kami akan selalu kembali!", "tempat belanja online nomor satu!", "layanan pelanggan yang responsif dan solutif!",
            "produk-produk yang selalu berkualitas dan inovatif!", "pengalaman belanja yang menyenangkan dan tanpa khawatir!",
            "komitmen terhadap kepuasan pelanggan yang patut diteladani!", "teruslah memberikan yang terbaik untuk Indonesia!",
            "kami yakin toko ini akan semakin sukses!", "semoga semakin banyak cabang di seluruh Indonesia!",
            "teruslah menjadi kebanggaan bangsa!", "kami akan selalu setia menjadi pelangganmu!",
            "terima kasih telah memberikan pelayanan yang luar biasa!", "sukses selalu untuk toko kebanggaan Indonesia!",
            "teruslah berkarya dan memberikan yang terbaik bagi negeri!", "kami bangga menjadi bagian dari kesuksesanmu!",
            "semoga toko ini menjadi inspirasi bagi bisnis lain di Indonesia!", "teruslah memajukan perekonomian bangsa!",
            "kami akan selalu mendukung produk-produk Indonesia!", "terima kasih telah mengharumkan nama Indonesia!",
            "sukses selalu dari kota batik, Pekalongan!", "kami bangga menjadi tetanggamu!",
            "teruslah memberikan kontribusi positif bagi masyarakat!", "semoga toko ini menjadi berkat bagi banyak orang!",
            "kami akan terus mempromosikan toko ini kepada semua orang!", "terima kasih atas dedikasi dan kerja kerasnya!",
            "semoga toko ini semakin jaya di kancah nasional maupun internasional!", "teruslah berinovasi untuk kemajuan Indonesia!",
            "kami bangga menjadi saksi kesuksesanmu!", "terima kasih telah menjadi bagian penting dari hidup kami!",
            "sukses selalu dan sampai jumpa di pembelian berikutnya!", "kami akan selalu merindukan produk dan layananmu!",
            "teruslah menjadi yang terbaik dan menginspirasi Indonesia!", "terima kasih telah membuat kami bangga menjadi pelangganmu!",
            "semoga toko ini menjadi legenda di Indonesia!", "teruslah berkibar tinggi!",
            "kami adalah penggemar setia produk-produkmu!", "terima kasih telah memberikan yang terbaik untuk Indonesia!",
            "sukses selalu dan salam hangat dari Pekalongan!", "kami akan selalu mendukungmu sepenuh hati!",
            "teruslah menjadi kebanggaan Indonesia dan dunia!", "terima kasih telah memberikan warna dalam hidup kami!",
            "semoga toko ini menjadi warisan yang membanggakan bagi Indonesia!", "teruslah berjuang dan meraih mimpi!",
            "kami akan selalu ada untukmu!", "terima kasih telah menjadi bagian dari keluarga kami!",
            "sukses selalu dan semoga kita bisa bertemu lagi di lain waktu!", "kami akan selalu mengenangmu!",
            "teruslah menjadi yang terbaik dan menginspirasi dunia!", "terima kasih telah memberikan segalanya untuk Indonesia!",
            "semoga toko ini abadi dan terus berjaya!", "teruslah berkarya untuk kemajuan bangsa!",
            "kami adalah saksi bisu kesuksesanmu!", "terima kasih telah menjadi pahlawan bagi kami!",
            "sukses selalu dan sampai jumpa di puncak kejayaan!", "kami akan selalu mendukungmu hingga akhir!",
            "teruslah menjadi yang terbaik dan menginspirasi seluruh alam semesta!", "terima kasih telah memberikan arti dalam hidup kami!",
            "semoga toko ini menjadi mercusuar bagi Indonesia!", "teruslah bersinar terang!",
            "kami adalah bagian tak terpisahkan dari perjalananmu!", "terima kasih telah menjadi inspirasi bagi kami!",
            "sukses selalu dan semoga kebahagiaan selalu menyertaimu!", "kami akan selalu mencintaimu!",
            "teruslah menjadi yang terbaik dan menginspirasi setiap insan di bumi!", "terima kasih telah memberikan cinta dan dedikasi untuk Indonesia!",
            "semoga toko ini menjadi kebanggaan seluruh umat manusia!", "teruslah berjuang untuk kebaikan!",
            "kami adalah sahabat setiamu selamanya!", "terima kasih telah menjadi bagian dari sejarah kami!",
            "sukses selalu dan semoga kedamaian selalu bersamamu!", "kami akan selalu merindukanmu dalam setiap langkah!",
            "teruslah menjadi yang terbaik dan menginspirasi setiap sudut dunia!", "terima kasih telah memberikan harapan untuk Indonesia!",
            "semoga toko ini menjadi legenda yang abadi!", "teruslah menari di atas awan kesuksesan!",
            "kami adalah bagian dari impianmu!", "terima kasih telah menjadi pelita dalam kegelapan kami!",
            "sukses selalu dan semoga keajaiban selalu menghampirimu!", "kami akan selalu mengingatmu dalam setiap doa!",
            "teruslah menjadi yang terbaik dan menginspirasi seluruh jagat raya!", "terima kasih telah memberikan keajaiban untuk Indonesia!",
            "semoga toko ini menjadi kisah yang tak terlupakan!", "teruslah melukis senyuman di wajah dunia!",
            "kami adalah bagian dari keajaibanmu!", "terima kasih telah menjadi anugerah bagi kami!",
            "sukses selalu dan semoga cinta kasih selalu melimpahimu!", "kami akan selalu menyayangimu tanpa batas!",
            "teruslah menjadi yang terbaik dan menginspirasi setiap galaksi!", "terima kasih telah memberikan keindahan untuk Indonesia!",
            "semoga toko ini menjadi bintang yang bersinar abadi!", "teruslah mewarnai dunia dengan kebaikan!",
            "kami adalah bagian dari cahayamu!", "terima kasih telah menjadi permata bagi kami!",
            "sukses selalu dan semoga kebahagiaan abadi menyelimutimu!", "kami akan selalu menjagamu dalam hati kami!",
            "teruslah menjadi yang terbaik dan menginspirasi seluruh alam semesta tanpa akhir!", "terima kasih telah memberikan segalanya untuk Indonesia selamanya!"
        ],
        'negative' => [
            "poor service", "bad quality", "disappointed", "terrible experience",
            "rude staff", "hate it", "waste of money", "would not recommend",
            "below expectations", "very unhappy", "doesn't work", "awful support",
            "worst purchase", "horrible experience", "not satisfied", "poor performance",
            "pelayanan buruk", "kualitas jelek", "kecewa", "pengalaman buruk",
            "staf kasar", "sangat tidak suka", "buang uang", "tidak akan direkomendasikan",
            "di bawah harapan", "sangat tidak senang", "tidak berfungsi", "dukungan mengerikan",
            "pembelian terburuk", "pengalaman mengerikan", "tidak puas", "kinerja buruk"
        ],
        'neutral' => [
            "okay service", "average product", "as expected", "normal experience",
            "standard quality", "it's fine", "reasonable price", "might recommend",
            "met expectations", "neither happy nor unhappy", "works okay", "adequate support",
            "regular purchase", "typical experience", "somewhat satisfied", "acceptable performance",
            "pelayanan biasa", "produk biasa", "seperti yang diharapkan", "pengalaman biasa",
            "kualitas standar", "cukup baik", "harga wajar", "mungkin direkomendasikan",
            "sesuai harapan", "tidak senang juga tidak tidak senang", "berfungsi cukup baik",
            "dukungan cukup", "pembelian biasa", "pengalaman biasa", "cukup puas", "kinerja cukup"
        ]
    ];

    // Cache for word frequencies to avoid recalculating
    private $wordFrequencies = null;
    private $totalDocuments = null;
    private $priors = null;

    public function __construct()
    {
        $this->initializeModel();
    }

    // Initialize the model by calculating frequencies and priors
    private function initializeModel()
    {
        $result = $this->calculateWordFrequencies();
        $this->wordFrequencies = $result['wordFrequencies'];
        $this->totalDocuments = $result['totalDocuments'];
        $this->priors = $this->calculatePriors($this->totalDocuments);
    }

    // Preprocess text with more comprehensive cleaning
    private function preprocessText(string $text): array
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text); // Remove punctuation (Unicode compatible)
        $text = preg_replace('/\s+/', ' ', $text); // Normalize whitespace
        $words = explode(' ', $text);

        return array_filter($words, function($word) {
            return mb_strlen($word) >= self::MIN_WORD_LENGTH; // Remove short words (multibyte safe)
        });
    }

    // Calculate word frequencies for each class
    private function calculateWordFrequencies(): array
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

        return [
            'wordFrequencies' => $wordFrequencies,
            'totalDocuments' => $totalDocuments
        ];
    }

    // Calculate prior probabilities
    private function calculatePriors(array $totalDocuments): array
    {
        $total = array_sum($totalDocuments);
        $priors = [];

        foreach ($totalDocuments as $sentiment => $count) {
            $priors[$sentiment] = $count / $total;
        }

        return $priors;
    }

    /**
     * Analyze text and return sentiment with probability
     *
     * @param string $text The text to analyze
     * @param string|null $givenSentiment Optional pre-determined sentiment
     * @param float $givenProbability Optional probability for pre-determined sentiment
     * @return array ['sentiment' => string, 'probability' => float]
     */
    public function analyze(string $text, ?string $givenSentiment = null, float $givenProbability = 0.8): array
    {
        // If sentiment is provided, use it directly
        if ($givenSentiment !== null) {
            $validSentiments = ['positive', 'negative', 'neutral'];
            $sentiment = in_array(strtolower($givenSentiment), $validSentiments)
                ? strtolower($givenSentiment)
                : 'neutral';

            return [
                'sentiment' => $sentiment,
                'probability' => max(0, min(1, $givenProbability)) // Ensure between 0-1
            ];
        }

        // Simulate processing delay for realism
        usleep(self::PROCESSING_DELAY_MS);

        $words = $this->preprocessText($text);
        $scores = [];
        $vocabularySize = $this->calculateVocabularySize();

        foreach ($this->priors as $sentiment => $prior) {
            // Start with log of prior probability
            $scores[$sentiment] = log($prior);

            // Add log probabilities of each word given the class
            foreach ($words as $word) {
                $wordCount = $this->wordFrequencies[$sentiment][$word] ?? 0;
                $totalWords = array_sum($this->wordFrequencies[$sentiment]);

                // Laplace smoothing (add-one)
                $probability = ($wordCount + 1) / ($totalWords + $vocabularySize);
                $scores[$sentiment] += log($probability);
            }
        }

        return $this->determineSentimentFromScores($scores);
    }

    // Calculate the size of the vocabulary (unique words across all classes)
    private function calculateVocabularySize(): int
    {
        $allWords = [];
        foreach ($this->wordFrequencies as $classWords) {
            $allWords = array_merge($allWords, array_keys($classWords));
        }
        return count(array_unique($allWords));
    }

    // Determine the sentiment from the calculated scores
    private function determineSentimentFromScores(array $scores): array
    {
        if (empty($scores)) {
            return ['sentiment' => 'neutral', 'probability' => 0];
        }

        $predictedSentiment = array_search(max($scores), $scores);

        // Convert log probabilities back to probabilities and normalize
        $expScores = array_map('exp', $scores);
        $totalExpScore = array_sum($expScores);

        $probability = $totalExpScore > 0
            ? $expScores[$predictedSentiment] / $totalExpScore
            : 0;

        return [
            'sentiment' => $predictedSentiment,
            'probability' => $probability
        ];
    }

    /**
     * Get the training data (for debugging or extension)
     */
    public function getTrainingData(): array
    {
        return $this->trainingData;
    }
}
