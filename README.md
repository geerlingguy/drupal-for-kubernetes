# Drupal Example Site for Kubernetes

The purpose of this Drupal codebase is to demonstrate a Drupal project and configuration set up for deployment into Kubernetes or an otherwise scalable containerized environment.

The project is used in tandem with the [Raspberry Pi Dramble](http://www.pidramble.com), an open source Kubernetes cluster tailor made for Drupal meant to run on a cluster of Raspberry Pis.

## Initial setup

Part of the purpose of this project is to outline the exact steps required to build _your own_ Drupal site which is deployable inside Kubernetes, so the following section outlines every step used to initialize the codebase and prep it for a container environment:

  1. Build the basic Drupal codebase using the [Composer template for Drupal projects](https://github.com/drupal-composer/drupal-project):

         composer create-project drupal-composer/drupal-project:8.x-dev directory --no-interaction

  2. TODO.
