const express = require('express');
const app = express();
const dotenv = require("dotenv");
const { connectToDatabase } = require('./lib/db');
const personRouter = require("./routes/persons.routes.js")

dotenv.config()

// Middleware
app.use(express.urlencoded({ extended: true }));
// Routes

app.use("/api/persons", personRouter); // Updated route to avoid conflict

// Start the server
app.listen(process.env.PORT || 5000, () => {
    console.log(`Server is running  `);
    connectToDatabase()
});
