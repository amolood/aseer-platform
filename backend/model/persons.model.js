const mongoose = require("mongoose")

const personSchema = new mongoose.Schema({

    name: { type: String, required: true },
    age: Number,
    gender: { type: String, enum: ['ذكر', 'أنثى', 'male', "fmale"], required: true },
    image: { type: String }, // رابط الصورة
    lastSeenLocation: { type: String },
    dateMissing: { type: String, required: true },
    description: { type: String },
    status: { type: String, enum: ['مفقود', 'تم العثور عليه'], default: 'مفقود' },
    contactInfo: {
        name: String,
        phone: String,
        phone2: String,
        phone3: String,
        phone4: String,
        email: String
    }
}, { timestamps: true });

const Persons = mongoose.model("Person", personSchema)
module.exports = Persons