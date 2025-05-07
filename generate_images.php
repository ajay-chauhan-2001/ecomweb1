<?php
// Sample product images to download
$images = [
    'sofa-set-1.jpg' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc',
    'sofa-set-2.jpg' => 'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e',
    'queen-bed-1.jpg' => 'https://images.unsplash.com/photo-1505693314120-0d8871890b7b',
    'queen-bed-2.jpg' => 'https://images.unsplash.com/photo-1505693314120-0d8871890b7b',
    'dining-set-1.jpg' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c',
    'office-desk-1.jpg' => 'https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd',
    'outdoor-set-1.jpg' => 'https://images.unsplash.com/photo-1583845112209-5eb7f768b8df'
];

// Directory to save images
$saveDir = 'assets/images/products/';

// Create directory if it doesn't exist
if (!file_exists($saveDir)) {
    mkdir($saveDir, 0777, true);
}

// Download each image
foreach ($images as $filename => $url) {
    $savePath = $saveDir . $filename;
    
    // Skip if file already exists
    if (file_exists($savePath)) {
        echo "Skipping $filename - already exists\n";
        continue;
    }
    
    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    // Execute cURL
    $imageData = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo "Error downloading $filename: " . curl_error($ch) . "\n";
        continue;
    }
    
    // Save the image
    if (file_put_contents($savePath, $imageData)) {
        echo "Successfully downloaded $filename\n";
    } else {
        echo "Failed to save $filename\n";
    }
    
    curl_close($ch);
}

echo "Image generation complete!\n";
?> 