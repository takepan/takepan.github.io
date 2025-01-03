<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メディアギャラリー</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
            background-color: #f0f0f0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .media-item {
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .media-item img {
            width: 100%;
            height: auto;
            border-radius: 4px;
        }

        .media-item video {
            width: 100%;
            height: auto;
            border-radius: 4px;
        }

        h2 {
            margin: 30px 0 20px;
            color: #444;
        }

        .caption {
            margin-top: 8px;
            font-size: 14px;
            color: #666;
            text-align: center;
        }

        @media (max-width: 600px) {
            .gallery {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>メディアギャラリー</h1>

        <h2>画像ギャラリー</h2>
        <div class="gallery">
            <!-- 画像ギャラリー -->
            <?php foreach ($picts as $pict): ?>
            <div class="media-item">
                <img src="https://img.ownly.biz<?php echo $pict['url']; ?>" alt="画像 1">
                <div class="caption"><?php echo $pict['url']; ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <h2>動画ギャラリー</h2>
        <div class="gallery">
            <!-- 動画ギャラリー -->
            <?php foreach ($videos as $video): ?>
            <div class="media-item">
                <video controls>
                    <source src="https://img.ownly.biz<?php echo $video['url']; ?>" type="video/mp4">
                    お使いのブラウザは動画再生に対応していません。
                </video>
                <div class="caption">動画 1 の説明</div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // 動画の遅延読み込みを実装（パフォーマンス向上のため）
        document.addEventListener('DOMContentLoaded', function() {
            const videos = document.querySelectorAll('video');
            const options = {
                root: null,
                rootMargin: '50px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const video = entry.target;
                        const sources = video.getElementsByTagName('source');
                        const src = sources[0].dataset.src;
                        if (src) {
                            sources[0].src = src;
                            video.load();
                            observer.unobserve(video);
                        }
                    }
                });
            }, options);

            videos.forEach(video => {
                observer.observe(video);
            });
        });
    </script>
</body>
</html>
