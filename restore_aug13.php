<?php
$file = "c:/xampp/htdocs/my_resume/resources/views/portfolio/edit.blade.php";
$lines = file($file);

// 1. Update sidebar link (around line 183)
for ($i = 0; $i < count($lines); $i++) {
    if (strpos($lines[$i], 'id="connections-tab"') !== false) {
        $lines[$i] = str_replace(
            'id="connections-tab" data-bs-toggle="tab" data-bs-target="#connectionsPane" type="button" role="tab"',
            'id="sidebarConnectionsLink" type="button" onclick="showConnectionsSection(event)"',
            $lines[$i]
        );
        break;
    }
}

// 2. Locate connectionsPane (around line 1441)
$startConn = -1;
$endConn = -1;
for ($i = 0; $i < count($lines); $i++) {
    if (strpos($lines[$i], '<div class="tab-pane fade" id="connectionsPane" role="tabpanel">') !== false) {
        $startConn = $i;
    }
    if ($startConn !== -1 && strpos($lines[$i], '<!-- 2. MODULES / SECTIONS TAB PANE -->') === false && $i > $startConn + 150) {
        // Look for end of connectionsPane
        if (trim($lines[$i]) === '</div>' && trim($lines[$i+1]) === '</div>' && strpos($lines[$i+2], '</div>') !== false) {
            $endConn = $i + 1;
            break;
        }
    }
}

if ($startConn !== -1 && $endConn !== -1) {
    // Extract connections lines
    $connLines = array_slice($lines, $startConn, $endConn - $startConn + 1);
    
    // Change wrapper div from tab-pane to hidden section
    $connLines[0] = '                    <!-- PROFESSIONAL CONNECTIONS SECTION - hidden by default, revealed by sidebar link -->' . "\n" .
                    '                    <div id="connectionsSection" class="mt-4 pt-3 border-top d-none">' . "\n";
    $connLines[count($connLines)-1] = '                    </div>' . "\n";

    // Remove connectionsPane from bottom
    array_splice($lines, $startConn, $endConn - $startConn + 1);

    // Find end of dashboardPane (before <!-- 2. MODULES / SECTIONS TAB PANE -->)
    $dashEnd = -1;
    for ($i = 0; $i < count($lines); $i++) {
        if (strpos($lines[$i], '<!-- 2. MODULES / SECTIONS TAB PANE -->') !== false) {
            $dashEnd = $i - 1;
            break;
        }
    }

    if ($dashEnd !== -1) {
        // Insert connLines into dashboardPane
        array_splice($lines, $dashEnd, 0, $connLines);
    }
}

// 3. Add JS function at end before </x-app-layout>
$js = <<<'JS'
    <script>
        function showConnectionsSection(e) {
            e.preventDefault();
            // Reveal the section
            var section = document.getElementById('connectionsSection');
            if (section) { section.classList.remove('d-none'); }
            // Mark sidebar link active
            document.querySelectorAll('.sidebar-menu .nav-link').forEach(function(l) { l.classList.remove('active'); });
            var link = document.getElementById('sidebarConnectionsLink');
            if (link) { link.classList.add('active'); }
            // Smooth scroll
            setTimeout(function() {
                if (section) { section.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
            }, 80);
        }
    </script>
JS;

for ($i = count($lines) - 1; $i >= 0; $i--) {
    if (strpos($lines[$i], '</x-app-layout>') !== false) {
        array_splice($lines, $i, 0, [$js . "\n"]);
        break;
    }
}

file_put_contents($file, implode("", $lines));
echo "Successfully restored Thursday 13 Aug 2026 changes to edit.blade.php\n";
?>
