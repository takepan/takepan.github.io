<?php
// エラー表示を有効化
error_reporting(E_ALL);
ini_set('display_errors', 1);

$f = '/Users/takeuchishinji/work/ownly/OWNLY_production/cflog-img-ssapp-jp/pop.log';
echo "Reading log file...\n";

// ログファイルの存在確認
if (!file_exists($f)) {
    die("Log file not found: " . $f . "\n");
}

$c = file($f);
echo "Found " . count($c) . " lines in log file\n";

$picts = [];
$videos = [];
$num = 0;

foreach ($c as $n => $l) {
    if ($num === 100) {
        echo "Reached 1000 items limit\n";
        break;
    }
    
    $parts = explode(' ', trim($l));
    if (count($parts) < 3) {
        echo "Invalid line format at line " . ($n + 1) . ": " . $l . "\n";
        continue;
    }
    
    list($cnt, $bytes, $url) = $parts;
    
    $num++;
    if (preg_match("/\.mp4$/", $url)) {
        $videos[] = ['url' => $url];
    } elseif (preg_match("/\.(jpe?g|gif|png|heic|webp)/i", $url)) {
        $picts[] = ['url' => $url];
    } else {
        //echo "Unrecognized file type: " . $url . "\n";
        continue;
    }
}
//var_dump($picts);
//exit;


//echo "Processed items: " . $num . "\n";
//echo "Found pictures: " . count($picts) . "\n";
//echo "Found videos: " . count($videos) . "\n";

// テンプレートファイルのパスを確認
$template_file = dirname(__FILE__) . '/cloudflare.tpl';
$output_file = dirname(__FILE__) . '/page.html';

//echo "Template file: " . $template_file . "\n";
//echo "Output file: " . $output_file . "\n";

// テンプレートファイルの存在確認
if (!file_exists($template_file)) {
    die("Template file not found: " . $template_file . "\n");
}

// テンプレートファイルの読み込み権限確認
if (!is_readable($template_file)) {
    die("Template file is not readable: " . $template_file . "\n");
}

// 出力ディレクトリの書き込み権限確認
$output_dir = dirname($output_file);
if (!is_writable($output_dir)) {
    die("Output directory is not writable: " . $output_dir . "\n");
}

// output-buffering開始
ob_start();

try {
    echo "Including template file...\n";
    // テンプレート読込
    include $template_file;
    
    //echo "Getting buffer contents...\n";
    // バッファの内容を取得
    $strOutput = ob_get_contents();
    
    if (empty($strOutput)) {
        //echo "Warning: Buffer is empty\n";
    } else {
        //echo "Buffer size: " . strlen($strOutput) . " bytes\n";
    }
    
    // バッファをクリアしてバッファリングを終了
    ob_end_clean();
    
    // ファイル書き込み
    //echo "Writing to output file...\n";
    if (file_put_contents($output_file, $strOutput) === false) {
        throw new Exception("Failed to write to file: " . $output_file);
    }
    
    echo "File successfully created: " . $output_file . "\n";
    echo "File size: " . filesize($output_file) . " bytes\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    // スタックトレースも表示
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
