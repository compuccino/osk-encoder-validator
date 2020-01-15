#!/bin/bash
docker build . -t registry.oskdev.de:5000/encoder-validator:latest
docker push registry.oskdev.de:5000/encoder-validator:latest