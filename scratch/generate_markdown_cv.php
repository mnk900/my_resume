<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Http\Controllers\CVController;

$user = User::where('name', 'like', '%Fazal%')->first() ?? User::first();
if (!$user) {
    echo "ERROR: User not found.\n";
    exit(1);
}

$portfolio = $user->portfolio;
$cvController = new CVController();
$data = $cvController->prepareCVData($user, $portfolio);

// Let's generate the markdown table format
$leftCol = [];
$rightCol = [];

// PROFILE IMAGE PLACEHOLDER
$imagePlaceholder = "";
if ($portfolio->profile_image) {
    $imagePlaceholder = '<img src="' . url('storage/' . $portfolio->profile_image) . '" width="110" height="110" style="border-radius:50%; border:2px solid #a51c30;" />';
} else {
    $imagePlaceholder = '📷 **[Profile Image Placeholder]**';
}
$leftCol[] = $imagePlaceholder;

// CONTACT INFO
$contact = "### CONTACT INFO <br>";
if ($data['phone']) $contact .= "📱 " . $data['phone'] . " <br>";
if ($data['email']) $contact .= "✉️ " . $data['email'] . " <br>";
if ($data['linkedin']) $contact .= "🔗 [LinkedIn](" . $data['linkedin'] . ") <br>";
if ($data['website']) $contact .= "🌐 [Website](" . $data['website'] . ") <br>";
$contact .= "📍 " . $data['location'];
$leftCol[] = $contact;

// TECHNICAL SKILLS
$skillsStr = "### TECHNICAL SKILLS <br>";
$count = 0;
foreach ($data['skills'] as $cat => $list) {
    $skillsStr .= "**" . trim($cat) . ":** " . implode(', ', $list) . " <br>";
}
$leftCol[] = $skillsStr;

// CERTIFICATIONS
$certsStr = "### CERTIFICATIONS <br>";
if (!empty($data['certifications'])) {
    $idx = 1;
    foreach ($data['certifications'] as $cert) {
        $certsStr .= $idx . ". " . trim($cert) . " <br>";
        $idx++;
    }
} else {
    $certsStr .= "*No certifications listed*";
}
$leftCol[] = $certsStr;

// TRAININGS
$trainingsStr = "### TRAININGS <br>";
if (!empty($data['trainings'])) {
    foreach ($data['trainings'] as $train) {
        $trainingsStr .= "• " . trim($train) . " <br>";
    }
} else {
    $trainingsStr .= "*No trainings listed*";
}
$leftCol[] = $trainingsStr;

// EDUCATION
$eduStr = "### EDUCATION <br>";
if (!empty($data['education'])) {
    foreach ($data['education'] as $edu) {
        $eduStr .= "**" . trim($edu['degree']) . "** <br> " . trim($edu['institution']) . " <br> *" . $edu['start_date'] . " – " . $edu['end_date'] . "* <br><br>";
    }
}
$leftCol[] = rtrim($eduStr, "<br>");


// RIGHT COLUMN
// NAME & TITLE
$rightCol[] = "# " . strtoupper($data['name']) . " <br> ## " . $data['title'] . " <br> *" . trim(strip_tags($data['summary'])) . "*";

// PROFESSIONAL EXPERIENCE
$expStr = "### PROFESSIONAL EXPERIENCE <br>";
if (!empty($data['experience'])) {
    $detailed = array_filter($data['experience'], function($e) { return $e['has_details']; });
    $compact = array_filter($data['experience'], function($e) { return !$e['has_details']; });
    
    foreach ($detailed as $exp) {
        $expStr .= "**" . trim($exp['position']) . "** – " . trim($exp['company']) . " <br> *" . $exp['start_date'] . " – " . $exp['end_date'] . "* <br>";
        
        $desc = trim(strip_tags($exp['description']));
        $bullets = explode("\n", $desc);
        foreach ($bullets as $b) {
            $b = trim($b);
            if (!empty($b)) {
                if (strpos($b, '•') === 0 || strpos($b, '-') === 0 || strpos($b, '*') === 0) {
                    $b = ltrim($b, "•-* \t");
                }
                $expStr .= "• " . $b . " <br>";
            }
        }
        $expStr .= "<br>";
    }
    
    if (!empty($compact)) {
        $expStr .= "**Additional Professional History** <br>";
        foreach ($compact as $exp) {
            $expStr .= "• **" . trim($exp['position']) . "** at " . trim($exp['company']) . " (" . $exp['start_date'] . " – " . $exp['end_date'] . ") <br>";
        }
    }
}
$rightCol[] = rtrim($expStr, "<br>");

// FLAGSHIP PROJECTS
$projStr = "### FLAGSHIP PROJECTS <br>";
if (!empty($data['projects'])) {
    foreach ($data['projects'] as $p) {
        $projStr .= "**" . trim($p['title']) . "** <br> " . trim(strip_tags($p['description'])) . " <br><br>";
    }
}
$rightCol[] = rtrim($projStr, "<br>");


// PRINT THE MARKDOWN TABLE
echo "| LEFT COLUMN (35% Width) | RIGHT COLUMN (65% Width) |\n";
echo "| :--- | :--- |\n";

// ROW 1
echo "| " . str_replace("\n", "", $leftCol[0] . "<br><br>" . $leftCol[1]) . " | " . str_replace("\n", "", $rightCol[0]) . " |\n";
// ROW 2
echo "| " . str_replace("\n", "", $leftCol[2]) . " | " . str_replace("\n", "", $rightCol[1]) . " |\n";
// ROW 3
echo "| " . str_replace("\n", "", $leftCol[3]) . " | " . str_replace("\n", "", $rightCol[2]) . " |\n";
// ROW 4
echo "| " . str_replace("\n", "", $leftCol[4]) . " | | \n";
// ROW 5
echo "| " . str_replace("\n", "", $leftCol[5]) . " | | \n";

