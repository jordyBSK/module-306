# Football Shop - Module 306

A modern e-commerce website for football jerseys built with Symfony, PostgreSQL, Docker, and Tabler UI.

## Features

🏆 **Product Showcase**: Browse a collection of football jerseys from top teams  
🛒 **Shopping Cart**: Add items to cart with persistent storage (no account required)  
💳 **Payment Simulation**: Support for Credit Card and PayPal payments  
📱 **Mobile Responsive**: Optimized for both web and mobile devices  
⚙️ **Admin Panel**: Easy product management interface  
🐳 **Docker Ready**: Complete containerized environment  

## Tech Stack

- **Backend**: Symfony 7.0 + PHP 8.1
- **Database**: PostgreSQL 13
- **Frontend**: Tabler UI Framework
- **Container**: Docker + Docker Compose

## Quick Start

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd module-306
   ```

2. **Start with Docker**
   ```bash
   docker-compose up --build
   ```

3. **Access the application**
   - **Shop**: http://localhost:8000
   - **Admin Panel**: http://localhost:8000/admin

## Development Setup

### Prerequisites
- Docker & Docker Compose
- Git

### Running the Application

```bash
# Start all services
docker-compose up -d

# View logs
docker-compose logs -f web

# Stop services
docker-compose down
```

### Database

The application automatically:
- Creates the database schema
- Loads sample data with popular football teams
- Handles migrations

## Project Structure

```
module-306/
├── src/
│   ├── Controller/          # Symfony controllers
│   ├── Entity/             # Database entities
│   └── Form/               # Form types
├── templates/              # Twig templates
│   ├── admin/             # Admin panel templates
│   └── shop/              # Shop frontend templates
├── config/                # Symfony configuration
├── migrations/            # Database migrations
├── public/                # Web assets
├── docker-compose.yml     # Docker services
├── Dockerfile            # Application container
└── sample_data.sql       # Initial data
```

## Features Overview

### Customer Features
- Browse football jerseys by team, size, and season
- Add products to shopping cart
- Persistent cart storage (no registration required)
- Secure checkout process
- Credit card and PayPal payment simulation
- Mobile-responsive design

### Admin Features
- Product management (Create, Read, Update, Delete)
- Price and stock management
- Product image management
- Inventory tracking

## Sample Data

The application comes with sample jerseys from popular teams:
- Paris Saint-Germain
- Real Madrid
- FC Barcelona
- Manchester United
- Liverpool FC
- Bayern Munich
- Juventus
- AC Milan
- Chelsea FC
- Arsenal FC

## Configuration

### Environment Variables
- `DATABASE_URL`: PostgreSQL connection string
- `APP_ENV`: Application environment (dev/prod)
- `APP_SECRET`: Symfony secret key

### Docker Services
- **web**: Symfony application (port 8000)
- **database**: PostgreSQL 13 (port 5432)

## API Endpoints

- `GET /` - Homepage with product catalog
- `POST /add-to-cart` - Add product to cart
- `GET /cart` - View shopping cart
- `GET /checkout` - Checkout page
- `POST /payment/process` - Process payment
- `GET /admin` - Admin dashboard
- `GET /admin/product/new` - Add new product

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License.