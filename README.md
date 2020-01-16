# Encoder Validator

## What is this?
The encoder validator is to test that the encoded stream from a live video encoder is actually encoding the parameters correctly.
## Installation
1. Install Docker and Docker Compose for your operating system. More info at https://www.docker.com
2. Clone this repository
3. Inside the repository run `docker-compose up -d`
4. Visit http://localhost:6250
## Usage
The idea is very simple. Take any encoding parameter you are sending anywhere and send it into this application instead and it will output the information and also test against validation files if you want.
### Steps
1. Figure out your public ip or private ip in an internal network. Linux and Mac uses `ifconfig`, Windows uses `ipconfig`. There are also graphical interface ways of doing it if you Google.
2. Visit http://localhost:6250
3. Click on the "Test using {protocol}" depending what protocol your encoder outputs.
4. Stream to the port that is shows on the public or private ip. RTMP doesn't need any application.
5. After 30 seconds the applications stops listening and your encoder might die. Otherwise just stop it.
6. Wait around 20 seconds for the application to verify your stream. After that it will show up in the list of tests.
7. If you just want to see the encoding information click "More Info"
8. If you want to test against a validation click on the dropdown and choose the validation.

## Setting up a validation config

Under the validation-configs create a .yaml file using YAML structure with an unique name. Follow the convention of the examples.

## Changing the Dockerfile

If something is needed to change on the Dockerfile, please run the `./build.sh` inside the docker folder afterwards so your changes are pushed.