#!/bin/bash

sudo apt-get install -qq -y python-pip

if [[ -z $SUPERVISOR_VERSION ]]; then
	sudo pip install supervisor
else
	sudo pip install supervisor==$SUPERVISOR_VERSION
fi

