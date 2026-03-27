# Requirements Document

## Introduction

This document defines the requirements for the eBay Dropshipping Platform MVP. The platform is a single-user web application built on Laravel + Vue 3 + Inertia.js that helps an operator manage the full dropshipping workflow: sourcing products, creating eBay-ready listings, tracking margins and profitability, recording orders, monitoring inventory, and reviewing performance through a dashboard. The MVP is manual-first and does not require live eBay API integration; all data entry is manual or via CSV import.

The existing codebase already provides scaffolded models (User, Product, Listing, Order), migrations, and basic controllers. These requirements describe the complete intended behaviour of the finished MVP, including gaps that must still be built.

---

## Glossary

- **Platform**: The eBay Dropshipping Platform web application.
- **Operator**: The authenticated single user who owns and manages all data in the Platform.
- **Product**: A sourced item stored in the Platform with cost, pricing, supplier, and stock information.
- **Listing**: An eBay-ready record derived from a Product, representing one intended or active eBay sale entry.
- **Order**: A record of a sale event linked to a Listing and a Product, capturing buyer, price, quantity, and fulfillment state.
- **Margin**: The estimated profit for a Product or Listing, calculated as sell price minus cost of goods, eBay fee estimate, and estimated shipping cost.
- **eBay_Fee**: The estimated eBay marketplace fee applied to a sale, expressed as a percentage of the sale price.
- **Fulfillment_Status**: The current state of an Order's delivery lifecycle: `pending`, `processing`, `shipped`, `delivered`, `cancelled`, `exception`.
- **Listing_Status**: The current state of a Listing in its workflow: `draft`, `ready`, `active`, `paused`, `sold`, `archived`.
- **Product_Status**: The listing readiness state stored on a Product: `draft`, `ready`, `active`, `paused`, `sold`, `archived`.
- **Low_Stock_Threshold**: The stock quantity at or below which the Platform considers a Product to be low on stock. Default value is 5 units.
- **CSV_Import**: A bulk data import operation using a comma-separated values file.
- **Revision**: A snapshot of a Listing's field values captured each time the Listing is updated.
- **Dashboard**: The main summary view showing key operational metrics for the Operator.
- **Profit_Calculator**: The component responsible for computing margin values from cost, price, fee, and shipping inputs.

---

## Requirements

### Requirement 1: Product Catalog Management

**User Story:** As an Operator, I want to create, view, edit, and delete Products, so that I can maintain an accurate catalog of items available for dropshipping.

#### Acceptance Criteria

1. THE Platform SHALL require the Operator to be authenticated before accessing any Product data.
2. WHEN the Operator submits a new Product form with a title, cost, target price, and stock quantity, THE Platform SHALL persist the Product and associate it with the authenticated Operator.
3. WHEN the Operator submits a Product with a missing title, cost, target price, or stock quantity, THE Platform SHALL reject the submission and return a field-level validation error for each missing required field.
4. WHEN the Operator submits a Product with a cost or target price value less than zero, THE Platform SHALL reject the submission and return a validation error stating the value must be zero or greater.
5. WHEN the Operator submits a Product with a stock quantity less than zero, THE Platform SHALL reject the submission and return a validation error stating the value must be zero or greater.
6. THE Platform SHALL allow the Operator to optionally supply a SKU, category, supplier name, source URL, and notes when creating or editing a Product.
7. WHEN the Operator updates a Product, THE Platform SHALL persist the changes and display the updated values immediately on the Product list.
8. WHEN the Operator deletes a Product, THE Platform SHALL remove the Product record and redirect the Operator to the Product list.
9. THE Platform SHALL display all Products belonging to the authenticated Operator on the Product list page, ordered by most recently created first.
10. THE Platform SHALL prevent the Operator from viewing, editing, or deleting a Product that belongs to a different user, returning a 403 response.

---

### Requirement 2: Product Bulk Import via CSV

**User Story:** As an Operator, I want to import multiple Products at once from a CSV file, so that I can populate the catalog quickly without manual entry.

#### Acceptance Criteria

1. WHEN the Operator uploads a CSV file containing product rows, THE Platform SHALL parse each row and create a corresponding Product record associated with the authenticated Operator.
2. WHEN the CSV file contains a row with a missing title, cost, or target price, THE Platform SHALL skip that row, record a row-level error, and continue processing the remaining rows.
3. WHEN the CSV import completes, THE Platform SHALL display a summary showing the count of successfully imported rows and the count of skipped rows with their error reasons.
4. WHEN the Operator uploads a file that is not a valid CSV format, THE Platform SHALL reject the upload and return an error before attempting to parse any rows.
5. THE Platform SHALL support a CSV column mapping for at minimum: `title`, `sku`, `category`, `supplier_name`, `cost`, `target_price`, `stock_quantity`, `notes`.
6. WHEN the CSV file contains more than 500 rows, THE Platform SHALL process the import and not impose a lower row limit for the MVP.

---

### Requirement 3: Listing Workflow Management

**User Story:** As an Operator, I want to create and manage eBay-ready Listings derived from Products, so that I can track what is listed, what needs attention, and what has sold.

#### Acceptance Criteria

1. WHEN the Operator creates a Listing from a Product, THE Platform SHALL pre-populate the Listing title and price from the associated Product's title and target price.
2. THE Platform SHALL enforce that a Listing is always associated with exactly one Product.
3. THE Platform SHALL allow the Listing_Status to be set to one of: `draft`, `ready`, `active`, `paused`, `sold`, `archived`.
4. WHEN the Operator updates a Listing's title, price, quantity, status, or notes, THE Platform SHALL save a Revision capturing the previous field values, the changed field names, and the timestamp of the change.
5. THE Platform SHALL display the full Revision history for a Listing on the Listing detail page, ordered from most recent to oldest.
6. WHEN the Operator sets a Listing's status to `active` and the associated Product's stock_quantity is 0, THE Platform SHALL reject the status change and return an error stating the Product is out of stock.
7. THE Platform SHALL display all Listings belonging to the authenticated Operator on the Listing list page, with the associated Product title visible.
8. WHEN the Operator deletes a Listing, THE Platform SHALL remove the Listing and all associated Revisions, and redirect to the Listing list.
9. THE Platform SHALL allow the Operator to optionally record a marketplace_item_id and external_url on a Listing for reference.

---

### Requirement 4: Margin and Profit Calculation

**User Story:** As an Operator, I want the Platform to calculate estimated profit margins for each Product and Listing, so that I can make informed pricing decisions and avoid unprofitable sales.

#### Acceptance Criteria

1. THE Profit_Calculator SHALL compute the estimated margin for a Product as: `target_price − cost − (target_price × ebay_fee_rate) − estimated_shipping_cost`.
2. THE Profit_Calculator SHALL compute the estimated margin for a Listing as: `listing_price − product_cost − (listing_price × ebay_fee_rate) − estimated_shipping_cost`.
3. THE Platform SHALL display the calculated margin value alongside each Product on the Product list page.
4. THE Platform SHALL display the calculated margin value alongside each Listing on the Listing list page.
5. WHEN the calculated margin for a Product or Listing is less than zero, THE Platform SHALL visually flag that item as unprofitable on its respective list page.
6. WHEN the calculated margin for a Product or Listing is greater than or equal to zero but less than a configurable minimum margin threshold, THE Platform SHALL visually flag that item as low-margin on its respective list page.
7. THE Platform SHALL allow the Operator to configure the eBay fee rate (as a percentage) and the default estimated shipping cost used in margin calculations.
8. WHEN the Operator changes the eBay fee rate or default shipping cost, THE Profit_Calculator SHALL recalculate and display updated margin values for all affected Products and Listings without requiring a page reload.
9. THE Platform SHALL display the total estimated potential profit across all Products on the Dashboard, calculated as the sum of (margin_per_unit × stock_quantity) for each Product with stock_quantity greater than 0.

---

### Requirement 5: Order Recording and Tracking

**User Story:** As an Operator, I want to record and track incoming orders, so that I can manage fulfillment and know which products are selling.

#### Acceptance Criteria

1. WHEN the Operator creates an Order, THE Platform SHALL require a sale price, quantity, and Fulfillment_Status, and SHALL associate the Order with the authenticated Operator.
2. THE Platform SHALL allow the Operator to optionally link an Order to a Listing and a Product when creating or editing the Order.
3. THE Platform SHALL allow the Operator to set the Fulfillment_Status to one of: `pending`, `processing`, `shipped`, `delivered`, `cancelled`, `exception`.
4. WHEN the Operator updates an Order's Fulfillment_Status to `shipped`, THE Platform SHALL require a tracking number to be present on the Order before accepting the update.
5. WHEN the Operator marks an Order's Fulfillment_Status as `delivered`, THE Platform SHALL record the current timestamp as the delivery confirmation time on the Order.
6. THE Platform SHALL display all Orders belonging to the authenticated Operator on the Order list page, showing order number, buyer name, sale price, quantity, fulfillment status, and linked Product title.
7. THE Platform SHALL allow the Operator to filter the Order list by Fulfillment_Status.
8. WHEN an Order is linked to a Product and the Order's Fulfillment_Status is set to `delivered`, THE Platform SHALL decrement the linked Product's stock_quantity by the Order's quantity.
9. IF the Order quantity to be decremented exceeds the Product's current stock_quantity, THEN THE Platform SHALL set the Product's stock_quantity to 0 and record a warning in the Order's notes field.

---

### Requirement 6: Inventory Tracking and Low-Stock Alerts

**User Story:** As an Operator, I want the Platform to track stock levels and warn me when inventory is low, so that I can avoid over-selling or listing out-of-stock items.

#### Acceptance Criteria

1. THE Platform SHALL display the current stock_quantity for each Product on the Product list page.
2. WHEN a Product's stock_quantity is at or below the Low_Stock_Threshold, THE Platform SHALL visually flag that Product as low-stock on the Product list page.
3. THE Platform SHALL display the count of low-stock Products on the Dashboard.
4. WHEN the Operator attempts to set a Listing's Listing_Status to `active` and the associated Product's stock_quantity is 0, THE Platform SHALL prevent the status change and display an out-of-stock error message.
5. THE Platform SHALL allow the Operator to manually update a Product's stock_quantity at any time via the Product edit form.
6. WHEN a Product's stock_quantity reaches 0, THE Platform SHALL automatically set the Listing_Status of all `active` Listings associated with that Product to `paused`.

---

### Requirement 7: Dashboard and Performance Overview

**User Story:** As an Operator, I want a dashboard that summarises key metrics, so that I can quickly assess the health of my dropshipping operation.

#### Acceptance Criteria

1. THE Dashboard SHALL display the total count of Products in the catalog.
2. THE Dashboard SHALL display the count of Listings with Listing_Status of `active`.
3. THE Dashboard SHALL display the count of Orders with Fulfillment_Status of `pending`.
4. THE Dashboard SHALL display the count of Products at or below the Low_Stock_Threshold.
5. THE Dashboard SHALL display the total estimated potential profit across all Products with stock_quantity greater than 0.
6. THE Dashboard SHALL display the five most recently created Products.
7. THE Dashboard SHALL display the five most recently created Listings with their associated Product title.
8. THE Dashboard SHALL display the five most recently created Orders with their associated Product title and Fulfillment_Status.
9. WHEN there are Listings with Listing_Status of `draft` or `ready` that have not been updated in more than 7 days, THE Dashboard SHALL surface those Listings in an "Attention Required" section.
10. THE Dashboard SHALL display the top 5 Products by total revenue (sum of sale_price × quantity across all linked delivered Orders).

---

### Requirement 8: Authentication and Data Isolation

**User Story:** As an Operator, I want all my data to be private and protected by authentication, so that no other user can access or modify my records.

#### Acceptance Criteria

1. THE Platform SHALL require the Operator to log in before accessing any page other than the login, registration, and password reset pages.
2. WHEN an unauthenticated request is made to a protected route, THE Platform SHALL redirect the request to the login page.
3. THE Platform SHALL scope all Product, Listing, and Order queries to the authenticated Operator's user_id.
4. WHEN a request targets a Product, Listing, or Order that does not belong to the authenticated Operator, THE Platform SHALL return a 403 Forbidden response.
5. THE Platform SHALL use Laravel Breeze's existing session-based authentication without modification to the authentication flow.

---

### Requirement 9: Listing Revision History

**User Story:** As an Operator, I want a full edit history for each Listing, so that I can audit changes and understand how a listing evolved over time.

#### Acceptance Criteria

1. THE Platform SHALL store a Revision record each time a Listing's title, price, quantity, status, or notes field is changed.
2. EACH Revision SHALL record: the Listing ID, the field name that changed, the previous value, the new value, and the timestamp of the change.
3. THE Platform SHALL display the Revision history for a Listing in descending chronological order on the Listing detail page.
4. THE Platform SHALL retain all Revisions for a Listing until the Listing itself is deleted.
5. WHEN a Listing is deleted, THE Platform SHALL delete all associated Revision records.

---

### Requirement 10: Settings and Configuration

**User Story:** As an Operator, I want to configure platform-wide settings such as fee rates and thresholds, so that margin calculations and alerts reflect my actual business parameters.

#### Acceptance Criteria

1. THE Platform SHALL provide a Settings page accessible to the authenticated Operator.
2. THE Platform SHALL allow the Operator to set a global eBay fee rate expressed as a percentage between 0 and 100.
3. THE Platform SHALL allow the Operator to set a global default estimated shipping cost expressed as a non-negative decimal value.
4. THE Platform SHALL allow the Operator to set the Low_Stock_Threshold as a positive integer.
5. THE Platform SHALL allow the Operator to set a minimum margin threshold as a non-negative decimal value used to flag low-margin items.
6. WHEN the Operator saves updated settings, THE Platform SHALL persist the values and apply them immediately to all subsequent margin calculations and stock alerts.
7. IF the Operator submits a fee rate outside the range 0–100, THEN THE Platform SHALL reject the input and return a validation error.
