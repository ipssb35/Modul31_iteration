
<?php
    /* Дан html-код, с помощью итераций вырежьте из него мета теги (title description, keywords). */

    class FileIterator
    {
        const ROW_SIZE = 4096;
        protected $filePointer = null;
        protected $currentElement = null;
        protected $rowCounter = null;

        // открывает файл
        public function __construct($file)
        {
            try {
                $this->filePointer = fopen($file, 'r+');
            } catch (\Exception $e) {
                throw new \Exception("$file cannot be read");
            }
        }

        // сбрасывает указатель файла
        public function rewind(): void
        {
            $this->rowCounter = 0;
            rewind($this->filePointer);
        }

        // возвращает текущую строку в виде массива
        public function current(): array
        {
            $line = fgets($this->filePointer, self::ROW_SIZE);

            if (!preg_match('/<meta.*(keyword|description)/i', $line)) {
                $this->currentElement[] = $line;
            }

            return $this->currentElement;
        }

        // возвращает номер текущуй строки
        public function key(): int
        {
            return $this->rowCounter;
        }

        // достигнут ли конец файла
        public function next(): bool
        {
            if (is_resource($this->filePointer)) {
                return !feof($this->filePointer);
            }

            return false;
        }

        // является ли следующая строка допустимой
        public function valid(): bool
        {
            if (!$this->next()) {
                if (is_resource($this->filePointer)) {
                    fclose($this->filePointer);
                }

                return false;
            }

            return true;
        }
    }

    $file = __DIR__ . '/file.php';
    $fileIterator = new FileIterator($file);

    foreach ($fileIterator as $key => $value) {
        print_r($value);
    }