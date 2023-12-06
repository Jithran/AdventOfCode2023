<?php
echo '<pre>';

$input = file_get_contents('input.txt');
$parts = explode("\r\n\r\n", $input);

$inputs = array_map('intval', array_values(array_filter(explode(' ', trim(explode(':', $parts[0])[1])))));

$seeds = [];
for ($i = 0; $i < count($inputs); $i += 2) {
    $seeds[] = [$inputs[$i], $inputs[$i] + $inputs[$i + 1]];
}

array_shift($parts); // Remove the first element (inputs)
foreach ($parts as $block) {
    $ranges = [];
    $lines = explode("\r\n", $block);
    array_shift($lines); // Skip the first line

    foreach ($lines as $line) {
        $ranges[] = array_map('intval', explode(' ', $line));
    }


    $new = [];
    while (count($seeds) > 0) {
        [$start, $end] = array_pop($seeds);
        foreach ($ranges as [$dest, $src, $range]) {
            $overlapStart = max($start, $src);
            $overlapEnd = min($end, $src + $range);
            if ($overlapStart < $overlapEnd) {
                $new[] = [$overlapStart - $src + $dest, $overlapEnd - $src + $dest];
                if ($overlapStart > $start) {
                    $seeds[] = [$start, $overlapStart];
                }
                if ($end > $overlapEnd) {
                    $seeds[] = [$overlapEnd, $end];
                }
                break;
            }
        }

        if ($start == $overlapStart && $end == $overlapEnd) {
            $new[] = [$start, $end];
        }
    }

    $seeds = $new;
}

echo(min($seeds)[1]);
