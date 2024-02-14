FROM node:21-alpine

WORKDIR /app

# Install socket.io
RUN npm install socket.io

# Install ioredis
RUN npm install ioredis

CMD ["node", "server.js"]

EXPOSE 3000
