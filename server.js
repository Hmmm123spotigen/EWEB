const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const fs = require('fs');

const app = express();
const PORT = 3000;
const COUNT_FILE = 'count.json';

// Middleware
app.use(cors());
app.use(bodyParser.json());

// Load the count from the JSON file
function loadCount() {
    if (fs.existsSync(COUNT_FILE)) {
        const data = fs.readFileSync(COUNT_FILE);
        return JSON.parse(data).count || 0;
    }
    return 0;
}

// Save the count to the JSON file
function saveCount(count) {
    fs.writeFileSync(COUNT_FILE, JSON.stringify({ count }));
}

// Initialize the count
let count = loadCount();

// Serve the static HTML file
app.get('/', (req, res) => {
    res.sendFile(__dirname + '/index.html');
});

// Handle button tap
app.post('/tap', (req, res) => {
    count++;
    saveCount(count);
    res.json({ count });
});

// Start the server
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
