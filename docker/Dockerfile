FROM romeoz/docker-apache-php:7.3

ARG DEBIAN_FRONTEND=noninteractive

RUN mkdir /root/libsrt
ADD libsrt /root/libsrt

RUN apt-get -y update && \
    apt-get install -y wget php-yaml nano mediainfo git build-essential yasm pkg-config \
                       libssl-dev libchromaprint-dev frei0r-plugins-dev libmpg123-0 libmpg123-dev libgnutls28-dev libopencv-dev ladspa-sdk libiec61883-dev libavc1394-dev libass-dev libbluray-dev libbs2b-dev libcaca-dev libdc1394-22-dev libdrm-dev flite1-dev libgme-dev libgsm1-dev libmp3lame-dev libopencv-dev libopenjp2-7-dev libopus-dev libpulse-dev librsvg2-dev librubberband-dev libshine-dev libsnappy-dev libsoxr-dev libspeex-dev libtwolame-dev libssh-dev libtheora-dev libvorbis-dev libvpx-dev libwavpack-dev libwebp-dev libx265-dev libxml2-dev libxvidcore-dev libx264-dev libzvbi-dev libopenal-dev libglew-dev libomxil-bellagio-dev libcdio-paranoia-dev libsdl2-dev && \
    dpkg -i /root/libsrt/libsrt1_1.3.2-1_amd64.deb /root/libsrt/libsrt-dev_1.3.2-1_amd64.deb && \
    git clone https://github.com/FFmpeg/FFmpeg /root/ffmpeg
RUN cd /root/ffmpeg && \
    ./configure \
      --enable-nonfree --disable-shared \
      --enable-libsrt \
      --enable-gpl --enable-swresample --enable-avisynth --enable-gnutls --enable-ladspa --enable-libass --enable-libbluray --enable-libbs2b --enable-libcaca --enable-libcdio --enable-libflite --enable-libfontconfig --enable-libfreetype --enable-libfribidi --enable-libgme --enable-libgsm --enable-libmp3lame --enable-libopenjpeg --enable-libopus --enable-libpulse --enable-librubberband --enable-librsvg --enable-libshine --enable-libsnappy --enable-libsoxr --enable-libspeex --enable-libssh --enable-libtheora --enable-libtwolame --enable-libvorbis --enable-libvpx --enable-libwavpack --enable-libwebp --enable-libx265 --enable-libxml2 --enable-libxvid --enable-libzvbi --enable-omx --enable-openal --enable-opengl --enable-sdl2 --enable-libdc1394 --enable-libdrm --enable-libiec61883 --enable-chromaprint --enable-frei0r --enable-libx264 \
      --extra-cflags=-I/usr/local/include && \
    make -j8 && \
    make install -j8 && \
    cd /root && rm -rf /root/ffmpeg

COPY config/ports.conf /etc/apache2/
COPY config/app.conf /etc/apache2/sites-enabled/

RUN mkdir /videos
RUN mkdir /validation-config

COPY config/entrypoint.sh /sbin/entrypoint.sh