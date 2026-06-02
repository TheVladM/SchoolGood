<?php
/**
 * Script pour générer un document Word à partir du manuel technique
 * Utilise COM automation (Windows uniquement) ou crée un fichier .docx basique
 */

// Désactiver l'affichage des erreurs pour les warnings PHP
error_reporting(E_ERROR | E_PARSE);

$markdownContent = file_get_contents(__DIR__ . '/MANUEL_TECHNIQUE.md');

if ($markdownContent === false) {
    die("Erreur: Impossible de lire le fichier MANUEL_TECHNIQUE.md\n");
}

// Fonction pour convertir le Markdown en HTML
function markdownToHtml($markdown) {
    $html = $markdown;
    
    // Titres
    $html = preg_replace('/^# (.*?)$/m', '<h1>$1</h1>', $html);
    $html = preg_replace('/^## (.*?)$/m', '<h2>$1</h2>', $html);
    $html = preg_replace('/^### (.*?)$/m', '<h3>$1</h3>', $html);
    $html = preg_replace('/^#### (.*?)$/m', '<h4>$1</h4>', $html);
    
    // Gras
    $html = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $html);
    
    // Listes non ordonnées
    $html = preg_replace('/^- (.*?)$/m', '<li>$1</li>', $html);
    
    // Listes ordonnées
    $html = preg_replace('/^\d+\.\s+(.*?)$/m', '<li>$1</li>', $html);
    
    // Lignes horizontales
    $html = str_replace('---', '<hr>', $html);
    
    // Sauts de ligne
    $html = nl2br($html);
    
    return $html;
}

$htmlContent = markdownToHtml($markdownContent);

// Méthode 1: Essayer COM (Word automation)
function generateWithCOM($htmlContent, $outputPath) {
    try {
        $word = new COM("Word.Application") or die("Impossible de démarrer Word");
        $word->Visible = false;
        $word->DisplayAlerts = false;
        
        $doc = $word->Documents->Add();
        
        // Ajouter le contenu
        $selection = $word->Selection;
        
        // Parser le HTML et ajouter le contenu
        $lines = explode("\n", $htmlContent);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            if (preg_match('/<h1>(.*?)<\/h1>/i', $line, $matches)) {
                $selection->Style = "Title";
                $selection->TypeText($matches[1]);
                $selection->TypeParagraph();
            } elseif (preg_match('/<h2>(.*?)<\/h2>/i', $line, $matches)) {
                $selection->Style = "Heading 1";
                $selection->TypeText($matches[1]);
                $selection->TypeParagraph();
            } elseif (preg_match('/<h3>(.*?)<\/h3>/i', $line, $matches)) {
                $selection->Style = "Heading 2";
                $selection->TypeText($matches[1]);
                $selection->TypeParagraph();
            } elseif (preg_match('/<h4>(.*?)<\/h4>/i', $line, $matches)) {
                $selection->Style = "Heading 3";
                $selection->TypeText($matches[1]);
                $selection->TypeParagraph();
            } elseif (preg_match('/<b>(.*?)<\/b>/i', $line, $matches)) {
                $selection->Font->Bold = true;
                $selection->TypeText($matches[1]);
                $selection->Font->Bold = false;
                $selection->TypeParagraph();
            } elseif (preg_match('/<li>(.*?)<\/li>/i', $line, $matches)) {
                $selection->TypeText("• " . $matches[1]);
                $selection->TypeParagraph();
            } elseif (preg_match('/<hr>/i', $line)) {
                $selection->TypeParagraph();
            } else {
                $text = strip_tags($line);
                if (!empty($text)) {
                    $selection->TypeText($text);
                    $selection->TypeParagraph();
                }
            }
        }
        
        // Sauvegarder le document
        $doc->SaveAs2($outputPath);
        $doc->Close();
        $word->Quit();
        
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Méthode 2: Créer un fichier HTML qui peut être ouvert par Word
function generateAsHTML($htmlContent, $outputPath) {
    $fullHtml = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manuel Technique de Maintenance - SchoolGood</title>
    <style>
        body {
            font-family: 'Calibri', 'Arial', sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            color: #333;
        }
        h1 {
            font-size: 24pt;
            color: #1a365d;
            border-bottom: 2px solid #1a365d;
            padding-bottom: 10px;
            margin-top: 30px;
        }
        h2 {
            font-size: 18pt;
            color: #2c5282;
            margin-top: 25px;
            margin-bottom: 10px;
        }
        h3 {
            font-size: 14pt;
            color: #2b6cb0;
            margin-top: 20px;
            margin-bottom: 8px;
        }
        h4 {
            font-size: 12pt;
            color: #3182ce;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        p {
            margin: 10px 0;
        }
        ul, ol {
            margin: 10px 0;
            padding-left: 30px;
        }
        li {
            margin: 5px 0;
        }
        hr {
            border: none;
            border-top: 1px solid #ccc;
            margin: 20px 0;
        }
        code {
            background-color: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        .code-block {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 10pt;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 15px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .toc {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .toc ol {
            margin: 0;
        }
    </style>
</head>
<body>
{$htmlContent}
</body>
</html>
HTML;

    file_put_contents($outputPath, $fullHtml);
    return true;
}

// Méthode 3: Créer un fichier MHTML (format Word compatible)
function generateAsMHTML($htmlContent, $outputPath) {
    $mhtml = <<<MHTML
From: <SchoolGood>
Subject: Manuel Technique de Maintenance - SchoolGood
MIME-Version: 1.0
Content-Type: multipart/related;
	type="text/html";
	boundary="----=_NextPart_01"

------=_NextPart_01
Content-Type: text/html;
	charset="UTF-8"
Content-Transfer-Encoding: quoted-printable
Content-Location: main.html

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Manuel Technique de Maintenance - SchoolGood</title>
</head>
<body>
{$htmlContent}
</body>
</html>

------=_NextPart_01--
MHTML;

    file_put_contents($outputPath, $mhtml);
    return true;
}

// Déterminer le chemin de sortie
$outputDir = __DIR__ . '/storage/docs';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

$outputWordPath = $outputDir . '/Manuel_Technique_SchoolGood.docx';
$outputHtmlPath = $outputDir . '/Manuel_Technique_SchoolGood.html';
$outputMhtmlPath = $outputDir . '/Manuel_Technique_SchoolGood.mht';

echo "Génération du document Word...\n";

// Essayer COM en premier (meilleure qualité)
if (extension_loaded('com_dotnet') || class_exists('COM')) {
    echo "Tentative avec COM automation...\n";
    if (generateWithCOM($htmlContent, $outputWordPath)) {
        echo "✓ Document Word généré avec succès: $outputWordPath\n";
        exit(0);
    }
}

// Sinon, générer en HTML (peut être ouvert par Word)
echo "Génération en format HTML (ouvrable dans Word)...\n";
if (generateAsHTML($htmlContent, $outputHtmlPath)) {
    echo "✓ Document HTML généré avec succès: $outputHtmlPath\n";
    echo "  Vous pouvez ouvrir ce fichier avec Microsoft Word et l'enregistrer en .docx\n";
}

// Générer aussi en MHTML
echo "Génération en format MHTML...\n";
if (generateAsMHTML($htmlContent, $outputMhtmlPath)) {
    echo "✓ Document MHTML généré avec succès: $outputMhtmlPath\n";
    echo "  Ce format peut être ouvert directement dans Microsoft Word\n";
}

echo "\n=== Génération terminée ===\n";
echo "Les documents sont disponibles dans: $outputDir\n";