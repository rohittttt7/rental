# Multi-stage Dockerfile for Rental Application
# Supports both Node.js and Python applications

# === Node.js Build Stage ===
FROM node:18-alpine AS node-builder
WORKDIR /app

# Copy package files if they exist
COPY package*.json ./
RUN if [ -f package.json ]; then npm ci --only=production; fi

# === Python Build Stage ===
FROM python:3.11-slim AS python-builder
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    gcc \
    && rm -rf /var/lib/apt/lists/*

# Copy requirements if they exist
COPY requirements*.txt ./
RUN if [ -f requirements.txt ]; then pip install --no-cache-dir -r requirements.txt; fi

# === Final Stage ===
FROM ubuntu:22.04
WORKDIR /app

# Install runtime dependencies
RUN apt-get update && apt-get install -y \
    curl \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js if package.json exists
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Python if requirements.txt exists
RUN apt-get update && apt-get install -y python3 python3-pip

# Copy application files
COPY . .

# Copy dependencies from build stages
COPY --from=node-builder /app/node_modules ./node_modules 2>/dev/null || true
COPY --from=python-builder /usr/local/lib/python3.11/site-packages /usr/local/lib/python3.11/site-packages 2>/dev/null || true

# Create non-root user
RUN useradd -m -u 1001 appuser && chown -R appuser:appuser /app
USER appuser

# Expose common ports
EXPOSE 3000 5000 8000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:3000/health || curl -f http://localhost:5000/health || curl -f http://localhost:8000/health || exit 1

# Default command - can be overridden
CMD ["sh", "-c", "if [ -f package.json ]; then npm start; elif [ -f app.py ]; then python3 app.py; elif [ -f main.py ]; then python3 main.py; else echo 'No startup script found'; fi"]