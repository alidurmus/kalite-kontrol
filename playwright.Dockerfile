# Use the official Playwright image
FROM mcr.microsoft.com/playwright:v1.53.1-jammy

# Set working directory
WORKDIR /app

# Copy package.json file
COPY package.json ./

# Install dependencies (this will also create package-lock.json inside the image)
RUN npm install

# Copy the rest of the application files
COPY . .

# Run tests
CMD ["npx", "playwright", "test"] 