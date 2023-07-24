<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class LanguageModel {
    private $models = [];
    private $n;
    private $vocabSize;

    function __construct($n = 3) {
        $this->n = $n;
        $this->vocabSize = 0; //unique words in vocabulary
    }

    function train($filePath) {
        $text = file_get_contents($filePath);
        $text = strtolower($text);
        $paragraphs = preg_split('/\n+/', $text);

        $this->vocabSize = count(array_unique(str_word_count($text, 1)));
        
        for ($n = $this->n; $n > 0; $n--) {
            $this->models[$n] = [];

            foreach ($paragraphs as $paragraph) {
                $words = preg_split('/\s+|(?<=[.!?])|(?=[.!?])/u', $paragraph, -1, PREG_SPLIT_NO_EMPTY);
                
                $start = array_fill(0, $n - 1, '<start>');
                $end = array_fill(0, $n - 1, '<end>');
                $words = array_merge($start, $words, $end);
                
                for ($i = 0; $i < count($words) - $n + 1; $i++) {
                    $slice = array_slice($words, $i, $n);
                    $key = implode(' ', array_slice($slice, 0, $n - 1));
                    $word = $slice[$n - 1];
                    
                    if (!isset($this->models[$n][$key])) {
                        $this->models[$n][$key] = [];
                    }
                    if (!isset($this->models[$n][$key][$word])) {
                        $this->models[$n][$key][$word] = 0;
                    }
                    $this->models[$n][$key][$word]++;
                }
            }

            foreach ($this->models[$n] as $key => $next_words) {
                $total = array_sum($next_words);
                foreach ($next_words as $word => $freq) {
                    $this->models[$n][$key][$word] = round((($freq + 1) / ($total + $this->vocabSize))*100,6);
                }
            }
        }
    }

    function saveModel($filePath) {
        file_put_contents($filePath, serialize($this->models));
    }

    function loadModel($filePath) {
        $this->models = unserialize(file_get_contents($filePath));
    }

    function getNextWord($key, $n) {
        if (!isset($this->models[$n][$key])) {
            return null;
        }
    
        $next_words = $this->models[$n][$key];
        if (count($next_words) == 1) { //avoid exact phrases
            return null;
        }
        arsort($next_words);
        $top_words = array_slice($next_words, 0, 3, true); // random first 3 words
    
        $prob_diff = max($top_words) - min($top_words);
    
        if ($prob_diff < 0.05) {
            $word_keys = array_keys($top_words);
            $selected_word = $word_keys[array_rand($word_keys)];
            
            return $selected_word;
        } else {
            $prob_sum = array_sum($next_words);
            $rand = mt_rand() / mt_getrandmax() * $prob_sum;
            $accum = 0;
            foreach ($next_words as $word => $prob) {
                $accum += $prob;
                if ($accum >= $rand) {
                    return $word;
                }
            }
        }
    
        return null;
    }
    
    function generateSentence($start, $length) {
        $sentence = explode(' ', $start);
        for ($i = count($sentence); $i < $length; $i++) {
            $n = $this->n;
            $next_word = null;

            while ($next_word === null && $n > 0) {
                $key = implode(' ', array_slice($sentence, $i - $n + 1, $n - 1));
                $next_word = $this->getNextWord($key, $n);
                $n--;
            }

            if ($next_word === null) {
                break;
            }
            $sentence[] = $next_word;//add next word to sentence
        }
        return implode(' ', $sentence);
    }
}

$ngrams = 6;

// Usage
if (!file_exists('trained')) {
    echo "creating file 'trained'";
    echo "<br>";
    echo "<br>";

    $lm = new LanguageModel($ngrams);
    $lm->train('train-input.txt');
    $lm->saveModel('trained');
}

$lm = new LanguageModel($ngrams);
$lm->loadModel('trained');
echo $lm->generateSentence('voldemort', 120);
?>