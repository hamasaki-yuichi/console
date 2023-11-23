#!/bin/bash

cd /terraform;
terraform init;

echo -e '\n\nenvironment';
echo '$AWS_ACCESS_KEY_ID=' $AWS_ACCESS_KEY_ID;
echo '$AWS_SECRET_ACCESS_KEY=' $AWS_SECRET_ACCESS_KEY;

tail -f /dev/null