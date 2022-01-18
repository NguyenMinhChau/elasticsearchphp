#Dockerfile
# Image
FROM docker.elastic.co/elasticsearch/elasticsearch:7.3.1

COPY elasticsearch-analysis-vietnamese-7.3.1.zip /usr/share/elasticsearch/

RUN cd /usr/share/elasticsearch && \
    bin/elasticsearch-plugin install --batch file:///usr/share/elasticsearch/elasticsearch-analysis-vietnamese-7.3.1.zip && \
    bin/elasticsearch-plugin install analysis-icu

# CLI
# docker build -t docker.elastic.co/elasticsearch/elasticsearch:7.3.1 . //trùng với image của elasticsearch trong docker
# docker-compose up