FROM node:18-alpine

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy application code
COPY . .

# Create uploads directory with proper permissions
RUN mkdir -p uploads/products && chmod -R 777 uploads

# Expose port
EXPOSE 5000

# Start application
CMD ["npm", "run", "dev"]
