const express = require('express');
const router = express.Router();
const Persons = require('../model/persons.model.js');
const multer = require('multer');
const { v2: cloudinary } = require('cloudinary');
const { CloudinaryStorage } = require('multer-storage-cloudinary');
const dotenv = require("dotenv")
dotenv.config()

// Configure Cloudinary
cloudinary.config({
    cloud_name: process.env.CLOUDINARY_NAME,
    api_key: process.env.CLOUDINARY_API_KEY,
    api_secret: process.env.CLOUDINARY_API_SECRET
});

// Configure Multer storage with Cloudinary
const storage = new CloudinaryStorage({
    cloudinary: cloudinary,
    params: {
        folder: 'persons', // Folder name in Cloudinary
        allowed_formats: ['jpg', 'png', 'jpeg'] // Allowed image formats
    }
});
const upload = multer({ storage });

// Get all persons
router.get('/', async (req, res) => {
    try {
        const persons = await Persons.find();
        return res.status(200).json(persons);
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

// Add a new person with image upload
router.post('/', upload.single('image'), async (req, res) => {
    try {
        const { body, file } = req;
        if (body.contactInfo && typeof body.contactInfo === 'string') {
            try {
                body.contactInfo = JSON.parse(body.contactInfo);
            } catch (error) {
                return res.status(400).json({ error: 'Invalid JSON format for contactInfo' });
            }
        }
        const newPerson = new Persons({
            ...body,
            image: file?.path // Save the image URL from Cloudinary
        });
        const savedPerson = await newPerson.save();
        res.status(201).json(savedPerson);
    } catch (error) {
        res.status(400).json({ error: error.message });
    }
});

module.exports = router;
