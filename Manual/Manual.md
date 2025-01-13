# Product Inventory Management System - User Manual

## Overview
This manual provides instructions for using the Product Inventory Management System - a web-based solution for managing products, sales, and inventory across multiple branch locations.

## Getting Started

### System Requirements
- Web browser with JavaScript enabled
- Internet connection
- Valid user credentials (if applicable)

## Core Features

### 1. Product Management

#### Viewing Products
- Navigate to the products page
- Select a branch location from the dropdown
- View list of available products showing:
  - Product name
  - Unit price
  - Available quantity
  - Available sizes

#### Product Stock Information
- Stock levels are tracked per branch
- System prevents selling more than available stock
- Products with zero stock are not displayed in sales

### 2. Sales Management

#### Recording New Sales
1. Select the branch/outlet
2. Choose product from available inventory
3. Enter quantity
4. System automatically calculates total price
5. Confirm sale to record transaction

#### Viewing Sales Records
- Access sales history page
- Filter sales by outlet/branch (optional)
- View details including:
  - Product name
  - Quantity sold
  - Unit price
  - Net price
  - Sale date

#### Updating Sales
1. Locate sale record in sales history
2. Click edit/update button
3. Modify quantity or product details
4. System will:
   - Validate stock availability
   - Adjust inventory automatically
   - Update sale record
   - Prevent updates if insufficient stock

### 3. Stock Management

- Stock levels update automatically with sales
- System maintains separate stock counts per branch
- Prevents negative inventory through validation
- Stock adjustments occur in real-time

## Best Practices

1. Always verify stock availability before promising products to customers
2. Double-check quantities when recording sales
3. Review sales records regularly for accuracy
4. Monitor stock levels across branches

## Troubleshooting

### Common Issues

1. **Cannot See Products**
   - Verify branch selection
   - Check if products have available stock
   - Refresh page if needed

2. **Sale Update Fails**
   - Check if requested quantity exceeds available stock
   - Verify product is still active
   - Ensure all required fields are filled

3. **Stock Discrepancies**
   - Review recent sales transactions
   - Check for pending updates
   - Contact system administrator if issues persist

## Support

For technical support or questions:
- Review this documentation
- Contact system administrator
- Report issues with specific error messages
