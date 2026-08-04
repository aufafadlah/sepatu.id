# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Stack

Laravel 11, Laravel Breeze (Blade templates), Tailwind CSS, Alpine.js, MySQL

## Users

Primary users are Indonesian sneaker enthusiasts, urban youth, and retail customers seeking authentic local streetwear footwear. Internal users include 4 specialized admin roles (Super Admin, Admin Produk, Admin Gudang, Admin Pesanan) managing store operations.

## Product Purpose

Sepatu.id is a specialized single-brand footwear e-commerce platform that simplifies catalog browsing, stock availability tracking per shoe size, authenticated customer reviews, and multi-role admin inventory/order management.

## Positioning

A dedicated local sneaker & streetwear brand web store offering verified buyer reviews, size-level stock transparency, and real-time interactive catalog filtering.

## Operating Context

Customers shop online via mobile or desktop browsers to browse catalog, manage wishlist/cart, checkout, track order status, and submit reviews. Store team members operate role-restricted admin panels to handle product CRUD, warehouse stock per variant, order status updates, and sales PDF exports.

## Capabilities and Constraints

- Capabilities: RBAC with 5 roles (Super Admin, Admin Produk, Admin Gudang, Admin Pesanan, Pelanggan), real-time Alpine.js catalog filter without full reloads, verified buyer reviews, PDF sales report export, low stock alerts, audit trail activity logging.
- Technical Constraints: Must adhere strictly to server-rendered Laravel 11 Blade templates, Tailwind CSS, and Alpine.js interactivity.

## Brand Commitments

- Brand Name: Sepatu.id
- Identity: Local sneaker & streetwear brand with a clean, minimalist visual aesthetic that focuses heavily on shoe craftsmanship and clear product details.

## Evidence on Hand

- Repository codebase with Laravel 11 routes, controllers, DB schema (`users`, `categories`, `products`, `product_variants`, `wishlists`, `carts`, `orders`, `order_items`, `reviews`, `activity_logs`), and seeders.
- Pre-configured role accounts (`superadmin@sepatu-id.test`, `adminproduk@sepatu-id.test`, `admingudang@sepatu-id.test`, `adminpesanan@sepatu-id.test`, `user@sepatu-id.test`).

## Product Principles

1. Clarity over Clutter: Product imagery and shoe details take center stage with clean visual hierarchy.
2. Size & Stock Transparency: Clear size selection and real-time inventory indicators prevent ordering friction.
3. Trust & Authenticity: Highlight verified buyer reviews and clear order tracking.
4. Role-Tailored Efficiency: Keep customer shopping effortless while admin panels remain lean and highly functional for daily store operations.

## Accessibility & Inclusion

Ensure high contrast ratios, responsive mobile layouts, clear focus states for keyboard navigation, and semantic HTML across all Blade templates.
