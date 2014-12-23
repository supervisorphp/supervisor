#!/bin/bash

cd /tmp

wget https://pypi.python.org/packages/source/s/supervisor/supervisor-3.1.3.tar.gz
tar xfz supervisor-3.1.3.tar.gz
cd supervisor-3.1.3

sudo python setup.py install
