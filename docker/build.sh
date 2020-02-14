#!/bin/bash
cp ../app ./
docker build . -t compuccino/encoder-validator:latest
docker push compuccino/encoder-validator:latest
rm -rf ./app