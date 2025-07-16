FROM php:8.3-cli

# Copia os arquivos da app
WORKDIR /app
COPY ./ /app

# Expõe a porta do servidor
EXPOSE 8000

# Comando que inicia o servidor embutido
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/app"]
