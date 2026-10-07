# Hidden Insight – Image Privacy Scanner

## About the Project

Hidden Insight is a web-based image privacy scanner designed to help users identify sensitive information in images before sharing them online.

The system analyzes uploaded or captured images and detects potentially sensitive information such as personal data, hidden messages, and image metadata.

## Key Features

- Image upload and camera capture
- Detection of sensitive information
- OCR-based text analysis
- Detection of hidden messages
- Image metadata / EXIF analysis
- Sensitive data classification
- Metadata removal
- Scan history
- User and administrator accounts
- Admin dashboard for managing users and scan results

## Technologies Used

- PHP
- MySQL / MariaDB
- HTML
- CSS
- JavaScript
- Tailwind CSS
- Google Vision API
- OCR
- EXIF / Metadata Analysis

## Database

The project uses a MySQL/MariaDB database named:

`hidden_insight`

A clean database schema is included in:

`hidden_insight_clean.sql`

The schema contains tables for users, uploaded images, and scan results.

## Project Structure

```text
HiddenImageAnalysis/
├── admin/
├── assets/
├── user/
├── uploads/
├── analyze-image.php
├── analyze-image-process.php
├── login.php
├── signup.php
├── results.php
├── index.php
└── hidden_insight_clean.sql
