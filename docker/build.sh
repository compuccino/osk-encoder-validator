#!/bin/bash
cp -r ../app ./
docker build . -t compuccino/encoder-validator:latest
docker push compuccino/encoder-validator:latest
rm -rf ./app