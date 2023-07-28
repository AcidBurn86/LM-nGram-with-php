# LLM-with-php
This project is an easy and educational implementation of a language model in PHP, specifically based on a [Markov Chain](https://en.wikipedia.org/wiki/Markov_chain). In simple terms, a Markov Chain involves predicting the next step in a sequence based only on the current step, without taking into account the history of previous steps. In the case of a language model, this means predicting the next word based on the preceding words.

This repository contains an educational implementation of a language model in PHP. The code demonstrates how to construct, train, and use an n-gram language model for text generation. 

It's a simple but good example for understanding the basics of Natural Language Processing (NLP) and how statistical language models work. 

The model is trained on a text file with the 7 books of Harry Potter, serialized for future use, and then used to generate new sentences based on the probabilities of word sequences in the training data.

# How to use
First extend memory_limit in your php.ini to 10gb aprox.
and also max_execution_time to 300 seconds.

In the first run if there is no "trained" file in the directory it will train the model, it could take some time, depending on your hardware.

You can adjust the value of the **$ngrams** variable prior to running the model. 

This value determines the size of the word sequences (n-grams) the model uses for training and prediction, based on the preceding word. Please note that the number of possibilities grows exponentially with the value of n-grams, so caution is advised when setting this variable. 

A value of 6 is usually sufficient, while a higher value, like 15, might cause the program to freeze.

**Trained File Example**

```[
  "harry potter" => [
    "was" => 0.24,
    "is" => 0.18,
    "and" => 0.12,
    // other words and their probabilities
  ],
  "professor dumbledore" => [
    "was" => 0.35,
    "said" => 0.25,
    // other words and their probabilities
  ],
  // other n-grams and their following words with probabilities
]
```

**Examples of generated text:**

_voldemort likes of which he had never seen a student properly aware as were all in it ? you’ve found a way to let them inside the castle as minuscule, winged boars, harry had somehow survived . one was the thought struck him as though he were your mother and father’s . “you don’t know how weird thing where to go . he glanced over at her; she was still disapproving of the moment . he pressed on, a nonexistent breeze . “perfect ! ” shrieked professor trelawney was standing there . “good luck, ron,” said hagrid loudly . “hello,” she said . “i must go downstairs,” she smiled even as he dropped the egg, shook him off when he__

_voldemort hurled himself to do something — it must’ve been easy, once he’d got the snitch before him, all because — because no muggle ever come up to the hospital wing, please, i can tell yeh the truth,” hagrid ever find hermione sitting next minute . “well, it’s best if you can . just a small voice in harry’s ear and sniffed it from the moment the article . hermione had come again, screaming and lying waiting . ”her eyes lingered on harry in this,” said harry . “fetching,” ? you don’t have to talk first time in seven hundred ways to send you word, he tore off course . “gotcha that fred and george had been gone ten minutes_
