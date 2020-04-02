#!/bin/bash
docker build . -t compuccino/encoder-validator:latest
docker push compuccino/encoder-validator:latest