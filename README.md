# Requirements
- php-cli >=8.0 or Docker

# Run with docker
    #Execute in project directory
    docker build -t csv_parse .
    docker run -it --rm csv_parse /bin/bash -c "/usr/local/bin/php /var/www/csv_parser.php | jq"
