# eBay Dropshipping Platform Plan

## Goal
Build a platform that helps sell more on eBay by making product sourcing, listing creation, pricing, order handling, and performance tracking faster and more repeatable.

## Product Outcome
The platform should let a user:
- Find products worth listing
- Create and manage eBay listings
- Track margins, sales, and inventory
- Automate repetitive operations where possible
- See what is working and what is not

## Core Problems To Solve
- Product research is fragmented and time-consuming
- Listing creation is repetitive and error-prone
- Pricing and profit tracking are hard to maintain manually
- Orders and inventory can drift out of sync
- It is difficult to know which products are actually profitable

## MVP Scope
### 1. Product Catalog
- Store products with title, description, cost, target sell price, supplier, stock status, and profit estimate
- Tag products by category, source, and listing status
- Support bulk import from CSV or manual entry

### 2. Listing Workflow
- Draft eBay-ready listings from product data
- Track listing states such as draft, ready, active, paused, sold, and archived
- Keep a revision history for edits and updates

### 3. Margin Tracking
- Calculate estimated profit after fees, shipping, and cost of goods
- Show margin per product and per listing
- Flag low-margin or unprofitable items

### 4. Order Tracking
- Record incoming orders
- Match orders to products and suppliers
- Track fulfillment status and exceptions

### 5. Inventory Sync
- Track available stock by product
- Warn when stock is low
- Prevent over-listing items that are out of stock

### 6. Dashboard
- Show sales, profit, active listings, and low-stock alerts
- Surface the top-performing products
- Surface listings that need attention

## Phase Plan
### Phase 1: Discovery and Workflow Design
- Define the exact user workflow from product sourcing to sale
- Decide what data is manual and what data can be automated
- Define the MVP user roles and permissions

### Phase 2: MVP Build
- Build authentication and project setup
- Build product catalog and listing management
- Build profit calculations and dashboard metrics
- Build order and inventory tracking

### Phase 3: Integration
- Connect eBay account data where needed
- Add import/export flows
- Add automation for common repetitive tasks

### Phase 4: Optimization
- Improve analytics and alerts
- Add search, filters, and bulk actions
- Add operational reports and audit history

## Key Decisions To Make Before Coding
- Whether the platform is single-user or multi-user
- Whether it is manual-first or API-first
- Whether it will integrate with eBay immediately or start with internal tracking only
- Which supplier sources must be supported first
- Which fulfillment flow we want to optimize first

## Tech Stack Decision Criteria
We should choose the stack based on:
- Speed to ship the MVP
- Ease of integrating with eBay
- Data modeling and reporting needs
- Background job support for imports, sync, and automation
- UI quality for a dashboard-heavy product
- Maintainability for future automation features

## Open Questions
- Do we want this to be a web app, a desktop app, or a browser extension plus web app?
- Do we need direct eBay API integration in the first version?
- Do we want supplier sync in the MVP, or only manual product entry?
- Will the first version be just for one operator or for a team?

