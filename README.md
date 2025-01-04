# Vola Finance - Laravel Transaction Analysis Project

This project is a Laravel-based financial transaction analysis system that provides APIs for calculating user balances, transaction statistics, and financial analytics.

## Requirements

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js >= 16.0
- NPM >= 8.0

## Installation

1. Clone the repository
```bash
git clone <repository-url>
cd vola-finance
```

2. Install PHP dependencies
```bash
composer install
```

3. Install JavaScript dependencies
```bash
npm install
```

4. Configure environment
```bash
cp .env.example .env
```
Update the `.env` file with your database credentials and other configuration settings:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vola_finance
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Generate application key
```bash
php artisan key:generate
```

6. Run database migrations
```bash
php artisan migrate
```

7. Seed the database with transaction data
```bash
php artisan db:seed --class=TransactionsTableSeeder
```

8. Compile assets
```bash
npm run dev
```

9. Start the development server
```bash
php artisan serve
```

## Project Structure

```
vola-finance/
├── app/
│   ├── Http/Controllers/Api/
│   │   └── TransactionAnalysisController.php
│   └── Models/
│       └── Transaction.php
├── database/
│   ├── migrations/
│   │   └── create_transactions_table.php
│   ├── seeders/
│   │   └── TransactionsTableSeeder.php
│   └── data/
│       └── transaction.php
├── resources/
│   └── views/
│       └── welcome.blade.php
└── routes/
    └── api.php
```

## API Documentation

### 1. Get Closing Balances

Retrieves daily closing balances and averages for a specified user.

```
GET /api/closing-balances
```

Query Parameters:
- `user_id` (required): The ID of the user

Response:
```json
{
    "daily_balances": {
        "2024-01-04": 105.50,
        // ... more dates
    },
    "ninety_day_average": 98.75,
    "first_30_days_average": 95.20,
    "last_30_days_average": 102.30
}
```

### 2. Get Transaction Analysis

Retrieves various transaction statistics for a specified user.

```
GET /api/transaction-analysis
```

Query Parameters:
- `user_id` (required): The ID of the user

Response:
```json
{
    "last_30_days_income": 1250.75,
    "debit_transaction_count": 45,
    "weekend_debit_sum": 850.25,
    "high_income_sum": 2500.00
}
```

## Features

1. Daily closing balance calculation
2. 90-day average balance
3. First 30 and last 30 days average closing balance
4. Last 30 days income calculation (excluding category 18020004)
5. Debit transaction count in last 30 days
6. Weekend transaction analysis
7. High-value income analysis (transactions > $15)

## Testing

1. Run PHPUnit tests:
```bash
php artisan test
```

2. Test API endpoints using cURL:

```bash
# Test closing balances
curl "http://localhost:8000/api/closing-balances?user_id=1194398"

# Test transaction analysis
curl "http://localhost:8000/api/transaction-analysis?user_id=1194398"
```

Or using a REST client like Postman:
1. Import the included Postman collection from `postman/Vola_Finance_API.json`
2. Set the `base_url` variable to your local server URL
3. Run the requests in the collection

## Landing Page

The landing page can be accessed at the root URL (`/`) and includes:
- Navigation menu
- Hero section with call-to-action buttons
- Service cards showcasing different financial services
- Responsive design for all screen sizes

## Error Handling

The API includes comprehensive error handling for:
- Invalid user IDs
- Missing parameters
- Database connection issues
- Invalid date ranges
- Data validation errors

## Security

- API routes are protected using Laravel Sanctum
- Input validation for all API endpoints
- SQL injection protection
- XSS protection

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE.md file for details

## Support

For support, please contact: [your-email@example.com]