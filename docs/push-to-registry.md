# Push the Drupal image to a Docker registry

Once you have your Drupal Project ready for deployment to Kubernetes, you have to push it into a Docker Registry which the Kubernetes cluster is able to pull from.

> This guide assumes you're using the Raspberry Pi Dramble project for deployment—this Kubernetes cluster includes a built-in Docker Registry to make Docker image management easy—but the same process would apply using any Docker Registry, whether Docker Hub, Quay, Google Container Registry, Amazon ECR, or some other registry. Please see those registries' documentation for specific connection information.

Assuming you've already built a version of the Drupal container you're happy with using `docker build -t geerlingguy/drupal-for-kubernetes .`, you need to tag it with the registry URL so it can be pushed:

    docker tag geerlingguy/drupal-for-kubernetes:latest registry.pidramble.test/geerlingguy/drupal-for-kubernetes:latest

At this point, if you run `docker images` locally, you should see something like:

    $ docker images
    REPOSITORY                                                  TAG      IMAGE ID       CREATED              SIZE
    geerlingguy/drupal-for-kubernetes                           latest   104e5df60d95   About a minute ago   645MB
    registry.pidramble.test/geerlingguy/drupal-for-kubernetes   latest   104e5df60d95   About a minute ago   645MB
    <none>                                                      <none>   a18f6568edb7   2 minutes ago        283MB
    composer                                                    latest   803583e27ea7   6 days ago           157MB
    geerlingguy/drupal                                          latest   17fa5bbef5a1   8 days ago           499MB

Assuming the Pi Dramble cluster is running and the registry is accessible at `registry.pidramble.test` (see the Raspberry Pi Dramble's documentation for more on registry setup), you can push the image to the Pi Dramble registry:

    docker push registry.pidramble.test/geerlingguy/drupal-for-kubernetes:latest
