<?php
$currentDir = __DIR__;
$files = scandir($currentDir);
sort($files);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasiku - File Browser</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .path {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
            font-weight: 500;
        }
        .file-list {
            display: grid;
            gap: 10px;
        }
        .file-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .file-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        .file-icon {
            font-size: 20px;
            margin-right: 12px;
            width: 25px;
            text-align: center;
        }
        .file-name {
            flex: 1;
            color: #333;
            font-weight: 500;
        }
        a.file-name {
            color: #667eea;
            text-decoration: none;
        }
        a.file-name:hover {
            text-decoration: underline;
            color: #764ba2;
        }
        .file-size {
            color: #999;
            font-size: 12px;
            margin: 0 10px;
            min-width: 60px;
            text-align: right;
        }
        .folder {
            border-left-color: #28a745;
        }
        .folder a {
            color: #28a745;
            font-weight: 600;
        }
        .php {
            border-left-color: #ff6b6b;
        }
        .php a {
            color: #ff6b6b;
        }
        .sql {
            border-left-color: #ffa94d;
        }
        .sql a {
            color: #ffa94d;
        }
        .md {
            border-left-color: #4ecdc4;
        }
        .md a {
            color: #4ecdc4;
        }
        .category {
            margin-top: 25px;
            margin-bottom: 15px;
        }
        .category h2 {
            color: #667eea;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 8px;
        }
        .empty {
            color: #999;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📁 Donasiku - File Browser</h1>
        <div class="path">📍 <?php echo str_replace('\\', '/', $currentDir); ?></div>
        
        <div class="file-list">
            <?php
            $folders = [];
            $phpFiles = [];
            $otherFiles = [];
            
            foreach ($files as $file) {
                if ($file == '.' || $file == '..') continue;
                
                $filePath = $currentDir . DIRECTORY_SEPARATOR . $file;
                $isDir = is_dir($filePath);
                $fileSize = !$isDir ? filesize($filePath) : 0;
                
                $fileInfo = [
                    'name' => $file,
                    'path' => $filePath,
                    'isDir' => $isDir,
                    'size' => $fileSize,
                    'ext' => pathinfo($file, PATHINFO_EXTENSION)
                ];
                
                if ($isDir) {
                    $folders[] = $fileInfo;
                } elseif (strtolower(pathinfo($file, PATHINFO_EXTENSION)) == 'php') {
                    $phpFiles[] = $fileInfo;
                } else {
                    $otherFiles[] = $fileInfo;
                }
            }
            
            // Tampilkan Folders
            if (!empty($folders)) {
                echo '<div class="category"><h2>📂 Folder</h2></div>';
                foreach ($folders as $item) {
                    echo '<div class="file-item folder">';
                    echo '<div class="file-icon">📁</div>';
                    echo '<a href="?dir=' . urlencode($item['name']) . '" class="file-name">' . htmlspecialchars($item['name']) . '</a>';
                    echo '</div>';
                }
            }
            
            // Tampilkan PHP Files
            if (!empty($phpFiles)) {
                echo '<div class="category"><h2>⚙️ PHP Files</h2></div>';
                foreach ($phpFiles as $item) {
                    echo '<div class="file-item php">';
                    echo '<div class="file-icon">🔧</div>';
                    echo '<a href="' . htmlspecialchars($item['name']) . '" target="_blank" class="file-name">' . htmlspecialchars($item['name']) . '</a>';
                    echo '<div class="file-size">' . number_format($item['size'] / 1024, 2) . ' KB</div>';
                    echo '</div>';
                }
            }
            
            // Tampilkan Other Files
            if (!empty($otherFiles)) {
                echo '<div class="category"><h2>📄 Other Files</h2></div>';
                foreach ($otherFiles as $item) {
                    $icon = match(strtolower($item['ext'])) {
                        'sql' => '🗄️',
                        'md' => '📝',
                        'json' => '📋',
                        'txt' => '📄',
                        default => '📎'
                    };
                    
                    $class = 'file-item';
                    if ($item['ext'] == 'sql') $class .= ' sql';
                    elseif ($item['ext'] == 'md') $class .= ' md';
                    
                    echo '<div class="' . $class . '">';
                    echo '<div class="file-icon">' . $icon . '</div>';
                    if (in_array(strtolower($item['ext']), ['sql', 'md', 'txt', 'json'])) {
                        echo '<a href="' . htmlspecialchars($item['name']) . '" target="_blank" class="file-name">' . htmlspecialchars($item['name']) . '</a>';
                    } else {
                        echo '<span class="file-name">' . htmlspecialchars($item['name']) . '</span>';
                    }
                    echo '<div class="file-size">' . number_format($item['size'] / 1024, 2) . ' KB</div>';
                    echo '</div>';
                }
            }
            
            if (empty($folders) && empty($phpFiles) && empty($otherFiles)) {
                echo '<div class="empty">📭 Tidak ada file</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
