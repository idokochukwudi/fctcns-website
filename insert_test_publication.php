<?php
// insert_test_publication.php
require_once 'app/config/constants.php';
require_once 'app/config/database.php';

$db = Database::getInstance();

// Insert a test publication
$testData = [
    'title' => 'Test Research Publication',
    'authors' => 'John Doe, Jane Smith',
    'abstract' => 'This is a test abstract for a research publication. It contains more than 200 characters to meet the validation requirements. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    'publication_type' => 'journal',
    'journal_name' => 'Test Journal',
    'volume' => '12',
    'issue' => '3',
    'pages' => '45-67',
    'publisher' => 'Test Publisher',
    'publication_date' => '2024-01-15',
    'doi' => '10.1000/test',
    'url' => 'https://example.com/test',
    'keywords' => 'test, research, neuroscience',
    'research_area' => 'neuroscience',
    'citations' => 5,
    'impact_factor' => 2.5,
    'is_featured' => 1,
    'is_published' => 1,
    'created_by' => 1
];

try {
    $id = $db->insert('research_publications', $testData);
    echo "✓ Test publication inserted with ID: $id\n";
    
    // Verify it was inserted
    $result = $db->fetchOne("SELECT COUNT(*) as count FROM research_publications");
    echo "✓ Total publications in database: " . $result['count'] . "\n";
    
    // Also test the model
    require_once 'app/models/ResearchModel.php';
    $model = new ResearchModel();
    $publications = $model->getAll();
    echo "✓ Publications via ResearchModel: " . count($publications) . "\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}