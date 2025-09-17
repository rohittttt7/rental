# Simple Dockerfile for Rental Application
FROM node:18-alpine

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci --only=production

# Copy application files
COPY . .

# Create non-root user
RUN addgroup -g 1001 -S nodejs && \
    adduser -S rental -u 1001

# Change ownership of the app directory
RUN chown -R rental:nodejs /app
USER rental

# Expose port
EXPOSE 3000

# Start the application
CMD ["npm", "start"]