<?php
// Group files by type
$filesByType = [
    'PHP' => [
        'add_game.php',
        'browse.php',
        'db_connect.php',
        'delete_game.php',
        'edit_game.php',
        'footer.php',
        'game.php',
        'header.php',
        'index.php',
        'export_code.php',
        'export_by_type.php'
    ],
    'HTML' => [
        'contact.html',
        'contact_submit.html',
        'code_export.html'
    ],
    'CSS' => [
        'styles.css'
    ],
    'JavaScript' => [
        'validation.js'
    ],
    'SQL' => [
        'database_schema.sql'
    ]
];

// Function to get file content
function getFileContent($file) {
    if (file_exists($file)) {
        return file_get_contents($file);
    }
    return "// File not found: $file";
}

// Function to get language class for syntax highlighting
function getLanguageClass($extension) {
    switch ($extension) {
        case 'php':
            return 'language-php';
        case 'js':
            return 'language-javascript';
        case 'css':
            return 'language-css';
        case 'html':
            return 'language-html';
        case 'sql':
            return 'language-sql';
        default:
            return 'language-plaintext';
    }
}

// Handle export to Word if requested
if (isset($_GET['export']) && isset($_GET['type'])) {
    $type = $_GET['type'];
    $files = isset($filesByType[$type]) ? $filesByType[$type] : [];

    if (empty($files)) {
        echo "No files found for type: $type";
        exit;
    }

    header('Content-Type: application/vnd.ms-word');
    header('Content-Disposition: attachment; filename="GameCritic_' . $type . '_Code.doc"');

    echo '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>GameCritic - ' . $type . ' Code Export</title>
        <style>
            body { font-family: Calibri, sans-serif; }
            h1 { text-align: center; color: #7e57c2; }
            h2 { color: #7e57c2; margin-top: 20px; page-break-before: always; }
            pre { background-color: #f5f5f5; padding: 10px; border: 1px solid #ddd; white-space: pre-wrap; font-family: Consolas, "Courier New", monospace; }
        </style>
    </head>
    <body>
        <h1>GameCritic - ' . $type . ' Code</h1>';

    foreach ($files as $file) {
        $content = getFileContent($file);
        echo '<h2>' . htmlspecialchars($file) . '</h2>';
        echo '<pre>' . htmlspecialchars($content) . '</pre>';
    }

    echo '</body></html>';
    exit;
}

// Handle export all to Word
if (isset($_GET['export']) && $_GET['export'] === 'all') {
    header('Content-Type: application/vnd.ms-word');
    header('Content-Disposition: attachment; filename="GameCritic_All_Code.doc"');

    echo '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>GameCritic - All Code Export</title>
        <style>
            body { font-family: Calibri, sans-serif; }
            h1 { text-align: center; color: #7e57c2; }
            h2 { color: #7e57c2; margin-top: 20px; }
            h3 { color: #7e57c2; margin-top: 30px; page-break-before: always; }
            pre { background-color: #f5f5f5; padding: 10px; border: 1px solid #ddd; white-space: pre-wrap; font-family: Consolas, "Courier New", monospace; }
        </style>
    </head>
    <body>
        <h1>GameCritic - All Project Code</h1>';

    foreach ($filesByType as $type => $files) {
        echo '<h2>' . $type . ' Files</h2>';

        foreach ($files as $file) {
            $content = getFileContent($file);
            echo '<h3>' . htmlspecialchars($file) . '</h3>';
            echo '<pre>' . htmlspecialchars($content) . '</pre>';
        }
    }

    echo '</body></html>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameCritic - Code Export by Type</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #7e57c2;
            --primary-color-rgb: 126, 87, 194;
            --secondary-color: #00e676;
            --secondary-color-rgb: 0, 230, 118;
            --accent-color: #ff1744;
            --accent-color-rgb: 255, 23, 68;
            --text-color: #f5f5f5;
            --text-dark: #212121;
            --light-gray: #f5f5f5;
            --border-color: #424242;
            --dark-bg: #1e1e2f;
            --darker-bg: #121212;
            --card-bg: #2d2d3a;
            --header-bg: #121212;
            --footer-bg: #121212;
            --highlight: #00b0ff;
            --highlight-rgb: 0, 176, 255;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--dark-bg);
            background-image: linear-gradient(to bottom, var(--dark-bg), var(--darker-bg));
            padding: 20px;
            position: relative;
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(var(--highlight-rgb, 0.03) 1px, transparent 1px),
                radial-gradient(var(--primary-color-rgb, 0.03) 1px, transparent 1px);
            background-size: 20px 20px, 30px 30px;
            background-position: 0 0, 15px 15px;
            pointer-events: none;
            opacity: 0.1;
            z-index: -1;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: var(--card-bg);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(var(--highlight-rgb), 0.1);
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, var(--primary-color), var(--highlight), var(--secondary-color));
            box-shadow: 0 0 20px rgba(var(--highlight-rgb), 0.5);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--text-color);
            font-family: 'Montserrat', sans-serif;
            font-size: 2.5rem;
            position: relative;
            display: inline-block;
            width: 100%;
            padding-bottom: 15px;
        }

        h1::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--highlight), var(--secondary-color));
            border-radius: 3px;
            box-shadow: 0 0 10px rgba(var(--highlight-rgb), 0.5);
        }

        .export-all {
            display: block;
            width: 250px;
            margin: 0 auto 30px;
            padding: 1rem 2rem;
            background: linear-gradient(45deg, var(--primary-color), var(--highlight));
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 0 15px rgba(var(--highlight-rgb), 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .export-all::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(45deg, var(--highlight), var(--secondary-color));
            transition: width 0.3s ease;
            z-index: -1;
            border-radius: 50px;
        }

        .export-all:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 0 20px rgba(var(--highlight-rgb), 0.6);
        }

        .export-all:hover::before {
            width: 100%;
        }

        .type-tabs {
            display: flex;
            border-bottom: 2px solid rgba(var(--highlight-rgb), 0.2);
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .type-tab {
            padding: 12px 25px;
            background-color: rgba(var(--primary-color-rgb), 0.1);
            border: none;
            border-radius: 10px 10px 0 0;
            cursor: pointer;
            font-weight: 600;
            margin-right: 5px;
            transition: all 0.3s;
            color: var(--text-color);
            border: 1px solid rgba(var(--primary-color-rgb), 0.2);
            border-bottom: none;
            font-size: 0.95rem;
        }

        .type-tab:hover {
            background-color: rgba(var(--primary-color-rgb), 0.2);
            transform: translateY(-3px);
        }

        .type-tab.active {
            background: linear-gradient(to right, var(--primary-color), var(--highlight));
            color: white;
            box-shadow: 0 -5px 15px rgba(var(--highlight-rgb), 0.2);
        }

        .type-section {
            display: none;
            margin-bottom: 30px;
        }

        .type-section.active {
            display: block;
        }

        .type-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: rgba(0, 0, 0, 0.2);
            padding: 15px 20px;
            border-radius: 10px;
            border: 1px solid rgba(var(--highlight-rgb), 0.1);
        }

        .type-title {
            font-size: 1.5rem;
            color: var(--text-color);
            font-weight: 600;
            position: relative;
            padding-left: 20px;
        }

        .type-title::before {
            content: '🎮';
            position: absolute;
            left: -5px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
        }

        .export-type {
            padding: 8px 20px;
            background: linear-gradient(45deg, var(--secondary-color), #69f0ae);
            color: var(--darker-bg);
            border: none;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 0 15px rgba(var(--secondary-color-rgb), 0.3);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .export-type:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 0 20px rgba(var(--secondary-color-rgb), 0.5);
        }

        .file-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .file-btn {
            padding: 8px 15px;
            background-color: rgba(var(--highlight-rgb), 0.1);
            color: var(--text-color);
            border: 1px solid rgba(var(--highlight-rgb), 0.2);
            border-radius: 20px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .file-btn:hover, .file-btn.active {
            background-color: var(--highlight);
            color: white;
            box-shadow: 0 0 15px rgba(var(--highlight-rgb), 0.4);
            transform: translateY(-2px);
        }

        .code-section {
            margin-bottom: 30px;
            display: none;
        }

        .code-section.active {
            display: block;
        }

        .code-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(to right, var(--primary-color), var(--highlight));
            color: white;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
        }

        .code-title {
            font-size: 1.2rem;
            font-weight: bold;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .copy-btn {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            font-size: 0.9rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .copy-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .code-content {
            background-color: #1a1a2e;
            border: 1px solid rgba(var(--highlight-rgb), 0.1);
            border-top: none;
            border-radius: 0 0 10px 10px;
            overflow: hidden;
        }

        pre {
            margin: 0 !important;
            padding: 20px !important;
            max-height: 500px;
            overflow: auto;
            font-family: 'Fira Code', 'Consolas', 'Courier New', monospace !important;
            font-size: 0.95rem !important;
        }

        pre::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }

        pre::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
        }

        pre::-webkit-scrollbar-thumb {
            background-color: rgba(var(--highlight-rgb), 0.3);
            border-radius: 6px;
            border: 3px solid rgba(0, 0, 0, 0);
        }

        pre::-webkit-scrollbar-thumb:hover {
            background-color: rgba(var(--highlight-rgb), 0.5);
        }

        .success-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(to right, var(--secondary-color), #69f0ae);
            color: var(--darker-bg);
            padding: 12px 25px;
            border-radius: 50px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            display: none;
            font-weight: 600;
            transform: translateY(-10px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .success-message.show {
            display: block;
            transform: translateY(0);
            opacity: 1;
        }

        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(45deg, var(--primary-color), var(--highlight));
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            font-size: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transition: all 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .back-to-top.show {
            opacity: 1;
        }

        .back-to-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(var(--highlight-rgb), 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>GameCritic - Code Export by Type</h1>

        <a href="?export=all" class="export-all">Export All Code to Word</a>

        <div class="type-tabs">
            <?php foreach (array_keys($filesByType) as $index => $type): ?>
                <button class="type-tab <?php echo $index === 0 ? 'active' : ''; ?>" data-type="<?php echo $type; ?>"><?php echo $type; ?></button>
            <?php endforeach; ?>
        </div>

        <?php foreach ($filesByType as $type => $files): ?>
            <div class="type-section <?php echo $type === array_keys($filesByType)[0] ? 'active' : ''; ?>" id="section-<?php echo strtolower($type); ?>">
                <div class="type-header">
                    <h2 class="type-title"><?php echo $type; ?> Files</h2>
                    <a href="?export=type&type=<?php echo $type; ?>" class="export-type">Export <?php echo $type; ?> to Word</a>
                </div>

                <div class="file-list">
                    <?php foreach ($files as $index => $file): ?>
                        <button class="file-btn <?php echo $index === 0 ? 'active' : ''; ?>" data-file="<?php echo $file; ?>" data-type="<?php echo $type; ?>"><?php echo $file; ?></button>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($files as $index => $file): ?>
                    <?php
                        $content = getFileContent($file);
                        $extension = pathinfo($file, PATHINFO_EXTENSION);
                        $languageClass = getLanguageClass($extension);
                    ?>
                    <div class="code-section <?php echo $index === 0 ? 'active' : ''; ?>" id="section-<?php echo $type; ?>-<?php echo $file; ?>">
                        <div class="code-header">
                            <div class="code-title"><?php echo $file; ?></div>
                            <button class="copy-btn" data-content="<?php echo htmlspecialchars($content); ?>">Copy Code</button>
                        </div>
                        <div class="code-content">
                            <pre><code class="<?php echo $languageClass; ?>"><?php echo htmlspecialchars($content); ?></code></pre>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="success-message">Code copied to clipboard! 🎮</div>
    <a href="#" class="back-to-top" id="back-to-top">↑</a>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/plugins/autoloader/prism-autoloader.min.js"></script>
    <script>
        // DOM elements
        const typeTabs = document.querySelectorAll('.type-tab');
        const typeSelections = document.querySelectorAll('.type-section');
        const fileButtons = document.querySelectorAll('.file-btn');
        const copyButtons = document.querySelectorAll('.copy-btn');
        const successMessage = document.querySelector('.success-message');
        const backToTop = document.getElementById('back-to-top');

        // Type tabs
        typeTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs
                typeTabs.forEach(t => t.classList.remove('active'));

                // Add active class to clicked tab
                tab.classList.add('active');

                // Hide all type sections
                typeSelections.forEach(section => {
                    section.classList.remove('active');
                });

                // Show the selected type section
                const type = tab.getAttribute('data-type');
                document.getElementById(`section-${type.toLowerCase()}`).classList.add('active');
            });
        });

        // File buttons
        fileButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Get the type and file
                const type = button.getAttribute('data-type');
                const file = button.getAttribute('data-file');

                // Remove active class from all buttons in this type
                document.querySelectorAll(`.file-btn[data-type="${type}"]`).forEach(btn => {
                    btn.classList.remove('active');
                });

                // Add active class to clicked button
                button.classList.add('active');

                // Hide all code sections in this type
                document.querySelectorAll(`.code-section[id^="section-${type}-"]`).forEach(section => {
                    section.classList.remove('active');
                });

                // Show the selected code section
                document.getElementById(`section-${type}-${file}`).classList.add('active');
            });
        });

        // Copy buttons
        copyButtons.forEach(button => {
            button.addEventListener('click', () => {
                const content = button.getAttribute('data-content');

                // Create a temporary textarea element to copy the content
                const textarea = document.createElement('textarea');
                textarea.value = content;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);

                // Show success message
                successMessage.classList.add('show');
                setTimeout(() => {
                    successMessage.classList.remove('show');
                }, 2000);
            });
        });

        // Back to top button
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        backToTop.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>
