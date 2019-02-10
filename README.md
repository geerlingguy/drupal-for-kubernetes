# Drupal Example Site for Kubernetes

[![Build Status](https://travis-ci.com/geerlingguy/drupal-for-kubernetes.svg?branch=master)](https://travis-ci.com/geerlingguy/drupal-for-kubernetes)

The purpose of this Drupal codebase is to demonstrate a Drupal project and configuration set up for deployment into Kubernetes or an otherwise scalable containerized environment.

The project is used in tandem with the [Raspberry Pi Dramble](http://www.pidramble.com), an open source Kubernetes cluster tailor made for Drupal meant to run on a cluster of Raspberry Pis.

## Documentation

Please read through the [project documentation](docs/README.md) for details about how this project was created, how it's structured for easy development and deployment into production container environments, and how you can create your _own_ Drupal project like it.

## Local setup

  1. Build the site's docker image from the Dockerfile:

         ```
         docker build -t geerlingguy/drupal-for-kubernetes .
         ```

  1. Run the local development environment:

         ```
         docker-compose up -d
         ```

     (Wait for the environment to come up—you can monitor the logs with `docker-compose logs -f`).

  1. Once the container is running, install Drupal. You can either access http://localhost/ and install using the UI, or install via Drush:

         ```
         docker-compose exec drupal bash -c 'vendor/bin/drush site:install minimal --db-url="mysql://drupal:$DRUPAL_DB_PASSWORD@$DRUPAL_DB_HOST/drupal" --site-name="Drupal Example Site for Kubernetes" --existing-config -y'
         ```

  1. Visit http://localhost/ in your browser, and login as `admin` using the password Drush printed in the 'Installation complete' message.

### Managing Configuration

After making any configuration changes on the website, you can export the configuration to disk so it can be preserved in the codebase and deployed to the production site:

    docker-compose exec drupal bash -c 'vendor/bin/drush config:export -y'

## License

MIT license.

## Author Information

Created in 2019 by [Jeff Geerling](https://www.jeffgeerling.com/), author of [Ansible for DevOps](https://www.ansiblefordevops.com/) and [Ansible for Kubernetes](https://www.ansibleforkubernetes.com).
