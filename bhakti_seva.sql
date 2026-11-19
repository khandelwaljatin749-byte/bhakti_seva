
CREATE DATABASE bhakti_seva;

USE bhakti_seva;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE temples (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    location VARCHAR(200),
    description TEXT,
    timings VARCHAR(100)
);

CREATE TABLE scriptures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    content TEXT
);

CREATE TABLE pandits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    experience VARCHAR(50),
    specialization VARCHAR(150),
    contact VARCHAR(20)
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    pandit_id INT NOT NULL,
    puja_type VARCHAR(150) NOT NULL,
    booking_date DATE NOT NULL,
    address TEXT NOT NULL,
    status VARCHAR(30) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (pandit_id) REFERENCES pandits(id)
);

CREATE TABLE mantras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150),
    lyrics TEXT,
    meaning TEXT
);

CREATE TABLE aartis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150),
    lyrics TEXT,
    meaning TEXT
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255)
);

INSERT INTO temples
(name, location, description, timings)
VALUES
('Shree Kashi Vishwanath Temple',
 'Varanasi, Uttar Pradesh',
 'One of the most famous temples dedicated to Lord Shiva.',
 '3:00 AM - 11:00 PM'),

('Shree Tirupati Balaji Temple',
 'Tirupati, Andhra Pradesh',
 'A famous temple dedicated to Lord Venkateswara.',
 '6:00 AM - 9:00 PM'),

('Shree Jagannath Temple',
 'Puri, Odisha',
 'A sacred Hindu temple dedicated to Lord Jagannath.',
 '5:00 AM - 10:00 PM'),

('Shree Somnath Temple',
 'Gujarat',
 'One of the twelve Jyotirlinga temples of Lord Shiva.',
 '6:00 AM - 9:00 PM');

INSERT INTO scriptures
(title, description, content)
VALUES
('Bhagavad Gita',
 'A sacred Hindu scripture containing the teachings of Lord Krishna.',
 'The Bhagavad Gita teaches us about duty, devotion, knowledge and selfless action.'),

('Ramayana',
 'The story of Lord Rama and his journey.',
 'The Ramayana teaches truth, courage, devotion and righteousness.'),

('Mahabharata',
 'An ancient Indian epic.',
 'The Mahabharata teaches the importance of dharma and justice.'),

('Vedas',
 'The oldest sacred scriptures of Hinduism.',
 'The Vedas contain hymns, knowledge, rituals and spiritual teachings.');

INSERT INTO pandits
(name, experience, specialization, contact)
VALUES
('Pandit Ramesh Sharma', '15 Years', 'Satyanarayan Puja', '9876543210'),
('Pandit Suresh Mishra', '10 Years', 'Griha Pravesh Puja', '9876543211'),
('Pandit Amit Shastri', '12 Years', 'Marriage Puja', '9876543212');

INSERT INTO mantras (title, lyrics, meaning)
VALUES
('Gayatri Mantra',
 'ॐ भूर्भुवः स्वः तत्सवितुर्वरेण्यं भर्गो देवस्य धीमहि धियो यो नः प्रचोदयात्॥',
 'A prayer for wisdom and enlightenment.'),

('Mahamrityunjaya Mantra',
 'ॐ त्र्यम्बकं यजामहे सुगन्धिं पुष्टिवर्धनम्। उर्वारुकमिव बन्धनान्मृत्योर्मुक्षीय मामृतात्॥',
 'A prayer to Lord Shiva for protection and well-being.'),

('Ganesh Mantra',
 'ॐ गं गणपतये नमः॥',
 'A prayer to Lord Ganesha for removing obstacles.');

INSERT INTO aartis (title, lyrics, meaning)
VALUES
('Jai Ganesh Deva',
 'जय गणेश जय गणेश जय गणेश देवा। माता जाकी पार्वती पिता महादेवा॥',
 'A devotional prayer to Lord Ganesha.'),

('Om Jai Shiv Omkara',
 'ॐ जय शिव ओंकारा, स्वामी जय शिव ओंकारा।',
 'A devotional prayer to Lord Shiva.'),

('Om Jai Jagdish Hare',
 'ॐ जय जगदीश हरे, स्वामी जय जगदीश हरे।',
 'A devotional prayer to Lord Vishnu.');
