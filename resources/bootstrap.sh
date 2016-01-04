#!/bin/bash

if [[ -z $SUPERVISOR_VERSION ]]; then
	pip install --user supervisor
else
	pip install --user supervisor==$SUPERVISOR_VERSION
fi

