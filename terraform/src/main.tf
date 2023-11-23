terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 4.16"
    }
  }

  required_version = ">= 1.6.4"
}

#----------------------------------------
# provider
#----------------------------------------
provider "aws" {
  region = var.region
}

provider "http" {}

#----------------------------------------
# create vpc.
#----------------------------------------
resource "aws_vpc" "vpc" {
  cidr_block           = var.vpc_cidr_block
  enable_dns_hostnames = true
  tags = {
    Name        = "${var.app_name}-vpc-${terraform.workspace}"
    Environment = "${terraform.workspace}"
  }
}

#----------------------------------------
# create subnet.
#----------------------------------------
resource "aws_subnet" "public-subnet-1a" {
  vpc_id            = aws_vpc.vpc.id
  cidr_block        = var.public_subnet_1a_cidr_block
  availability_zone = var.az_a
  tags = {
    Name        = "${var.app_name}-public-subnet-1a-${terraform.workspace}"
    Environment = "${terraform.workspace}"
  }
}
resource "aws_subnet" "public-subnet-1c" {
  vpc_id            = aws_vpc.vpc.id
  cidr_block        = var.public_subnet_1c_cidr_block
  availability_zone = var.az_c
  tags = {
    Name        = "${var.app_name}-public-subnet-1c-${terraform.workspace}"
    Environment = "${terraform.workspace}"
  }
}

#----------------------------------------
# create internet gateway.
#----------------------------------------
resource "aws_internet_gateway" "igw" {
  vpc_id = aws_vpc.vpc.id
  tags = {
    Name        = "${var.app_name}-igw-${terraform.workspace}"
    Environment = "${terraform.workspace}"
  }
}

#----------------------------------------
# create route table.
#----------------------------------------
resource "aws_route_table" "public_rt" {
  vpc_id = aws_vpc.vpc.id
  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.igw.id
  }
  tags = {
    Name        = "${var.app_name}-rt-${terraform.workspace}"
    Environment = "${terraform.workspace}"
  }
}
resource "aws_route_table_association" "public_rt_associate" {
  subnet_id      = aws_subnet.public-subnet-1a.id
  route_table_id = aws_route_table.public_rt.id
}

#----------------------------------------
# create security group.
#----------------------------------------
# get my public ip.
data "http" "ifconfig" {
  url = "http://ipv4.icanhazip.com/"
}

variable "allowed_cidr" {
  default = null
}

locals {
  myip         = chomp(data.http.ifconfig.response_body)
  allowed_cidr = (var.allowed_cidr == null) ? "${local.myip}/32" : var.allowed_cidr
}

resource "aws_security_group" "ec2_sg" {
  name        = "ec2-sg"
  description = "For EC2 Linux"
  vpc_id      = aws_vpc.vpc.id
  tags = {
    Name        = "${var.app_name}-ec2-sg-${terraform.workspace}"
    Environment = "${terraform.workspace}"
  }

  # inbound rule.
  ingress {
    description = "HTTP from internet"
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
  ingress {
    description = "HTTPS from internet"
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
  ingress {
    description = "SSH from my ip-adress."
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = [local.allowed_cidr]
  }

  # outbound rule.
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

#----------------------------------------
# create ec2 key pair.
#----------------------------------------
resource "tls_private_key" "keygen" {
  algorithm = "RSA"
  rsa_bits  = 2048
}

locals {
  public_key_file  = "./.key_pair/${var.key_name}.id_rsa.pub"
  private_key_file = "./.key_pair/${var.key_name}.id_rsa"
}

resource "local_file" "private_key_pem" {
  filename = local.private_key_file
  content  = tls_private_key.keygen.private_key_pem
  provisioner "local-exec" {
    command = "chmod 600 ${local.private_key_file}"
  }
}

resource "local_file" "public_key_openssh" {
  filename = local.public_key_file
  content  = tls_private_key.keygen.public_key_openssh
  provisioner "local-exec" {
    command = "chmod 600 ${local.public_key_file}"
  }
}

resource "aws_key_pair" "key_pair" {
  key_name   = var.key_name
  public_key = tls_private_key.keygen.public_key_openssh
}

#----------------------------------------
# create ec2 instance.
#----------------------------------------
data "aws_ami" "amzlinux2" {
  most_recent = true
  owners      = ["amazon"]

  filter {
    name   = "name"
    values = ["amzn2-ami-hvm-*-x86_64-gp2"]
  }
}

# attach role by create assume role.
resource "aws_iam_instance_profile" "ec2_profile" {
  name = "${var.app_name}_ec2_profile_${terraform.workspace}"
  role = aws_iam_role.ec2-role.name
}

data "aws_iam_policy_document" "assume_role" {
  statement {
    effect = "Allow"

    principals {
      type        = "Service"
      identifiers = ["ec2.amazonaws.com"]
    }

    actions = ["sts:AssumeRole"]
  }
}

resource "aws_iam_role" "ec2-role" {
  name               = "${var.app_name}-ec2-role-${terraform.workspace}"
  path               = "/"
  assume_role_policy = data.aws_iam_policy_document.assume_role.json
}

resource "aws_instance" "ec2-server" {
  ami                         = data.aws_ami.amzlinux2.id
  instance_type               = "t2.micro"
  availability_zone           = var.az_a
  vpc_security_group_ids      = [aws_security_group.ec2_sg.id]
  subnet_id                   = aws_subnet.public-subnet-1a.id
  associate_public_ip_address = "true"
  key_name                    = var.key_name
  tags = {
    Name        = "${var.app_name}-ec2-server-${terraform.workspace}"
    Environment = "${terraform.workspace}"
  }
  iam_instance_profile = aws_iam_instance_profile.ec2_profile.id
}

#----------------------------------------
# create Elastic IP.
#----------------------------------------
resource "aws_eip" "eip" {
  instance = aws_instance.ec2-server.id
  vpc      = true
}

#----------------------------------------
# create S3.
#----------------------------------------
resource "aws_s3_bucket" "s3-bucket" {
  bucket = "${var.app_name}-s3-bucket-${terraform.workspace}"

  tags = {
    Name        = "${var.app_name}-s3-bucket-${terraform.workspace}"
    Environment = "${terraform.workspace}"
  }
}

# arrow public access
resource "aws_s3_bucket_accelerate_configuration" "s3_public_access" {
  bucket = aws_s3_bucket.s3-bucket.id
  status = "Enabled"
}

## attach s3 full access to ec2.
# data "aws_iam_policy_document" "allow_rw_access_s3_role_policy" {
#   statement {
#     actions   = ["s3:*"]
#     resources = ["arn:aws:s3:::${aws_s3_bucket.s3-bucket.id}", "arn:aws:s3:::${aws_s3_bucket.s3-bucket.id}/*"]
#     effect    = "Allow"
#   }
# }

# resource "aws_iam_policy" "allow_rw_access_s3" {
#   name   = "arrow_s3_policy"
#   policy = data.aws_iam_policy_document.allow_rw_access_s3_role_policy.json
# }

resource "aws_iam_role_policy_attachment" "ec2-attach" {
  role       = aws_iam_role.ec2-role.name
  policy_arn = var.allow_rw_access_s3_arn
}
