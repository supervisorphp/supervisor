#!/bin/bash

cd /tmp

wget https://pypi.python.org/packages/source/s/supervisor/supervisor-3.0.tar.gz
tar xfz supervisor-3.0.tar.gz
cd supervisor-3.0

sudo python setup.py install
