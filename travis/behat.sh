#!/bin/bash

./bin/behat --suite=installation -n -f progress -p "$TEST_SUITE"
./bin/behat --suite=login -n -f progress -p "$TEST_SUITE"