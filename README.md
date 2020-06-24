Commerce Kitty
==============

Commerce Kitty is an open-source tool designed to help online retailers. It
supports things like Inventory Management, Listings, Order Manager, and more.

# Features

* Multi-channel Inventory Management
* Multi-channel Listings Management
* Support for multiple warehouses

# Requirements

* PHP
* Postgres
* Nginx

# Installation

# Configuration

# Sponsors

---

# Dev Setup

Development is done using Docker Compose.

1. Edit `/etc/hosts` and add `127.0.0.1 commercekitty.local`
   * You will need to do this as root
2. Run `make selfsign` to create self signed SSL certs
3. Run `docker-compose up -d`
   * This will spin up php, nginx, and postgres
4. Open browser to http://commercekitty.local
