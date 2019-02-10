# Drupal Example Site for Kubernetes

The purpose of this Drupal codebase is to demonstrate a Drupal project and configuration set up for deployment into Kubernetes or an otherwise scalable containerized environment.

The project is used in tandem with the [Raspberry Pi Dramble](http://www.pidramble.com), an open source Kubernetes cluster tailor made for Drupal meant to run on a cluster of Raspberry Pis.

## Initial setup

Part of the purpose of this project is to outline the exact steps required to build _your own_ Drupal site which is deployable inside Kubernetes, so the following section outlines every step used to initialize the codebase and prep it for a container environment:

  1. Build the basic Drupal codebase using the [Composer template for Drupal projects](https://github.com/drupal-composer/drupal-project):

         ```
         composer create-project drupal-composer/drupal-project:8.x-dev directory --no-interaction
         ```

  1. Create a [`Dockerfile`](Dockerfile) and [`docker-compose.yml`](docker-compose.yml) to build a Docker image to run the site both locally and in production—using the exact same Docker container!
  1. Build the docker image from the Dockerfile:

         ```
         docker build -t geerlingguy/drupal-example-kubernetes .
         ```

  1. Verify you can run the site in the container image locally:

         ```
         docker-compose up -d
         ```

     (Wait for the environment to come up—you can monitor the logs with `docker-compose logs -f`).

  1. Once the container is running, you need to install Drupal. You can either access http://localhost/ and install using the UI, or install via Drush:

         ```
         docker-compose exec drupal bash -c "vendor/bin/drush site-install standard --db-url="mysql://drupal:$DRUPAL_DB_PASSWORD@$DRUPAL_DB_HOST/drupal" --site-name='Drupal Example Site for Kubernetes' -y"
         ```

  1. Visit http://localhost/ in your browser, and login as `admin` using the password Drush printed in the 'Installation complete' message.

## Push the image to a Docker registry

TODO.

## License

MIT license.

## Author Information

Created in 2019 by [Jeff Geerling](https://www.jeffgeerling.com/), author of [Ansible for DevOps](https://www.ansiblefordevops.com/) and [Ansible for Kubernetes](https://www.ansibleforkubernetes.com).
