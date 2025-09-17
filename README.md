# Rental Application

A modern rental management application for handling property rentals, bookings, and customer management.

## 🚀 Deployment Guide

This guide covers multiple deployment options for the Rental application.

### Prerequisites

- Node.js 18+ (for Node.js applications)
- Python 3.8+ (for Python applications)
- Docker (for containerized deployment)
- Git

### 📋 Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/rohittttt7/rental.git
   cd rental
   ```

2. **Install dependencies**
   ```bash
   # For Node.js projects
   npm install
   
   # For Python projects
   pip install -r requirements.txt
   ```

3. **Set up environment variables**
   ```bash
   cp .env.example .env
   # Edit .env with your configuration
   ```

4. **Run the application**
   ```bash
   # For Node.js
   npm start
   
   # For Python
   python app.py
   ```

## 🐳 Docker Deployment

### Build and Run with Docker

```bash
# Build the Docker image
docker build -t rental-app .

# Run the container
docker run -p 3000:3000 rental-app
```

### Using Docker Compose

```bash
# Start all services
docker-compose up -d

# View logs
docker-compose logs -f

# Stop services
docker-compose down
```

## ☁️ Cloud Deployment

### Deploy to Heroku

1. **Install Heroku CLI**
   ```bash
   # On macOS
   brew tap heroku/brew && brew install heroku
   
   # On Ubuntu
   curl https://cli-assets.heroku.com/install.sh | sh
   ```

2. **Login and create app**
   ```bash
   heroku login
   heroku create your-rental-app-name
   ```

3. **Set environment variables**
   ```bash
   heroku config:set NODE_ENV=production
   heroku config:set DATABASE_URL=your-database-url
   ```

4. **Deploy**
   ```bash
   git push heroku main
   ```

### Deploy to Vercel

1. **Install Vercel CLI**
   ```bash
   npm install -g vercel
   ```

2. **Deploy**
   ```bash
   vercel --prod
   ```

### Deploy to Netlify

1. **Build the project**
   ```bash
   npm run build
   ```

2. **Deploy**
   ```bash
   # Install Netlify CLI
   npm install -g netlify-cli
   
   # Deploy
   netlify deploy --prod --dir=dist
   ```

## 🔧 Environment Configuration

Create a `.env` file in the root directory:

```env
# Application
PORT=3000
NODE_ENV=production

# Database
DATABASE_URL=postgresql://username:password@localhost:5432/rental_db
MONGODB_URI=mongodb://localhost:27017/rental

# Authentication
JWT_SECRET=your-super-secret-jwt-key
JWT_EXPIRES_IN=7d

# File Storage
CLOUDINARY_CLOUD_NAME=your-cloud-name
CLOUDINARY_API_KEY=your-api-key
CLOUDINARY_API_SECRET=your-api-secret

# Email
EMAIL_HOST=smtp.gmail.com
EMAIL_PORT=587
EMAIL_USER=your-email@gmail.com
EMAIL_PASS=your-app-password

# Payment
STRIPE_SECRET_KEY=sk_test_your-stripe-secret-key
STRIPE_PUBLISHABLE_KEY=pk_test_your-stripe-publishable-key
```

## 🚀 CI/CD with GitHub Actions

The repository includes automated deployment workflows. Pushes to `main` branch automatically deploy to production.

**Supported deployment targets:**
- Heroku
- Vercel
- Netlify
- Docker Registry

## 📱 Production Deployment Checklist

- [ ] Environment variables configured
- [ ] Database migrations run
- [ ] SSL certificate installed
- [ ] Domain configured
- [ ] Monitoring setup
- [ ] Backup strategy implemented
- [ ] Security headers configured
- [ ] Performance optimization enabled

## 🔍 Troubleshooting

### Common Issues

**Port already in use**
```bash
# Find and kill process using port 3000
lsof -ti:3000 | xargs kill -9
```

**Database connection failed**
- Verify database credentials in `.env`
- Ensure database server is running
- Check firewall settings

**Build failures**
- Clear node_modules and reinstall: `rm -rf node_modules && npm install`
- Check Node.js version compatibility
- Verify all environment variables are set

**Docker issues**
```bash
# Clean up Docker
docker system prune -a

# Rebuild without cache
docker build --no-cache -t rental-app .
```

## 📚 Additional Resources

- [Docker Documentation](https://docs.docker.com/)
- [Heroku Dev Center](https://devcenter.heroku.com/)
- [Vercel Documentation](https://vercel.com/docs)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit changes: `git commit -m 'Add amazing feature'`
4. Push to branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.