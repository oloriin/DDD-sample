FROM ubuntu:16.04

RUN apt-get update
RUN apt-get install -y npm curl apt-transport-https ca-certificates
RUN curl -sL https://deb.nodesource.com/setup_8.x -o nodesource_setup.sh
RUN bash nodesource_setup.sh
RUN npm install npm@latest -g

# Add additional tools
RUN apt-get install -y nano links nodejs
RUN mkdir -p /var/www/ddd-sample

#CMD npm -v && nodejs -v && bash /root/init.sh && cd /var/www/ddd-sample && npm start
#bash /root/init.sh && cd /var/www/ddd-sample && npm run dev