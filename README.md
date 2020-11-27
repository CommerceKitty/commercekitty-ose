Commerce Kitty
==============

Commerce Kitty is an open-source tool designed to help online retailers. It
supports things like Inventory Management, Listings, Order Manager, and more.

# Features

* Multi-channel Inventory Management
* Multi-channel Listings Management
* Support for multiple warehouses
* Support for kits & bundles
* Integrations with other helpful platforms (Klaviyo, QuickBooks, etc.)
* Automation
* Reporting
* Demand Forecasting
* Dynamic Repricing

# Requirements

* PHP
* Postgres
* Nginx

# Installation

1. Clone repo and change to that directory
2. Run `make install` to install dependencies
3. Run `make compile.dev` to compile dev assets
4. Run `make up` to run docker containers

NOTE: If you would like to load in demo data, run `make db.fixtures`.

# Configuration

All configuration options can be found in `.env`

# Documentation

For documentation, please review the [wiki](https://github.com/CommerceKitty/commercekitty/wiki).

# Sponsors

If you would like to sponsor this project, please click the "Sponsor" button at
the top of this page.
