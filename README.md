# WordPress WhatsApp Notification Plugin

A lightweight WordPress and WooCommerce plugin that enables automated WhatsApp notifications for important website events such as user registration, comments, and order status updates.

---

## 🚀 Features

### 🔔 Admin Notifications
- New user registration alert
- New comment submission alert
- WooCommerce new order alert (Processing)
- Order status updates (Completed, On Hold)

### 📦 Customer Notifications
- Order status: On Hold
- Order status: Processing
- Order status: Completed

---

## 🛠 Technical Overview

- Built using WordPress hook-based architecture
- Utilizes WooCommerce order status hooks
- API-based WhatsApp message delivery
- Dynamic data injection (User Name, Order ID, Status, etc.)
- Secure credential storage
- Basic error handling and response validation
- Production-tested on live hosting environment

---

## ⚙️ Hooks Used

### WordPress Core Hooks
- `user_register`
- `comment_post`

### WooCommerce Hooks
- `woocommerce_order_status_processing`
- `woocommerce_order_status_completed`
- `woocommerce_order_status_on-hold`

---

## 🔐 Configuration

1. Install and activate the plugin.
2. Navigate to the plugin settings page.
3. Enter:
   - API Key / Authentication Token
4. Save settings.
5. Notifications will trigger automatically based on events.


---

## 📈 Use Case

This plugin is useful for:

- E-commerce store owners
- Hosting businesses
- Membership-based websites
- Automated customer engagement workflows

---

## 🧠 Learning Outcomes

- Understanding of WordPress hook system
- Event-driven backend logic
- API integration handling
- Structured debugging approach
- Production deployment experience

---

## 📌 Future Improvements

- Message template customization
- Retry mechanism for failed API calls
- Queue-based processing for bulk events
- Admin activity logs
