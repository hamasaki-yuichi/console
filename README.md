# Console Application

used by application management user.

## 1. How to setup develop environment.

- Setup mysql database.
- Create laravel project.
  Edit .env file.

## 2. How to commit

Branch operations are performed using below.

- main : release branch.
- develop : (default) develop & staging branch.
- feature : develop by feature function.  
  name rules.

  ```
  feature/(your_name)/yyyymmdd_(feature_name)
  ```

- hotfix : fix bug branch.

## 3. How to deploy

- Create AWS IAM user for this application.

- Create terraform container to run commands.

  ```
  $ docker-compose up terraform -d
  $ docker-compose exec terraform bash
  $ docker-compose --profile terraform down
  ```

### 3-1. Create AWS environment.

Create init AWS environment from terraform.

- Edit .env file from .env.example file.
- Create AWS environment.
  Set terraform workspace to replace env variables.
  replace : ${env} => ['dev', 'stg', 'prd']

  ```
  (terraform) >  terraform workspace new ${env}
  (terraform) >  terraform apply
  ```

- Move ec2 key-pair.

### 3-2. Setup Server.

Setup AWS EC2 server.

- Setup Nginx.
- Setup Mysql.
- PHP & laravel.
