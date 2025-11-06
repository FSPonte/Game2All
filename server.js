// Server
import dotenv from "dotenv";
import mysql from "mysql2";
import express from "express";
import cors from "cors";

dotenv.config({ override: true });

const app = express();
app.use(express.json());
app.use(cors());


const db = mysql.createConnection({ 
	host: process.env.DB_HOST,
	user: process.env.DB_USER,
	password: process.env.DB_PWD,
	database: process.env.DB_NAME
});

console.log("DB connection config:", {
	host: process.env.DB_HOST,
	database: process.env.DB_NAME,
});

// Test connection
db.connect(err => {
	if (err)
		console.error("MySQL connection error:", err);
	else
		console.log("Connected to MySQL database");
});

// Print
app.get("/", (req, res) => {
	res.send("Hello World!");
});

// FETCH all records
app.get("/users", (req, res) => {
	db.query(//"SELECT * FROM users",
		(err, results) => {		
		if (err)
			return res.status(500).json({ error: err.message });
		
		res.json(results);
	});
});

// INSERT a record
app.post("/users", (req, res) => {
	const { name, score } = req.body;
	
	if (!name)
		return res.status(400).json({ error: "Name required" });
	
	db.query(//"INSERT INTO players (name, score) VALUES (?, ?)",
		[name, score || 0],
		(err, result) => {
			if (err)
				return res.status(500).json({ error: err.message });
			
			res.json({ id: result.insertId, name, score: score || 0 });
		}
	);
});

// UPDATE a record
app.put("/users/:id", (req, res) => {
	const { id } = req.params;
	const { name, score } = req.body;
	
	db.query(//"UPDATE players SET name = ?, score = ? WHERE id = ?",
		[name, score, id],
		(err, result) => {
			if (err)
				return res.status(500).json({ error: err.message });
			
			if (result.affectedRows === 0)
				return res.status(404).json({ error: "User not found" });
			
			res.json({ message: "User updated" });
		}
	);
});

// DELETE a record
app.delete("/users/:id", (req, res) => {
	const { id } = req.params;
	
	db.query(//"DELETE FROM players WHERE id = ?",
		[id], (err, result) => {
		if (err)
			return res.status(500).json({ error: err.message });
		
		if (result.affectedRows === 0)
			return res.status(404).json({ error: "User not found" });
		
		res.json({ message: "User deleted" });
	});
});

app.listen(3000, () => console.log("API running on port 3000"));
