<?php
$f = '/Users/takeuchishinji/work/ownly/OWNLY_production/cflog-img-ssapp-jp/popu.log';

$fp = fopen($f, 'r');

if ($fp){
    if (flock($fp, LOCK_SH)){
        while (!feof($fp)) {
            $buffer = fgets($fp);
            $line = explode(' ', $buffer);
           
            if (count($line) === 3) {
                if (preg_match("/source\/$/", trim($line[2]))) {
                    echo $line[2];
                    exit;
                }
                //printf("%d %d %s\n", $line[0], $line[1], trim($line[2]));
                $ext = substr(trim($line[2]), strpos($line[2], '.'));
                $pos_q = strrpos($ext, '?');
                //echo $pos_q . PHP_EOL;
                if ($pos_q !== false) {
                    $ext = substr($ext, 0, $pos_q);
                }
                //echo substr($line[2], strrpos($line[2], '.')) . PHP_EOL;
                //echo strrpos($line[2], '?') . PHP_EOL;
                echo trim($line[2]), ' ', $ext . PHP_EOL;
            }
        }

        flock($fp, LOCK_UN);
    }else{
        print('ファイルロックに失敗しました');
    }
}

fclose($fp);

