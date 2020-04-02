FROM ubuntu:18.04

ARG DEBIAN_FRONTEND=noninteractive

ENV NGINX_VERSION nginx-1.15.0
ENV NGINX_RTMP_MODULE_VERSION 1.2.1

RUN mkdir /root/libsrt
ADD libsrt /root/libsrt

RUN apt-get -y update && \
    apt-get install -y wget php-yaml nano mediainfo git build-essential yasm pkg-config ca-certificates openssl php-fpm \
                       libssl-dev libchromaprint-dev frei0r-plugins-dev libmpg123-0 libmpg123-dev libgnutls28-dev libopencv-dev ladspa-sdk libiec61883-dev libavc1394-dev libass-dev libbluray-dev libbs2b-dev libcaca-dev libdc1394-22-dev libdrm-dev flite1-dev libgme-dev libgsm1-dev libmp3lame-dev libopencv-dev libopenjp2-7-dev libopus-dev libpulse-dev librsvg2-dev librubberband-dev libshine-dev libsnappy-dev libsoxr-dev libspeex-dev libtwolame-dev libssh-dev libtheora-dev libvorbis-dev libvpx-dev libwavpack-dev libwebp-dev libx265-dev libxml2-dev libxvidcore-dev libx264-dev libzvbi-dev libopenal-dev libglew-dev libomxil-bellagio-dev libcdio-paranoia-dev libsdl2-dev && \
    dpkg -i /root/libsrt/libsrt1_1.3.2-1_amd64.deb /root/libsrt/libsrt-dev_1.3.2-1_amd64.deb && \
    git clone https://github.com/FFmpeg/FFmpeg /root/ffmpeg && \
    rm -rf /var/lib/apt/lists/*
RUN cd /root/ffmpeg && \
    ./configure \
      --enable-nonfree --disable-shared \
      --enable-libsrt \
      --enable-gpl --enable-swresample --enable-avisynth --enable-gnutls --enable-ladspa --enable-libass --enable-libbluray --enable-libbs2b --enable-libcaca --enable-libcdio --enable-libflite --enable-libfontconfig --enable-libfreetype --enable-libfribidi --enable-libgme --enable-libgsm --enable-libmp3lame --enable-libopenjpeg --enable-libopus --enable-libpulse --enable-librubberband --enable-librsvg --enable-libshine --enable-libsnappy --enable-libsoxr --enable-libspeex --enable-libssh --enable-libtheora --enable-libtwolame --enable-libvorbis --enable-libvpx --enable-libwavpack --enable-libwebp --enable-libx265 --enable-libxml2 --enable-libxvid --enable-libzvbi --enable-omx --enable-openal --enable-opengl --enable-sdl2 --enable-libdc1394 --enable-libdrm --enable-libiec61883 --enable-chromaprint --enable-frei0r --enable-libx264 \
      --extra-cflags=-I/usr/local/include && \
    make -j8 && \
    make install -j8 && \
    cd /root && rm -rf /root/ffmpeg

RUN mkdir /videos
RUN mkdir /validation-config

RUN mkdir -p /tmp/build/nginx && \
    cd /tmp/build/nginx && \
    wget -O ${NGINX_VERSION}.tar.gz https://nginx.org/download/${NGINX_VERSION}.tar.gz && \
    tar -zxf ${NGINX_VERSION}.tar.gz

RUN mkdir -p /tmp/build/nginx-rtmp-module && \
    cd /tmp/build/nginx-rtmp-module && \
    wget -O nginx-rtmp-module-${NGINX_RTMP_MODULE_VERSION}.tar.gz https://github.com/arut/nginx-rtmp-module/archive/v${NGINX_RTMP_MODULE_VERSION}.tar.gz && \
    tar -zxf nginx-rtmp-module-${NGINX_RTMP_MODULE_VERSION}.tar.gz && \
    cd nginx-rtmp-module-${NGINX_RTMP_MODULE_VERSION}

RUN cd /tmp/build/nginx/${NGINX_VERSION} && \
    ./configure \
        --sbin-path=/usr/local/sbin/nginx \
        --conf-path=/etc/nginx/nginx.conf \
        --error-log-path=/var/log/nginx/error.log \
        --pid-path=/var/run/nginx/nginx.pid \
        --lock-path=/var/lock/nginx/nginx.lock \
        --http-log-path=/var/log/nginx/access.log \
        --http-client-body-temp-path=/tmp/nginx-client-body \
        --with-http_ssl_module \
        --with-threads \
        --with-ipv6 \
        --add-module=/tmp/build/nginx-rtmp-module/nginx-rtmp-module-${NGINX_RTMP_MODULE_VERSION} && \
    make -j $(getconf _NPROCESSORS_ONLN) && \
    make install && \
    mkdir /var/lock/nginx && \
    rm -rf /tmp/build

RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

RUN mkdir -p /run/php

COPY config/entrypoint.sh /sbin/entrypoint.sh
COPY config/nginx.conf /etc/nginx/nginx.conf
COPY config/www.conf /etc/php/7.2/fpm/pool.d/www.conf

CMD ["/sbin/entrypoint.sh"]