# ---------------------------
# environment
# ---------------------------
variable "app_name" {
  description = "Project application name."
  type        = string
}

# ---------------------------
# variables
# ---------------------------
variable "region" {
  default = "ap-northeast-1"
}
variable "az_a" {
  default = "ap-northeast-1a"
}
variable "az_c" {
  default = "ap-northeast-1c"
}
variable "az_d" {
  default = "ap-northeast-1d"
}

# ---------------------------
# variables for application.
# ---------------------------
variable "vpc_cidr_block" {
  default = "10.4.0.0/16"
}
variable "public_subnet_1a_cidr_block" {
  default = "10.4.0.0/24"
}
variable "public_subnet_1c_cidr_block" {
  default = "10.4.16.0/24"
}
variable "key_name" {
  default = "console-keypair"
}

# ---------------------------
# variables in projects.
# ---------------------------
variable "allow_rw_access_s3_arn" {
  default = "arn:aws:iam::846076018720:policy/arrow_s3_policy"
}
