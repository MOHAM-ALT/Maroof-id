const fs = require('fs');

try {
    const html = fs.readFileSync('ai-workspace/templates/page/maroof-id-light.html', 'utf8');
    const lines = html.split('\n');

    // CSS starts at line 13 (index 12) and ends at line 3025 (index 3024)
    // We will extract inner CSS inside <style> ... </style>
    const startIndex = lines.findIndex(l => l.includes('<style>')) + 1;
    const endIndex = lines.findIndex(l => l.includes('</style>'));

    if (startIndex > 0 && endIndex > startIndex) {
        const cssContent = lines.slice(startIndex, endIndex).join('\n');
        fs.writeFileSync('public/css/maroof-home.css', cssContent);
        console.log('Successfully extracted CSS to public/css/maroof-home.css');
    } else {
        console.log('Could not find <style> tags.');
    }
} catch (error) {
    console.error('Error:', error);
}
