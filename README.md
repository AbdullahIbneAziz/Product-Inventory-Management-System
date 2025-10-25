# Product-Inventory-Management-System

A web-based inventory management system that helps track products, sales, and stock across multiple branch locations.

## Features

### Product Management
- View available products and their stock quantities by branch location
- Track product details including name, price, and available sizes
- Monitor stock levels across different branches

### Sales Management 
- Record and track sales transactions
- View detailed sales records filtered by outlet/branch
- Update existing sales with automatic stock adjustment
- Validation to prevent overselling (checks available stock)

### Stock Management
- Automatic stock updates when sales are recorded or modified
- Stock tracking per branch location
- Prevents negative inventory levels

### API Endpoints

The system provides the following REST API endpoints:

- `GET /get_products_for_sales.php` - Retrieve available products for a specific branch
- `GET /get_sales_records.php` - Get sales records with optional branch filter
- `GET /get_sale.php` - Get details of a specific sale
- `POST /update_sale.php` - Update an existing sale with stock validation

## Technical Details

- Built using PHP backend with MySQL/PDO database
- RESTful API architecture
- Transaction management for data integrity
- CORS enabled for cross-origin requests
- JSON response format

## Contact Us
- GitHub: https://github.com/AbdullahIbneAziz
- GitHub: https://github.com/AlRafiAhmed
- GitHub: https://github.com/MH-Mehedi06
- GitHub: https://github.com/muhaiminul-tafsir