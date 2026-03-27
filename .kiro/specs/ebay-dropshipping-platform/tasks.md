# Implementation Plan: eBay Dropshipping Platform

## Overview

Incremental implementation of the full MVP on top of the existing Laravel 11 + Vue 3 + Inertia.js scaffold. Tasks build from data layer outward to UI, wiring everything together at the end.

## Tasks

- [x] 1. Database migrations and model foundations
  - Create migration for `revisions` table with columns: `id`, `listing_id` (FK cascade delete), `field`, `old_value`, `new_value`, `created_at`; add index on `(listing_id, created_at DESC)`
  - Create migration for `settings` table with columns: `id`, `user_id` (FK cascade delete), `key`, `value`; add unique index on `(user_id, key)`
  - Add `delivered_at` column to `orders` table via a new migration
  - Create `Revision` Eloquent model with `belongsTo Listing` relationship and `$fillable`
  - Create `Setting` Eloquent model with `belongsTo User` relationship and `$fillable`
  - Add `hasMany Revision` to `Listing` model; add `hasMany Setting` to `User` model
  - Add `delivered_at` cast (`datetime`) and `delivered_at` to `$fillable` on `Order` model
  - _Requirements: 3.4, 9.1, 9.2, 10.1_

- [ ] 2. Settings service and configuration
  - [ ] 2.1 Implement `SettingsService` in `app/Services/SettingsService.php`
    - `getForUser(int $userId): array` — returns key-value array with defaults for `ebay_fee_rate` (0.1295), `default_shipping_cost` (0.00), `low_stock_threshold` (5), `min_margin_threshold` (0.00)
    - `save(int $userId, array $data): void` — upserts each key into the `settings` table
    - _Requirements: 10.1, 10.6_
  - [ ]* 2.2 Write unit tests for `SettingsService`
    - Test defaults returned when no rows exist
    - Test save persists values and subsequent read returns them
    - _Requirements: 10.6_
  - [ ] 2.3 Implement `SettingsController` with `index` (GET /settings) and `store` (POST /settings)
    - Validate: `ebay_fee_rate` numeric 0–100, `default_shipping_cost` numeric ≥ 0, `low_stock_threshold` integer ≥ 1, `min_margin_threshold` numeric ≥ 0
    - Return validation error for fee rate outside 0–100
    - _Requirements: 10.2, 10.3, 10.4, 10.5, 10.7_
  - [ ] 2.4 Create `Settings/Index.vue` page
    - Form with fields for all four settings, bound via Inertia `useForm`
    - Reactive computed margin preview using the formula `price - cost - (price × fee_rate) - shipping_cost`
    - _Requirements: 10.1, 4.8_
  - [ ]* 2.5 Write property test for settings validation and round-trip (`testSettingsValidationAndRoundTrip`)
    - **Property 24: Settings validation and round-trip**
    - **Validates: Requirements 10.2, 10.3, 10.4, 10.5, 10.6, 10.7**
  - Add `/settings` routes to `routes/web.php`
  - _Requirements: 10.1–10.7_

- [ ] 3. Profit calculator service
  - [ ] 3.1 Implement `ProfitCalculator` in `app/Services/ProfitCalculator.php`
    - `computeProductMargin(Product $product, array $settings): float` — `target_price - cost - (target_price × ebay_fee_rate) - default_shipping_cost`
    - `computeListingMargin(Listing $listing, array $settings): float` — `price - product.cost - (price × ebay_fee_rate) - default_shipping_cost`
    - _Requirements: 4.1, 4.2_
  - [ ]* 3.2 Write property test for margin formula correctness (`testMarginFormulaCorrectness`)
    - **Property 11: Margin formula correctness**
    - **Validates: Requirements 4.1, 4.2**
  - [ ]* 3.3 Write property tests for unprofitable and low-margin flags (`testUnprofitableFlag`, `testLowMarginFlag`)
    - **Property 12: Unprofitable flag when margin is negative**
    - **Property 13: Low-margin flag when margin is below threshold**
    - **Validates: Requirements 4.5, 4.6**
  - _Requirements: 4.1, 4.2, 4.5, 4.6_

- [ ] 4. Product catalog — complete CRUD and margin display
  - [ ] 4.1 Update `ProductController::index` to inject `ProfitCalculator` and `SettingsService`, append `margin`, `is_unprofitable`, and `is_low_margin` to each product in the response
    - _Requirements: 4.3, 4.5, 4.6_
  - [ ] 4.2 Update `Products/Index.vue` to display margin column with colour-coded badges (red = unprofitable, amber = low-margin), stock quantity, and low-stock visual flag
    - _Requirements: 4.3, 4.5, 4.6, 6.1, 6.2_
  - [ ]* 4.3 Write property test for product creation round-trip (`testProductCreationRoundTrip`)
    - **Property 1: Product creation round-trip**
    - **Validates: Requirements 1.2**
  - [ ]* 4.4 Write property test for required field validation (`testRequiredFieldValidation`)
    - **Property 2: Required field validation rejects incomplete products**
    - **Validates: Requirements 1.3, 1.4, 1.5**
  - [ ]* 4.5 Write property test for product list ordering (`testProductListOrdering`)
    - **Property 3: Product list ordering**
    - **Validates: Requirements 1.9**
  - [ ]* 4.6 Write property test for ownership enforcement (`testOwnershipEnforcement`)
    - **Property 4: Ownership enforcement returns 403**
    - **Validates: Requirements 1.10, 8.3, 8.4**
  - _Requirements: 1.2–1.10, 4.3, 4.5, 4.6, 6.1, 6.2_

- [ ] 5. CSV product import
  - [ ] 5.1 Implement `CsvImportService` in `app/Services/CsvImportService.php`
    - Parse uploaded CSV, map columns `title`, `sku`, `category`, `supplier_name`, `cost`, `target_price`, `stock_quantity`, `notes` (case-insensitive)
    - Skip rows missing `title`, `cost`, or `target_price`; collect row-level errors
    - Return `['imported' => int, 'skipped' => int, 'errors' => array]`
    - Reject non-CSV files before parsing
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 2.6_
  - [ ] 5.2 Implement `ProductImportController` with `create` (GET /products/import) and `store` (POST /products/import)
    - Validate MIME type; call `CsvImportService`; return import result as Inertia prop
    - _Requirements: 2.1, 2.3, 2.4_
  - [ ] 5.3 Create `Products/Import.vue` page with file upload form and result summary table
    - _Requirements: 2.3_
  - [ ]* 5.4 Write property test for CSV import row count invariant (`testCsvImportRowCountInvariant`)
    - **Property 5: CSV import row count invariant**
    - **Validates: Requirements 2.1, 2.2, 2.3**
  - [ ]* 5.5 Write property test for CSV column mapping round-trip (`testCsvColumnMappingRoundTrip`)
    - **Property 6: CSV column mapping round-trip**
    - **Validates: Requirements 2.5**
  - Add import routes to `routes/web.php`; add "Import" link to `Products/Index.vue` header
  - _Requirements: 2.1–2.6_

- [ ] 6. Checkpoint — ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 7. Revision service and listing CRUD
  - [ ] 7.1 Implement `RevisionService` in `app/Services/RevisionService.php`
    - `record(Listing $listing, array $oldValues, array $newValues): void` — writes one `Revision` row per changed field (title, price, quantity, status, notes) inside the caller's DB transaction
    - _Requirements: 3.4, 9.1, 9.2_
  - [ ]* 7.2 Write property test for revision completeness on listing update (`testRevisionCompletenessOnUpdate`)
    - **Property 8: Revision completeness on listing update**
    - **Validates: Requirements 3.4, 9.1, 9.2**
  - [ ]* 7.3 Write property test for revision ordering (`testRevisionOrdering`)
    - **Property 9: Revision ordering**
    - **Validates: Requirements 3.5, 9.3**
  - [ ] 7.4 Implement full `ListingController` (create, store, show, edit, update, destroy)
    - `store`: pre-populate title and price from the linked product; validate status enum; associate with authenticated user
    - `update`: wrap in `DB::transaction`, call `RevisionService::record` before saving; block `active` status if product stock = 0
    - `destroy`: delete listing (cascade removes revisions); redirect to listing list
    - Inject `ProfitCalculator` and `SettingsService`; append `margin`, `is_unprofitable`, `is_low_margin` to each listing in index response
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.6, 3.7, 3.8, 3.9, 4.4, 6.4_
  - [ ]* 7.5 Write property test for listing pre-population from product (`testListingPrePopulation`)
    - **Property 7: Listing pre-population from product**
    - **Validates: Requirements 3.1**
  - [ ]* 7.6 Write property test for cascade delete removes revisions (`testCascadeDeleteRemovesRevisions`)
    - **Property 10: Cascade delete removes revisions**
    - **Validates: Requirements 3.8, 9.4, 9.5**
  - [ ] 7.7 Create `Listings/Create.vue`, `Listings/Edit.vue`, and `Listings/Show.vue` pages
    - Create/Edit: product selector dropdown, status selector, marketplace_item_id and external_url fields, margin preview
    - Show: display listing details and full revision history table (descending order)
    - _Requirements: 3.1, 3.3, 3.5, 3.9_
  - [ ] 7.8 Update `Listings/Index.vue` to display margin column with colour-coded badges and add Create/Edit/Delete action links
    - _Requirements: 3.7, 4.4, 4.5, 4.6_
  - Add full listing routes to `routes/web.php`
  - _Requirements: 3.1–3.9, 4.4, 9.1–9.5_

- [ ] 8. Order service and full order CRUD
  - [ ] 8.1 Implement `OrderService` in `app/Services/OrderService.php`
    - `markDelivered(Order $order): void` — inside `DB::transaction` with pessimistic lock on Product: set `fulfillment_status = delivered`, record `delivered_at = now()`, decrement `product.stock_quantity` by order quantity (floor at 0, append warning to notes if underflow), then call auto-pause logic
    - `autoPauseActiveListings(Product $product): void` — if `product.stock_quantity === 0`, set all `active` listings for that product to `paused`
    - _Requirements: 5.5, 5.8, 5.9, 6.6_
  - [ ]* 8.2 Write property test for stock decrement on delivery (`testStockDecrementOnDelivery`)
    - **Property 21: Stock decrement on delivery**
    - **Validates: Requirements 5.8, 5.9**
  - [ ]* 8.3 Write property test for auto-pause on zero stock (`testAutoPauseOnZeroStock`)
    - **Property 22: Auto-pause active listings when stock reaches zero**
    - **Validates: Requirements 6.6**
  - [ ] 8.4 Implement full `OrderController` (index, create, store, edit, update, destroy)
    - `index`: scope to user, eager-load product and listing, support `fulfillment_status` filter query param
    - `store`: validate sale_price, quantity, fulfillment_status; optionally link listing and product
    - `update`: validate shipped status requires tracking_number; call `OrderService::markDelivered` when status transitions to `delivered`
    - `destroy`: delete order, redirect to order list
    - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5, 5.6, 5.7_
  - [ ]* 8.5 Write property test for order fulfillment status filter (`testOrderStatusFilter`)
    - **Property 20: Order fulfillment status filter**
    - **Validates: Requirements 5.7**
  - [ ] 8.6 Create `Orders/Create.vue` and `Orders/Edit.vue` pages
    - Fields: order_number, buyer_name, sale_price, quantity, fulfillment_status, tracking_number, notes; optional listing and product selectors
    - _Requirements: 5.1, 5.2, 5.3, 5.4_
  - [ ] 8.7 Update `Orders/Index.vue` to add status filter UI, Create/Edit/Delete action links, and display quantity column
    - _Requirements: 5.6, 5.7_
  - Add full order routes to `routes/web.php`
  - _Requirements: 5.1–5.9_

- [ ] 9. Inventory and low-stock logic
  - [ ] 9.1 Update `ProductController::index` and `ProductController::update` to use `SettingsService` for the `low_stock_threshold` value (replacing the hardcoded `5` in `DashboardController`)
    - _Requirements: 6.1, 6.2, 6.5_
  - [ ]* 9.2 Write property test for low-stock flag consistency (`testLowStockFlagConsistency`)
    - **Property 23: Low-stock flag consistency**
    - **Validates: Requirements 6.2, 6.3**
  - _Requirements: 6.1–6.6_

- [ ] 10. Dashboard — complete stats, attention listings, and top products
  - [ ] 10.1 Update `DashboardController` to inject `SettingsService` and `ProfitCalculator`
    - Use dynamic `low_stock_threshold` from settings
    - Compute `potential_profit` using `ProfitCalculator` (margin × stock_quantity per product)
    - Add `attention_listings`: listings with status `draft` or `ready` and `updated_at` older than 7 days
    - Add `top_products`: top 5 products by sum of `sale_price × quantity` across delivered orders, ordered descending
    - _Requirements: 4.9, 7.1–7.10_
  - [ ]* 10.2 Write property test for dashboard stats accuracy (`testDashboardStatsAccuracy`)
    - **Property 15: Dashboard stats accuracy**
    - **Validates: Requirements 4.9, 7.1, 7.2, 7.3, 7.4, 7.5**
  - [ ]* 10.3 Write property test for dashboard recent items bounded and ordered (`testDashboardRecentItemsBounded`)
    - **Property 16: Dashboard recent items are correctly bounded and ordered**
    - **Validates: Requirements 7.6, 7.7, 7.8**
  - [ ]* 10.4 Write property test for attention listings staleness (`testAttentionListingsStaleness`)
    - **Property 17: Attention Required surfaces stale draft/ready listings**
    - **Validates: Requirements 7.9**
  - [ ]* 10.5 Write property test for top products ranking (`testTopProductsRanking`)
    - **Property 18: Top products by revenue ranking**
    - **Validates: Requirements 7.10**
  - [ ] 10.6 Update `Dashboard.vue` to display low_stock stat card, attention_listings section, and top_products section
    - _Requirements: 7.4, 7.9, 7.10_
  - _Requirements: 4.9, 7.1–7.10_

- [ ] 11. Authentication and data isolation hardening
  - [ ] 11.1 Ensure all routes for products, listings, orders, and settings are wrapped in the `auth` middleware in `routes/web.php`
    - _Requirements: 8.1, 8.2_
  - [ ]* 11.2 Write property test for unauthenticated requests redirected to login (`testUnauthenticatedRequestsRedirected`)
    - **Property 19: Unauthenticated requests are redirected to login**
    - **Validates: Requirements 8.1, 8.2**
  - _Requirements: 8.1–8.5_

- [ ] 12. Final checkpoint — ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional and can be skipped for a faster MVP
- Each task references specific requirements for traceability
- Property tests use `eris/eris` and run a minimum of 100 iterations; install with `composer require --dev giorgiosironi/eris`
- Each property test file should include the comment `// Feature: ebay-dropshipping-platform, Property N: <property_text>`
- Checkpoints ensure incremental validation before moving to the next phase
