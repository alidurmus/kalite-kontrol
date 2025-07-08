# Node.js ve Playwright için güncel Dockerfile
FROM mcr.microsoft.com/playwright:v1.53.1-jammy

# Çalışma dizini
WORKDIR /app

# Proje dosyalarını kopyala
COPY package*.json ./
COPY playwright_test_kontrol_no.spec.js ./
COPY playwright_test_login.spec.js ./
COPY playwright_test_dashboard.spec.js ./
COPY playwright_test_kalite.spec.js ./
COPY requirements.txt ./

# Node.js bağımlılıklarını yükle
RUN npm install --save-dev @playwright/test

# Testleri çalıştırmak için komut
CMD ["npx", "playwright", "test"] 