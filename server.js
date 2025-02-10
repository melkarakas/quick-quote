const express = require("express");
const fetch = require("node-fetch"); // If using Node.js 18+, you can use the native fetch
const path = require("path");
const app = express();

const NEVER_BOUNCE_API_KEY = process.env.NEVER_BOUNCE_API_KEY; // Set via Azure Application Settings

// Serve static files from the "public" folder
app.use(express.static(path.join(__dirname, "")));

// Proxy endpoint for email validation using NeverBounce
app.get("/api/validate-email", async (req, res) => {
  const email = req.query.email;
  if (!email) {
    return res.status(400).json({ error: "Email parameter is required" });
  }

  try {
    const url = `https://api.neverbounce.com/v4/single/check?key=${NEVER_BOUNCE_API_KEY}&email=${encodeURIComponent(email)}`;
    const response = await fetch(url);
    const data = await response.json();

    // Return the result from NeverBounce (you can filter further if needed)
    res.json({ result: data.result });
  } catch (error) {
    console.error("NeverBounce error:", error);
    res.status(500).json({ error: "Internal Server Error" });
  }
});

// Fallback to index.html (for Single Page Applications)
app.get("*", (req, res) => {
  res.sendFile(path.join(__dirname, "public", "index.html"));
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});
