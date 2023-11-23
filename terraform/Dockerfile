FROM hashicorp/terraform:1.6.4

# already installed terrarorm.

# install aws cli v2.
RUN apk add --no-cache bash py-pip \
    && pip install --upgrade pip awscli

COPY --chmod=777 ./entrypoint.sh /home/entrypoint.sh

WORKDIR /terraform

ENTRYPOINT ["/home/entrypoint.sh"]