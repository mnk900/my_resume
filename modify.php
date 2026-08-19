<?php
$file = "c:/xampp/htdocs/my_resume/resources/views/portfolio/edit.blade.php";
$lines = file($file);

// Find the boundaries
$startDashboardEnd = -1;
$startConnectionsPane = -1;
$endConnectionsPane = -1;

for ($i = 0; $i < count($lines); $i++) {
    if (strpos($lines[$i], 'id="connections-tab"') !== false) {
        // Update sidebar link
        $lines[$i] = str_replace('data-bs-toggle="tab" data-bs-target="#connectionsPane"', '', $lines[$i]);
        $lines[$i] = str_replace('role="tab"', 'class="trigger-connections-section"', $lines[$i]);
    }
    if (strpos($lines[$i], '<!-- 2. MODULES / SECTIONS TAB PANE -->') !== false) {
        // The dashboard pane ends right before this
        $startDashboardEnd = $i - 2; 
    }
    if (strpos($lines[$i], '<div class="tab-pane fade" id="connectionsPane" role="tabpanel">') !== false) {
        $startConnectionsPane = $i;
    }
    if ($startConnectionsPane !== -1 && $i > $startConnectionsPane + 10 && strpos($lines[$i], '<!-- Tab Persistence Script -->') !== false) {
        // The connections pane ends before the scripts
        // But wait, it's just a few divs. 
    }
}

// Better way to find end of connectionsPane:
// It ends just before:
//     @push('scripts')
//         <script>
//             // Client-side search and pagination engine

for ($i = $startConnectionsPane; $i < count($lines); $i++) {
    if (strpos($lines[$i], '@push(\'scripts\')') !== false) {
        $endConnectionsPane = $i - 3; // roughly 3 divs up
        break;
    }
}

if ($startConnectionsPane !== -1 && $endConnectionsPane !== -1 && $startDashboardEnd !== -1) {
    // Extract connections pane
    $connectionsPaneLines = array_slice($lines, $startConnectionsPane, $endConnectionsPane - $startConnectionsPane + 1);
    
    // Modify first line of extracted pane to be a hidden div instead of tab-pane
    for($j=0; $j<count($connectionsPaneLines); $j++){
        if(strpos($connectionsPaneLines[$j], '<div class="tab-pane fade" id="connectionsPane" role="tabpanel">') !== false){
            $connectionsPaneLines[$j] = '                <div id="connectionsSection" class="mt-5 d-none">' . "\n";
            break;
        }
    }

    // Remove original connections pane
    array_splice($lines, $startConnectionsPane, $endConnectionsPane - $startConnectionsPane + 1);
    
    // Insert into dashboard pane
    array_splice($lines, $startDashboardEnd, 0, $connectionsPaneLines);
    
    // Add JS code before @push('scripts')
    for ($i = 0; $i < count($lines); $i++) {
        if (strpos($lines[$i], '@push(\'scripts\')') !== false) {
            $js = <<<JS
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.trigger-connections-section').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    // Activate Dashboard tab first if not active
                    var dashTab = document.getElementById('dashboard-tab');
                    if(dashTab && typeof bootstrap !== 'undefined') {
                        bootstrap.Tab.getOrCreateInstance(dashTab).show();
                    }
                    
                    // Show connections section
                    const section = document.getElementById('connectionsSection');
                    if (section) {
                        section.classList.remove('d-none');
                        setTimeout(() => {
                            section.scrollIntoView({ behavior: 'smooth' });
                        }, 100);
                    }
                });
            });
        });
    </script>
JS;
            array_splice($lines, $i, 0, [$js . "\n"]);
            break;
        }
    }
    
    file_put_contents($file, implode("", $lines));
    echo "Success! Reconstructed edit.blade.php.";
} else {
    echo "Could not find boundaries. startDashboardEnd: $startDashboardEnd, startConnectionsPane: $startConnectionsPane, endConnectionsPane: $endConnectionsPane";
}
?>
