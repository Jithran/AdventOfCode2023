<?php
echo '<pre>';

$input = file_get_contents('input.txt');
$input = trim($input);

$parts = explode("\r\n\r\n", $input);

$seed = array_map('intval', explode(' ', trim(explode(':', array_shift($parts))[1])));

$partsInstances = [];
foreach ($parts as $part) {
    $partsInstances[] = new Part($part);
}

$P1 = [];
foreach ($seed as $x) {
    foreach ($partsInstances as $instance) {
        $x = $instance->apply_one($x);
    }
    $P1[] = $x;
}

echo min($P1) . "\r\n";

$P2 = [];
if (count($seed) % 2 == 0) {
    for ($i = 0; $i < count($seed); $i += 2) {
        $st = $seed[$i];
        $sz = $seed[$i + 1];
        $range = [[$st, $st + $sz]];
        foreach ($partsInstances as $instance) {
            $range = $instance->apply_range($range);
        }
        if (count($range) > 0) {
            $P2[] = $range;
        }
    }
}

$lowestVal = PHP_INT_MAX;
foreach($P2 AS $item) {
    foreach($item AS $item2) {
        if($item2[0] < $lowestVal) {
            $lowestVal = $item2[0];
        }
    }
}

echo $lowestVal . "\r\n";



class Part {
    public $values = [];

    public function __construct($part) {
        $lines = explode("\r\n", $part);
        array_shift($lines);  // Remove first line (name)
        foreach ($lines as $line) {
            $this->values[] = array_map('intval', explode(' ', $line));
        }
    }

    public function apply_one($seed) {
        foreach ($this->values as $value) {
            list($dst, $src, $count) = $value;
            if ($src <= $seed && $seed < $src + $count) {
                return $seed + $dst - $src;
            }
        }
        return $seed;
    }

    public function apply_range($setOfRanges): array
    {
        $addition = [];
        foreach ($this->values as $value) {
            list($dest, $src, $count) = $value;

            $src_end = $src + $count;
            $newRange = [];
            while ($setOfRanges) {
                $range = array_pop($setOfRanges);
                list($start, $end) = $range;

                $before = [$start, min($end, $src)];
                $inter = [max($start, $src), min($src_end, $end)];
                $after = [max($src_end, $start), $end];

                if ($before[1] > $before[0]) {
                    $newRange[] = $before;
                }
                if ($inter[1] > $inter[0]) {
                    $addition[] = [$inter[0] - $src + $dest, $inter[1] - $src + $dest];
                }
                if ($after[1] > $after[0]) {
                    $newRange[] = $after;
                }
            }
            $setOfRanges = $newRange;
        }
        return array_merge($addition, $setOfRanges);
    }
}